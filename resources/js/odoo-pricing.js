export const formatChf = (cents) => {
    const [whole, fraction] = (Math.round(cents) / 100).toFixed(2).split('.')

    return `CHF ${whole.replace(/\B(?=(\d{3})+(?!\d))/g, "'")}.${fraction}`
}

const clamp = (value, min, max) => {
    const number = Math.trunc(Number(value))

    return Math.min(max, Math.max(min, Number.isFinite(number) ? number : min))
}

const countFrom = (value) => (typeof value === 'string' && /^\d{1,4}$/.test(value) ? Number(value) : undefined)

export const selectionFromAddress = (search) => {
    const params = new URLSearchParams(search)
    const billing = params.get('billing')

    return {
        full: countFrom(params.get('full')),
        light: countFrom(params.get('light')),
        billing: billing === 'monthly' || billing === 'yearly' ? billing : undefined,
    }
}

const fill = (label, replacements) =>
    Object.entries(replacements).reduce((text, [key, value]) => text.replace(`:${key}`, value), label ?? '')

export const calculate = (config, fullUsers, lightUsers, billing = 'yearly') => {
    const period = billing === 'monthly' ? 'monthly' : 'yearly'
    const light = clamp(lightUsers, config.light_users.min, config.max_users)
    const wanted = clamp(fullUsers, config.full_users.min, config.max_users)
    const lightKept = Math.min(light, config.max_users - wanted)
    const full = wanted + lightKept < config.min_users ? config.min_users : wanted
    const people = full + lightKept
    const tier = config.tiers.find((candidate) => people <= candidate.up_to) ?? config.tiers[config.tiers.length - 1]
    const price = (cents, by = 1) => Math.round(cents * by * config.eur_to_chf)
    const factor = config.hosting.factor[period]

    const fullUnit = price(config.license.full[period])
    const lightUnit = price(config.license.light[period])
    const workerUnit = price(config.hosting.worker, factor)
    const storageUnit = price(config.hosting.storage_gb, factor)
    const stagingUnit = price(config.hosting.staging, factor)

    const storageWanted = people * config.storage_gb_per_user
    const storageGb = Math.min(storageWanted, config.storage_gb_max)

    const fullMonth = full * fullUnit
    const lightMonth = lightKept * lightUnit
    const workerMonth = tier.workers * workerUnit
    const storageMonth = storageGb * storageUnit
    const stagingMonth = config.staging * stagingUnit
    const hostingMonth = workerMonth + storageMonth + stagingMonth
    const licenseMonth = fullMonth + lightMonth

    return {
        full,
        light: lightKept,
        people,
        tier,
        fullUnit,
        lightUnit,
        storageUnit,
        storageGb,
        storageCapped: storageWanted > storageGb,
        fullMonth,
        lightMonth,
        licenseMonth,
        workerMonth,
        storageMonth,
        stagingMonth,
        hostingMonth,
        totalMonth: licenseMonth + hostingMonth,
    }
}

export const odooPricing = () => ({
    config: null,
    labels: {},
    full: 10,
    light: 0,
    billing: 'yearly',
    copied: false,
    copyLabel: '',
    copyTimer: null,

    init() {
        const server = JSON.parse(this.$root.dataset.selection ?? '{}')
        const address = selectionFromAddress(window.location.search)

        this.config = JSON.parse(this.$root.dataset.config)
        this.labels = JSON.parse(this.$root.dataset.labels)
        this.full = address.full ?? server.full ?? this.config.full_users.default
        this.light = address.light ?? server.light ?? this.config.light_users.default
        this.billing = (address.billing ?? server.billing) === 'monthly' ? 'monthly' : 'yearly'
        this.copyLabel = this.$root.dataset.copyLabel ?? ''
        this.settle('full')

        this.openFaqFromHash()

        this.$watch('full', () => this.update())
        this.$watch('light', () => this.update())
        this.$watch('billing', () => this.update())
        this.paint()
    },

    destroy() {
        clearTimeout(this.copyTimer)
    },

    get result() {
        return calculate(this.config, this.full, this.light, this.billing)
    },

    get query() {
        const { full, light } = this.result

        return { full, light, billing: this.isMonthly ? 'monthly' : 'yearly' }
    },

    withQuery(address) {
        const url = new URL(address, window.location.href)

        Object.entries(this.query).forEach(([key, value]) => url.searchParams.set(key, String(value)))

        return url
    },

    get summaryUrl() {
        return this.withQuery(this.$root.dataset.summaryUrl).toString()
    },

    update() {
        this.paint()
        window.history.replaceState(window.history.state, '', this.withQuery(window.location.href))
    },

    copy() {
        const url = this.summaryUrl

        if (!navigator.clipboard) {
            this.open(url)

            return Promise.resolve()
        }

        return navigator.clipboard.writeText(url).then(
            () => this.confirmCopied(),
            () => this.open(url),
        )
    },

    open(url) {
        window.location.assign(url)
    },

    confirmCopied() {
        this.copied = true
        this.copyLabel = this.$root.dataset.copyDone
        clearTimeout(this.copyTimer)
        this.copyTimer = setTimeout(() => {
            this.copied = false
            this.copyLabel = this.$root.dataset.copyLabel
        }, 2000)
    },

    get notCopied() {
        return !this.copied
    },

    normalize(event) {
        this.settle(event?.target?.dataset?.kind)
    },

    settle(kind) {
        const { max_users: max, min_users: min, full_users: fullUsers, light_users: lightUsers } = this.config

        if (kind === 'light') {
            this.light = clamp(this.light, lightUsers.min, max)
            this.full = clamp(this.full, fullUsers.min, max - this.light)

            if (this.light + this.full < min) {
                this.light = min
            }

            return
        }

        this.full = clamp(this.full, fullUsers.min, max)
        this.light = clamp(this.light, lightUsers.min, max - this.full)

        if (this.full + this.light < min) {
            this.full = min
        }
    },

    step(event) {
        const { kind, step } = event.currentTarget.dataset
        const field = kind === 'light' ? 'light' : 'full'

        this[field] = clamp(this[field], 0, this.config.max_users) + Number(step)
        this.settle(field)
    },

    openFaqFromHash() {
        const id = window.location.hash.slice(1)
        const target = id === '' ? null : document.getElementById(id)

        if (target instanceof HTMLDetailsElement) {
            target.open = true
        }
    },

    openFaq(event) {
        const target = document.getElementById(event.currentTarget.dataset.faq)

        if (target) {
            target.open = true
        }
    },

    setYearly() {
        this.billing = 'yearly'
    },

    setMonthly() {
        this.billing = 'monthly'
    },

    get isYearly() {
        return this.billing === 'yearly'
    },

    get isMonthly() {
        return this.billing === 'monthly'
    },

    paint() {
        const result = this.result
        const share = this.licenseShareValue

        this.track(this.$refs.fullRange, result.full)
        this.track(this.$refs.lightRange, result.light)
        this.$refs.lightRow.classList.toggle('invisible', result.light === 0)
        this.$refs.licenseBar.style.width = `${share}%`
        this.$refs.hostingBar.style.width = `${100 - share}%`
    },

    track(range, value) {
        const min = Number(range.min)
        const max = Number(range.max)

        range.style.setProperty('--fill', `${((value - min) / (max - min)) * 100}%`)
    },

    get licenseShareValue() {
        return Math.round((this.result.licenseMonth / this.result.totalMonth) * 100)
    },

    get licenseShare() {
        return `${this.licenseShareValue} %`
    },

    get hostingShare() {
        return `${100 - this.licenseShareValue} %`
    },

    get billingNote() {
        return this.isYearly ? this.labels.note_yearly : this.labels.note_monthly
    },

    get peopleLabel() {
        return this.result.people === 1 ? this.labels.user_one : fill(this.labels.users, { count: this.result.people })
    },

    get tierLabel() {
        return fill(this.labels.tier, { count: this.result.tier.up_to })
    },

    get storageLabel() {
        return fill(this.labels.gigabytes, { count: this.result.storageGb })
    },

    get storageTitle() {
        return fill(this.labels.storage_title, { count: this.result.storageGb })
    },

    get storageHint() {
        if (this.result.storageCapped) {
            return fill(this.labels.storage_capped, { max: this.config.storage_gb_max })
        }

        return fill(this.labels.storage_hint, {
            per: this.config.storage_gb_per_user,
            line: `${this.storageLabel} × ${formatChf(this.result.storageUnit)}`,
        })
    },

    get operationHint() {
        return fill(this.labels.operation_hint, { count: this.result.tier.up_to })
    },

    get fullUnit() {
        return formatChf(this.result.fullUnit)
    },

    get lightUnit() {
        return formatChf(this.result.lightUnit)
    },

    get fullLine() {
        return `${this.result.full} × ${formatChf(this.result.fullUnit)}`
    },

    get lightLine() {
        return `${this.result.light} × ${formatChf(this.result.lightUnit)}`
    },

    get fullMonth() {
        return formatChf(this.result.fullMonth)
    },

    get fullYear() {
        return formatChf(this.result.fullMonth * 12)
    },

    get lightMonth() {
        return formatChf(this.result.lightMonth)
    },

    get lightYear() {
        return formatChf(this.result.lightMonth * 12)
    },

    get licenseMonth() {
        return formatChf(this.result.licenseMonth)
    },

    get licenseYear() {
        return formatChf(this.result.licenseMonth * 12)
    },

    get operationMonth() {
        return formatChf(this.result.workerMonth)
    },

    get operationYear() {
        return formatChf(this.result.workerMonth * 12)
    },

    get storageMonth() {
        return formatChf(this.result.storageMonth)
    },

    get storageYear() {
        return formatChf(this.result.storageMonth * 12)
    },

    get stagingMonth() {
        return formatChf(this.result.stagingMonth)
    },

    get stagingYear() {
        return formatChf(this.result.stagingMonth * 12)
    },

    get hostingMonth() {
        return formatChf(this.result.hostingMonth)
    },

    get hostingYear() {
        return formatChf(this.result.hostingMonth * 12)
    },

    get totalMonth() {
        return formatChf(this.result.totalMonth)
    },

    get totalYear() {
        return formatChf(this.result.totalMonth * 12)
    },
})

export const formatChf = (cents) => {
    const [whole, fraction] = (Math.round(cents) / 100).toFixed(2).split('.')

    return `CHF ${whole.replace(/\B(?=(\d{3})+(?!\d))/g, "'")}.${fraction}`
}

const clamp = (value, min, max) => {
    const number = Math.trunc(Number(value))

    return Math.min(max, Math.max(min, Number.isFinite(number) ? number : min))
}

const fill = (label, replacements) =>
    Object.entries(replacements).reduce((text, [key, value]) => text.replace(`:${key}`, value), label ?? '')

export const calculate = (config, fullUsers, lightUsers, billing = 'yearly') => {
    const period = billing === 'monthly' ? 'monthly' : 'yearly'
    const full = clamp(fullUsers, config.full_users.min, config.max_users)
    const light = clamp(lightUsers, config.light_users.min, config.max_users - full)
    const people = full + light
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
    const lightMonth = light * lightUnit
    const workerMonth = tier.workers * workerUnit
    const storageMonth = storageGb * storageUnit
    const stagingMonth = config.staging * stagingUnit
    const hostingMonth = workerMonth + storageMonth + stagingMonth
    const licenseMonth = fullMonth + lightMonth

    return {
        full,
        light,
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

    init() {
        this.config = JSON.parse(this.$root.dataset.config)
        this.labels = JSON.parse(this.$root.dataset.labels)
        this.full = this.config.full_users.default
        this.light = this.config.light_users.default

        this.openFaqFromHash()

        this.$watch('full', () => this.paint())
        this.$watch('light', () => this.paint())
        this.$watch('billing', () => this.paint())
        this.paint()
    },

    get result() {
        return calculate(this.config, this.full, this.light, this.billing)
    },

    normalize() {
        const result = this.result

        this.full = result.full
        this.light = result.light
    },

    step(event) {
        const { kind, step } = event.currentTarget.dataset
        const current = kind === 'light' ? this.result.light : this.result.full

        this[kind === 'light' ? 'light' : 'full'] = current + Number(step)
        this.normalize()
    },

    openFaqFromHash() {
        const target = document.getElementById(window.location.hash.slice(1))

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

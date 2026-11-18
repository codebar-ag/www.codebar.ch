import { describe, expect, it } from 'vitest'

import { calculate, formatChf, odooPricing } from '../../resources/js/odoo-pricing.js'
import fixture from '../Fixtures/odoo-pricing.json'

const labels = {
    users: ':count Benutzer',
    user_one: '1 Benutzer',
    tier: 'bis :count Benutzer',
    gigabytes: ':count GB',
    note_yearly: 'jährlich',
    note_monthly: 'monatlich',
    operation_hint: 'Ausgelegt für bis :count Benutzer.',
    storage_title: 'Speicherplatz :count GB',
    storage_hint: ':per GB pro Benutzer, also :line',
    storage_capped: 'Höchstens :max GB.',
}

function range(min, max) {
    const element = document.createElement('input')
    element.type = 'range'
    element.min = String(min)
    element.max = String(max)

    return element
}

function mount() {
    const component = odooPricing()
    const root = document.createElement('div')

    root.dataset.config = JSON.stringify(fixture.config)
    root.dataset.labels = JSON.stringify(labels)

    component.$root = root
    component.$watch = () => {}
    component.$refs = {
        fullRange: range(fixture.config.full_users.min, fixture.config.max_users),
        lightRange: range(fixture.config.light_users.min, fixture.config.max_users),
        lightRow: document.createElement('tr'),
        licenseBar: document.createElement('div'),
        hostingBar: document.createElement('div'),
    }
    component.init()

    return component
}

function stepButton(kind, step) {
    const button = document.createElement('button')
    button.dataset.kind = kind
    button.dataset.step = String(step)

    return { currentTarget: button }
}

describe('the odoo price calculation', () => {
    it.each(fixture.cases)('matches the server for $full full, $light light, $billing', ({ full, light, billing, expected }) => {
        const result = calculate(fixture.config, full, light, billing)

        expect({
            full: result.full,
            light: result.light,
            people: result.people,
            tier: result.tier.up_to,
            storageGb: result.storageGb,
            storageCapped: result.storageCapped,
            licenseMonth: result.licenseMonth,
            workerMonth: result.workerMonth,
            storageMonth: result.storageMonth,
            stagingMonth: result.stagingMonth,
            hostingMonth: result.hostingMonth,
            totalMonth: result.totalMonth,
        }).toEqual(expected)
    })

    it.each([
        ['', 1],
        ['abc', 1],
        [null, 1],
        [undefined, 1],
        [Number.NaN, 1],
        [Number.POSITIVE_INFINITY, 1],
        [12.9, 12],
        ['12.9', 12],
        ['1e2', 100],
        [-3, 1],
        [100000, 150],
    ])('turns the input %o into %i full users', (input, expected) => {
        expect(calculate(fixture.config, input, 0).full).toBe(expected)
    })

    it('falls back to yearly billing for an unknown period', () => {
        expect(calculate(fixture.config, 10, 0, 'weekly')).toEqual(calculate(fixture.config, 10, 0, 'yearly'))
    })

    it.each([
        [0, 'CHF 0.00'],
        [790, 'CHF 7.90'],
        [620400, "CHF 6'204.00"],
        [8540304, "CHF 85'403.04"],
        [123456789, "CHF 1'234'567.89"],
    ])('formats %i cents as %s', (cents, expected) => {
        expect(formatChf(cents)).toBe(expected)
    })
})

describe('the odoo price calculator component', () => {
    it('starts with the configured defaults', () => {
        const component = mount()

        expect(component.full).toBe(10)
        expect(component.light).toBe(0)
        expect(component.totalYear).toBe("CHF 6'204.00")
        expect(component.totalMonth).toBe('CHF 517.00')
        expect(component.peopleLabel).toBe('10 Benutzer')
        expect(component.tierLabel).toBe('bis 25 Benutzer')
        expect(component.storageTitle).toBe('Speicherplatz 50 GB')
    })

    it('switches every price with the billing period', () => {
        const component = mount()

        component.setMonthly()

        expect(component.isMonthly).toBe(true)
        expect(component.fullUnit).toBe('CHF 56.90')
        expect(component.stagingMonth).toBe('CHF 15.00')
        expect(component.totalYear).toBe("CHF 7'848.00")
        expect(component.billingNote).toBe('monatlich')

        component.setYearly()

        expect(component.isYearly).toBe(true)
        expect(component.totalYear).toBe("CHF 6'204.00")
        expect(component.billingNote).toBe('jährlich')
    })

    it('steps up and down and stops at the limits', () => {
        const component = mount()

        component.step(stepButton('full', 1))
        expect(component.full).toBe(11)

        component.full = 1
        component.step(stepButton('full', -1))
        expect(component.full).toBe(1)

        component.step(stepButton('light', -1))
        expect(component.light).toBe(0)
        expect(component.full).toBe(1)

        component.full = 150
        component.step(stepButton('full', 1))
        expect(component.full).toBe(150)
    })

    it('lets the control being moved win and takes the room from the other', () => {
        const component = mount()

        component.full = 100
        component.light = 50
        component.settle('light')
        expect(component.full).toBe(100)
        expect(component.light).toBe(50)

        component.full = 140
        component.settle('full')
        expect(component.light).toBe(10)
        expect(component.peopleLabel).toBe('150 Benutzer')

        component.light = 120
        component.settle('light')
        expect(component.light).toBe(120)
        expect(component.full).toBe(30)
    })

    it('reaches 150 on either slider instead of jumping back', () => {
        const component = mount()

        component.light = 150
        component.settle('light')
        expect(component.light).toBe(150)
        expect(component.full).toBe(0)
        expect(component.peopleLabel).toBe('150 Benutzer')
        expect(component.result.light).toBe(150)
        expect(component.result.full).toBe(0)

        component.step(stepButton('light', 1))
        expect(component.light).toBe(150)

        component.step(stepButton('full', 1))
        expect(component.full).toBe(1)
        expect(component.light).toBe(149)

        component.full = 150
        component.settle('full')
        expect(component.full).toBe(150)
        expect(component.light).toBe(0)
    })

    it('never drops below one user in total', () => {
        const component = mount()

        component.full = 0
        component.settle('full')
        expect(component.full).toBe(1)
        expect(component.light).toBe(0)

        component.light = 5
        component.settle('light')
        component.full = 0
        component.settle('full')
        expect(component.full).toBe(0)
        expect(component.light).toBe(5)

        component.light = 0
        component.settle('light')
        expect(component.light).toBe(1)
        expect(component.full).toBe(0)
    })

    it('reads which control changed from the event', () => {
        const component = mount()
        const input = document.createElement('input')
        input.dataset.kind = 'light'

        component.full = 100
        component.light = 80
        component.normalize({ target: input })

        expect(component.light).toBe(80)
        expect(component.full).toBe(70)
    })

    it('recovers from an emptied number field', () => {
        const component = mount()

        component.full = ''
        expect(component.totalYear).toBe(formatChf(calculate(fixture.config, 1, 0).totalMonth * 12))

        component.normalize()
        expect(component.full).toBe(1)
    })

    it('hides the light row at zero without removing it', () => {
        const component = mount()

        expect(component.$refs.lightRow.classList.contains('invisible')).toBe(true)

        component.light = 5
        component.paint()
        expect(component.$refs.lightRow.classList.contains('invisible')).toBe(false)

        component.light = 0
        component.paint()
        expect(component.$refs.lightRow.classList.contains('invisible')).toBe(true)
    })

    it('paints the slider fill and the cost split', () => {
        const component = mount()

        component.full = 150
        component.paint()

        expect(component.$refs.fullRange.style.getPropertyValue('--fill')).toBe('100%')
        expect(component.$refs.lightRange.style.getPropertyValue('--fill')).toBe('0%')

        const license = Number.parseInt(component.$refs.licenseBar.style.width, 10)
        const hosting = Number.parseInt(component.$refs.hostingBar.style.width, 10)

        expect(license + hosting).toBe(100)
        expect(component.licenseShare).toBe(`${license} %`)
    })

    it('says so when the storage limit is reached', () => {
        const component = mount()

        expect(component.storageHint).toBe('5 GB pro Benutzer, also 50 GB × CHF 0.16')

        component.full = 120
        expect(component.storageTitle).toBe('Speicherplatz 512 GB')
        expect(component.storageHint).toBe('Höchstens 512 GB.')
    })

    it('uses the singular for a single user', () => {
        const component = mount()

        component.full = 1

        expect(component.peopleLabel).toBe('1 Benutzer')
    })

    it('opens the faq entry named in the address on load', () => {
        const details = document.createElement('details')
        details.id = 'faq-from-hash'
        document.body.append(details)
        window.location.hash = '#faq-from-hash'

        mount()

        expect(details.open).toBe(true)

        window.location.hash = ''
        expect(() => mount()).not.toThrow()
    })

    it('opens the faq entry an info icon points to', () => {
        const component = mount()
        const details = document.createElement('details')
        details.id = 'faq-light'
        document.body.append(details)

        const link = document.createElement('a')
        link.dataset.faq = 'faq-light'
        component.openFaq({ currentTarget: link })

        expect(details.open).toBe(true)

        link.dataset.faq = 'missing'
        expect(() => component.openFaq({ currentTarget: link })).not.toThrow()
    })
})

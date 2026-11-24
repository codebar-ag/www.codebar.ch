import { describe, expect, it } from 'vitest'
import { clamp, lerp, matrix, nearestSnap, opennessTarget, smooth, snapPoints, springStep } from '../../resources/js/infrastructure/motion.js'

describe('clamp, lerp and smooth', () => {
    it('clamps to 0..1 by default and to a given range', () => {
        expect(clamp(-2)).toBe(0)
        expect(clamp(.4)).toBe(.4)
        expect(clamp(9)).toBe(1)
        expect(clamp(5, 10, 20)).toBe(10)
        expect(clamp(25, 10, 20)).toBe(20)
    })

    it('interpolates linearly, also outside 0..1', () => {
        expect(lerp(10, 20, 0)).toBe(10)
        expect(lerp(10, 20, .5)).toBe(15)
        expect(lerp(10, 20, 1)).toBe(20)
        expect(lerp(10, 20, 1.5)).toBe(25)
        expect(lerp(10, 20, -.5)).toBe(5)
    })

    it('eases between two edges and holds outside them', () => {
        expect(smooth(0, 1, -1)).toBe(0)
        expect(smooth(0, 1, 0)).toBe(0)
        expect(smooth(0, 1, .5)).toBe(.5)
        expect(smooth(0, 1, 1)).toBe(1)
        expect(smooth(0, 1, 4)).toBe(1)
        expect(smooth(2, 4, 2.5)).toBeCloseTo(.15625)
    })
})

describe('matrix', () => {
    it('is the identity when nothing is tilted', () => {
        const m = matrix(0, 0)

        expect(m.a).toBe(1)
        expect(m.b).toBeCloseTo(0)
        expect(m.c).toBe(0)
        expect(m.d).toBe(1)
    })

    it('flattens the depth axis when looked at from the side', () => {
        const m = matrix(0, Math.PI / 2)

        expect(m.d).toBeCloseTo(0)
        expect(m.a).toBe(1)
    })

    it('turns both axes with the rotation', () => {
        const m = matrix(Math.PI / 2, 0)

        expect(m.a).toBeCloseTo(0)
        expect(m.b).toBeCloseTo(-1)
        expect(m.c).toBeCloseTo(1)
        expect(m.d).toBeCloseTo(0)
    })

    it('never produces NaN for extreme angles', () => {
        for (const m of [matrix(1e6, -1e6), matrix(-Math.PI, Math.PI), matrix(0, -0)]) {
            expect(Object.values(m).every(Number.isFinite)).toBe(true)
        }
    })
})

describe('opennessTarget', () => {
    it('keeps every layer closed at the start', () => {
        for (let k = 0; k < 7; k++) {
            expect(opennessTarget(0, k)).toBe(0)
        }
    })

    it('opens the first layer earlier than the others', () => {
        expect(opennessTarget(.4, 0)).toBe(0)
        expect(opennessTarget(.65, 0)).toBeCloseTo(.5)
        expect(opennessTarget(.9, 0)).toBe(1)
        expect(opennessTarget(1.08, 1)).toBeCloseTo(0)
        expect(opennessTarget(1.44, 1)).toBeCloseTo(.5)
        expect(opennessTarget(1.8, 1)).toBe(1)
    })

    it('has opened layer k completely at its snap point and not yet touched the next', () => {
        for (let k = 0; k < 7; k++) {
            expect(opennessTarget(k + .95, k)).toBe(1)
            expect(opennessTarget(k + .95, k + 1)).toBe(0)
        }
    })

    it('stays within 0..1 for any scroll value', () => {
        for (const sv of [-5, -0.001, 3.3, 99, Infinity]) {
            const o = opennessTarget(sv, 2)

            expect(o).toBeGreaterThanOrEqual(0)
            expect(o).toBeLessThanOrEqual(1)
        }
    })
})

describe('springStep', () => {
    const run = (o, v, t, frames, dt = 1 / 60) => {
        const seen = []
        let s = { o, v, moving: true }

        for (let i = 0; i < frames && s.moving; i++) {
            s = springStep(s.o, s.v, t, dt)
            seen.push(s.o)
        }

        return { ...s, seen }
    }

    it('rests exactly on the target and reports that it stopped', () => {
        expect(springStep(1, 0, 1, 1 / 60)).toEqual({ o: 1, v: 0, moving: false })
        expect(springStep(0, 0, 0, 1 / 60)).toEqual({ o: 0, v: 0, moving: false })
    })

    it('reaches the target within two seconds and stops', () => {
        const s = run(0, 0, 1, 120)

        expect(s.moving).toBe(false)
        expect(s.o).toBe(1)
        expect(s.v).toBe(0)
        expect(s.seen.length).toBeLessThan(120)
    })

    it('overshoots a little when opening and when closing, never by more than 15 percent', () => {
        const up = run(0, 0, 1, 120), down = run(1, 0, 0, 120)

        expect(Math.max(...up.seen)).toBeGreaterThan(1.005)
        expect(Math.max(...up.seen)).toBeLessThan(1.15)
        expect(Math.min(...down.seen)).toBeLessThan(-.005)
        expect(Math.min(...down.seen)).toBeGreaterThan(-.15)
    })

    it('does not move when no time has passed or time runs backwards', () => {
        expect(springStep(.3, 0, 1, 0)).toEqual({ o: .3, v: 0, moving: true })
        expect(springStep(.3, 0, 1, -1)).toEqual({ o: .3, v: 0, moving: true })
    })

    it('stays stable after a long pause between two frames', () => {
        for (const dt of [.6, 5, 1e6]) {
            const s = springStep(0, 0, 1, dt)

            expect(Number.isFinite(s.o)).toBe(true)
            expect(Math.abs(s.o - 1)).toBeLessThan(.2)
        }
    })

    it('gives the same result at 30, 60 and 120 frames per second', () => {
        const at = fps => { let s = { o: 0, v: 0 }; for (let i = 0; i < fps * .2; i++) s = springStep(s.o, s.v, 1, 1 / fps); return s.o }

        expect(at(30)).toBeCloseTo(at(60), 2)
        expect(at(120)).toBeCloseTo(at(60), 2)
    })

    it('follows a target that changes on the way', () => {
        let s = springStep(0, 0, 1, .1)
        s = run(s.o, s.v, 0, 240)

        expect(s.moving).toBe(false)
        expect(s.o).toBe(0)
    })

    it('can be made stiffer or softer', () => {
        expect(springStep(0, 0, 1, .05, 300, 20).o).toBeGreaterThan(springStep(0, 0, 1, .05, 50, 20).o)
    })
})

describe('snapPoints and nearestSnap', () => {
    it('has one point per layer and one for the overview', () => {
        expect(snapPoints(3)).toEqual([.95, 1.95, 2.95, 4])
        expect(snapPoints(1)).toEqual([.95, 2])
        expect(snapPoints(0)).toEqual([1])
    })

    it('snaps back to the closed start for a small scroll', () => {
        expect(nearestSnap(0, 6)).toBe(0)
        expect(nearestSnap(.3, 6)).toBe(0)
        expect(nearestSnap(-2, 6)).toBe(0)
    })

    it('snaps to the nearest layer in both directions', () => {
        expect(nearestSnap(.6, 6)).toBe(.95)
        expect(nearestSnap(1.2, 6)).toBe(.95)
        expect(nearestSnap(1.5, 6)).toBe(1.95)
        expect(nearestSnap(4.55, 6)).toBe(4.95)
    })

    it('snaps to the overview at the end and beyond', () => {
        expect(nearestSnap(6.6, 6)).toBe(7)
        expect(nearestSnap(7, 6)).toBe(7)
        expect(nearestSnap(50, 6)).toBe(7)
    })

    it('keeps the earlier point on an exact tie', () => {
        expect(nearestSnap(.475, 6)).toBe(0)
        expect(nearestSnap(1.45, 6)).toBe(.95)
    })

    it('works without layers', () => {
        expect(nearestSnap(.2, 0)).toBe(0)
        expect(nearestSnap(.8, 0)).toBe(1)
    })
})

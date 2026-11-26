import { describe, expect, it } from 'vitest'
import { roundedPath } from '../../resources/js/infrastructure/geometry.js'

describe('roundedPath', () => {
    it('returns an empty path without points', () => {
        expect(roundedPath([])).toBe('')
    })

    it('keeps a single point as a move', () => {
        expect(roundedPath([[1, 2]])).toBe('M1.0 2.0')
    })

    it('draws a straight line between two points', () => {
        expect(roundedPath([[0, 0], [10, 0]])).toBe('M0.0 0.0L10.0 0.0')
    })

    it('rounds a corner with the given radius', () => {
        expect(roundedPath([[0, 0], [100, 0], [100, 100]], 10)).toBe('M0.0 0.0L90.0 0.0Q100.0 0.0 100.0 10.0L100.0 100.0')
    })

    it('never rounds further than half a segment', () => {
        expect(roundedPath([[0, 0], [10, 0], [10, 100]], 18)).toBe('M0.0 0.0L5.0 0.0Q10.0 0.0 10.0 5.0L10.0 100.0')
    })

    it('drops points that repeat the previous one', () => {
        expect(roundedPath([[0, 0], [0, 0.5], [10, 0]])).toBe('M0.0 0.0L10.0 0.0')
    })
})

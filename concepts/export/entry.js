import Alpine from '@alpinejs/csp'
import focus from '@alpinejs/focus'
import { navigation } from '../../resources/js/navigation'
import { odooPricing } from '../../resources/js/odoo-pricing'
import { languageSuggestion } from '../../resources/js/language-suggestion'
Alpine.plugin(focus)
Alpine.data('navigation', navigation)
Alpine.data('odooPricing', odooPricing)
Alpine.data('languageSuggestion', languageSuggestion)
Alpine.start()

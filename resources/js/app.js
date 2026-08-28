import '../css/app.css'

import Alpine from '@alpinejs/csp'
import focus from '@alpinejs/focus'

import { applicationForm } from './application-form'
import { codeBlock } from './code-block'
import { combobox } from './combobox'
import { introTabs } from './intro-tabs'
import { languageSuggestion } from './language-suggestion'
import { markdownEditor } from './markdown-editor'
import { navigation } from './navigation'
import { readingProgress } from './reading-progress'
import { tableOfContents } from './table-of-contents'
import { toast } from './toast'
import { videoEmbed } from './video-embed'

window.Alpine = Alpine
Alpine.plugin(focus)

Alpine.data('autoSubmit', () => ({
    submit() {
        this.$root.submit()
    },
}))

Alpine.data('combobox', combobox)

Alpine.data('applicationForm', applicationForm)

Alpine.data('introTabs', introTabs)

Alpine.data('markdownEditor', markdownEditor)

Alpine.data('toast', toast)

Alpine.data('languageSuggestion', languageSuggestion)

Alpine.data('navigation', navigation)

Alpine.data('readingProgress', readingProgress)

Alpine.data('tableOfContents', tableOfContents)

Alpine.data('videoEmbed', videoEmbed)

Alpine.data('codeBlock', codeBlock)

Alpine.start()

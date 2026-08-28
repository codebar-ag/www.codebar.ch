@php
    $locked = $application->isSubmitted();

    $sections = [
        'who' => ['first_name', 'last_name', 'age', 'city'],
        'interests' => ['interests', 'focus_fit', 'built_so_far'],
        'about' => ['about'],
        'links' => ['github', 'linkedin', 'project_link'],
        'documents' => ['documents', 'documents.*'],
    ];

    $filled = fn (array $fields): int => collect($fields)->filter(fn ($field) => filled(old($field, $application->{$field})))->count();
    $hasErrors = fn (array $fields): bool => collect($fields)->contains(fn ($field) => $errors->has($field));
    $status = fn (array $fields): string => $filled($fields).' / '.count($fields);
@endphp

<x-app-layout :page="$page">
    <x-layout.page-header
            :title="__('My application')"
            :intro="$locked ? __('Application locked header') : __('Application page header')"
            :breadcrumbs="[
                ['label' => __('Jobs'), 'url' => localized_route('jobs.index')],
                ['label' => __('Internship title'), 'url' => localized_route('jobs.internship.show')],
                ['label' => __('My application')],
            ]"/>

    <x-ui.toast :message="session('status')"/>

    <x-layout.section>
        @if($locked)
            <x-ui.alert variant="success">
                {{ __('Application submitted hint', ['date' => $application->submitted_at?->format('d.m.Y H:i')]) }}
            </x-ui.alert>

            <p class="mt-6 text-sm text-muted">{{ __('Application email label') }}: {{ $application->email }}</p>

            <div class="mt-4 space-y-4">
                <x-ui.collapsible :title="__('Application block who heading')">
                    <dl class="grid gap-4 sm:grid-cols-2">
                        @foreach(['first_name' => __('First name'), 'last_name' => __('Last name'), 'age' => __('Age'), 'city' => __('City')] as $field => $label)
                            <div>
                                <dt class="text-sm font-medium text-gray-800">{{ $label }}</dt>
                                <dd class="text-base text-gray-900">{{ $application->{$field} ?? '–' }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </x-ui.collapsible>

                <x-ui.collapsible :title="__('Application block interests heading')">
                    @foreach(['interests' => __('Application question interests'), 'focus_fit' => __('Application question focus fit'), 'built_so_far' => __('Application question built so far')] as $field => $label)
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $label }}</p>
                            <x-ui.prose class="mt-1 md:prose-base">{!! $application->markdownHtml($application->{$field}) ?? '<p>–</p>' !!}</x-ui.prose>
                        </div>
                    @endforeach
                </x-ui.collapsible>

                <x-ui.collapsible :title="__('Application block about heading')">
                    <p class="text-sm font-medium text-gray-800">{{ __('Application question about') }}</p>
                    <x-ui.prose class="mt-1 md:prose-base">{!! $application->markdownHtml($application->about) ?? '<p>–</p>' !!}</x-ui.prose>
                </x-ui.collapsible>

                <x-ui.collapsible :title="__('Application block links heading')">
                    <dl class="space-y-3">
                        @foreach(['github' => 'GitHub / GitLab', 'linkedin' => 'LinkedIn', 'project_link' => __('Application project link')] as $field => $label)
                            <div>
                                <dt class="text-sm font-medium text-gray-800">{{ $label }}</dt>
                                <dd class="text-base text-gray-900">
                                    @if(filled($application->{$field}))
                                        <x-ui.link :href="$application->{$field}" target="_blank">{{ $application->{$field} }}</x-ui.link>
                                    @else
                                        –
                                    @endif
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </x-ui.collapsible>

                <x-ui.collapsible :title="__('Application documents')">
                    @if($files->isEmpty())
                        <p class="text-base text-muted">–</p>
                    @else
                        <ul class="space-y-2">
                            @foreach($files as $file)
                                <li class="rounded-panel border border-border bg-white px-4 py-2 text-sm text-gray-800">
                                    {{ $file->original_name }} <span class="text-muted">({{ $file->humanSize() }})</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </x-ui.collapsible>
            </div>
        @else
            <div class="mt-6">
                <x-h3 :title="__('Internship person heading')"/>
                <x-ui.prose>
                    <p>{{ __('Internship person body') }}</p>
                </x-ui.prose>
            </div>

            <form method="POST" action="{{ request()->fullUrl() }}" enctype="multipart/form-data" class="mt-6"
                  x-data="applicationForm" data-autosave-url="{{ request()->fullUrl() }}">
                @csrf
                @method('PUT')
                <x-honeypot/>

                <div class="flex items-center justify-between gap-4">
                    <p class="text-sm text-muted">{{ __('Application email label') }}: {{ $application->email }}</p>
                    <p class="text-sm text-muted" aria-live="polite">
                        <span x-show="hasSaved" x-cloak>{{ __('Saved automatically at') }} <span x-text="savedAt"></span></span>
                        <span x-show="hasFailed" x-cloak class="text-red-600">{{ __('Autosave failed hint') }}</span>
                    </p>
                </div>

                <div class="mt-4 space-y-4">
                    <x-ui.collapsible :title="__('Application block who heading')"
                                      :open="$hasErrors($sections['who'])"
                                      :status="$status($sections['who'])"
                                      :complete="$filled($sections['who']) === 4">
                        <div class="grid gap-6 sm:grid-cols-2">
                            <x-form.field name="first_name" :label="__('First name')" required>
                                <x-form.input name="first_name" :value="$application->first_name"/>
                            </x-form.field>

                            <x-form.field name="last_name" :label="__('Last name')" required>
                                <x-form.input name="last_name" :value="$application->last_name"/>
                            </x-form.field>

                            <x-form.field name="age" :label="__('Age')" required>
                                <x-form.input name="age" type="number" min="10" max="99" :value="$application->age"/>
                            </x-form.field>

                            <x-form.field name="city" :label="__('City')" required>
                                <x-form.input name="city" :value="$application->city"/>
                            </x-form.field>
                        </div>
                    </x-ui.collapsible>

                    <x-ui.collapsible :title="__('Application block interests heading')"
                                      :open="$hasErrors($sections['interests'])"
                                      :status="$status($sections['interests'])"
                                      :complete="$filled(['interests', 'focus_fit']) === 2">
                        <p class="text-base text-muted">{{ __('Application block interests intro') }}</p>

                        <x-form.field name="interests" :label="__('Application question interests')" required>
                            <x-form.markdown name="interests" :value="$application->interests"/>
                        </x-form.field>

                        <x-form.field name="focus_fit" :label="__('Application question focus fit')" required>
                            <x-form.markdown name="focus_fit" :value="$application->focus_fit"/>
                        </x-form.field>

                        <x-form.field name="built_so_far" :label="__('Application question built so far')">
                            <x-form.markdown name="built_so_far" :value="$application->built_so_far"/>
                        </x-form.field>
                    </x-ui.collapsible>

                    <x-ui.collapsible :title="__('Application block about heading')"
                                      :open="$hasErrors($sections['about'])"
                                      :status="$status($sections['about'])"
                                      :complete="$filled($sections['about']) === 1">
                        <x-form.field name="about" :label="__('Application question about')" required>
                            <x-form.markdown name="about" :value="$application->about"/>
                        </x-form.field>
                    </x-ui.collapsible>

                    <x-ui.collapsible :title="__('Application block links heading')"
                                      :open="$hasErrors($sections['links'])"
                                      :status="$status($sections['links'])"
                                      :complete="$filled($sections['links']) > 0">
                        <p class="text-base text-muted">{{ __('Application block documents intro') }}</p>

                        <x-form.field name="github" label="GitHub / GitLab">
                            <x-form.input name="github" type="url" :value="$application->github"
                                          placeholder="https://github.com/..."/>
                        </x-form.field>

                        <x-form.field name="linkedin" label="LinkedIn">
                            <x-form.input name="linkedin" type="url" :value="$application->linkedin"
                                          placeholder="https://www.linkedin.com/in/..."/>
                        </x-form.field>

                        <x-form.field name="project_link" :label="__('Application project link')"
                                      :help="__('Application project link help')">
                            <x-form.input name="project_link" type="url" :value="$application->project_link"
                                          placeholder="https://..."/>
                        </x-form.field>
                    </x-ui.collapsible>

                    <x-ui.collapsible :title="__('Application documents')"
                                      :open="$hasErrors($sections['documents'])"
                                      :status="(string) $files->count()"
                                      :complete="$files->isNotEmpty()">
                        <x-form.field name="documents" :label="__('Application documents upload label')"
                                      :help="__('Application documents help')">
                            <x-form.file name="documents[]" accept="application/pdf" multiple id="documents"/>
                            @error('documents.*')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </x-form.field>

                        @if($files->isNotEmpty())
                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-800">{{ __('Uploaded') }}</p>
                                <ul class="space-y-2">
                                    @foreach($files as $file)
                                        <li class="flex items-center justify-between gap-4 rounded-panel border border-border bg-white px-4 py-2">
                                            <span class="min-w-0 truncate text-sm text-gray-800">{{ $file->original_name }}
                                                <span class="text-muted">({{ $file->humanSize() }})</span>
                                            </span>
                                            <x-ui.button type="submit" variant="ghost" size="sm"
                                                         form="delete-file-{{ $file->id }}"
                                                         :label="__('Remove')"/>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </x-ui.collapsible>
                </div>

                <p class="mt-6 text-sm text-muted">{{ __('Application submit once hint') }}</p>

                <x-form.actions>
                    <x-ui.button type="submit" name="action" value="save" variant="outline" :label="__('Save application')"/>
                    <x-ui.button type="submit" name="action" value="submit" :label="__('Submit application')"/>
                </x-form.actions>
            </form>

            @foreach($files as $file)
                <form id="delete-file-{{ $file->id }}" method="POST"
                      action="{{ URL::temporarySignedRoute(Str::slug(app()->getLocale()).'.jobs.internship.application.files.destroy', now()->addDays(7), ['application' => $application, 'applicationFile' => $file]) }}">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @endif
    </x-layout.section>
</x-app-layout>

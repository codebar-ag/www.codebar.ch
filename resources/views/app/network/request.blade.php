<x-app-layout :page="$page">
    <x-layout.page-header
            :title="__('Update network profile')"
            :intro="__('Enter the email address stored in your network profile. If it is registered, we will send you a personal link — valid for one hour — to update your profile.')"
            :breadcrumbs="[
                ['label' => __('Network'), 'url' => localized_route('network.index')],
                ['label' => __('Update network profile')],
            ]"/>

    <x-layout.section>
        @if(session('status'))
            <x-ui.alert class="max-w-2xl">{{ session('status') }}</x-ui.alert>
        @endif

        <form method="POST" action="{{ localized_route('network.request.store') }}" class="mt-section max-w-md">
            @csrf

            <x-form.field name="email" :label="__('Email')">
                <x-form.input name="email" type="email" required autofocus/>
            </x-form.field>

            <x-form.actions>
                <x-ui.button :label="__('Request link')"/>
            </x-form.actions>
        </form>
    </x-layout.section>
</x-app-layout>

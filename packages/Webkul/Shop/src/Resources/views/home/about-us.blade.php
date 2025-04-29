@push('meta')
    <meta name="title" content="@lang('shop::app.home.about.page-title')" />
    <meta name="description" content="@lang('shop::app.home.about.meta-description')" />
    <meta name="keywords" content="@lang('shop::app.home.about.meta-keywords')" />
@endPush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.home.about.page-title')
    </x-slot>

    <div class="container px-[60px] py-8 max-lg:px-8 max-sm:px-4">
        <h1 class="font-dmserif text-4xl max-md:text-3xl max-sm:text-xl">
            @lang('shop::app.home.about.page-title')
        </h1>

        <div class="mt-6 grid gap-6 md:grid-cols-2">
            <div>
                <h2 class="text-2xl font-medium mb-4">
                    @lang('shop::app.home.about.our-story')
                </h2>
                <p class="text-zinc-600">
                    @lang('shop::app.home.about.our-story-content')
                </p>
            </div>

            <div>
                <h2 class="text-2xl font-medium mb-4">
                    @lang('shop::app.home.about.our-mission')
                </h2>
                <p class="text-zinc-600">
                    @lang('shop::app.home.about.our-mission-content')
                </p>
            </div>
        </div>

        <div class="mt-8">
            <h2 class="text-2xl font-medium mb-4">
                @lang('shop::app.home.about.our-team')
            </h2>
            <p class="text-zinc-600 mb-6">
                @lang('shop::app.home.about.our-team-content')
            </p>
        </div>
    </div>
</x-shop::layouts>

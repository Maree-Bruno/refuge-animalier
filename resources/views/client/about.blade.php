<x-layouts.client>
    <div class="bg-white shadow-[inset_0_-4px_30px_rgba(0,0,0,0.1)] py-4 flex justify-center">
        <section class="p-5 flex flex-col gap-6 leading-9 sm:flex-row lg:px-10 lg:justify-between xl:max-w-10/12 xl:gap-20">
            <div class="aspect-square max-h-80 place-self-center sm:max-h-none">
                <img
                    src="{{URL('images/billy.webp')}}"
                    alt="{{ __('about.hero.title') }}"
                    class="h-full aspect-square rounded-tl-[20px] rounded-tr-[20px] rounded-bl-[500px] rounded-br-[500px] object-cover object-top object-fit-fill xl:max-h-[500px]">
            </div>
            <div class="flex flex-col space-y-5 lg:py-10 lg:w-3/4 xl:max-w-2/5">
                <h2 class="title">
                    {{ __('about.hero.title') }}
                </h2>
                <p class="subsubtitle leading-8">
                    {{ __('about.hero.subtitle') }}
                </p>
                <p class="text-lg lg:text leading-8">
                    {{ __('about.hero.description1') }}
                </p>
                <p class="text-lg lg:text leading-8">
                    {{ __('about.hero.description2') }}
                </p>
            </div>
        </section>
    </div>

    <x-layouts.section :title="__('about.values.title')">
        <div class="grid sm:grid-cols-2 gap-5 justify-items-center about-card-display">
            <x-about.value-card
                :title="__('about.values.kindness.title')"
                :text="__('about.values.kindness.text')"
                icon="linkedin"
                iconclass="lg:scale-200"
                bg="bg-honeyyellow"/>

            <x-about.value-card
                :title="__('about.values.transparency.title')"
                :text="__('about.values.transparency.text')"
                icon="linkedin"
                iconclass="lg:scale-200"
                bg="bg-greenmint"/>

            <x-about.value-card
                :title="__('about.values.commitment.title')"
                :text="__('about.values.commitment.text')"
                icon="linkedin"
                iconclass="lg:scale-200"
                bg="bg-sweetorange"/>

            <x-about.value-card
                :title="__('about.values.community.title')"
                :text="__('about.values.community.text')"
                icon="linkedin"
                iconclass="svg-fillwhite lg:scale-200"
                bg="bg-blueslate"/>
        </div>
    </x-layouts.section>

    <div class="bg-lighthoneyyellow/40 shadow-inner py-10">
        <x-layouts.section :title="__('about.procedure.title')">
            <div class="flex flex-col justify-center items-center space-y-16 lg:space-y-24">
                @foreach(__('about.procedure.steps') as $step)
                    <x-about.procedure
                        :reverse="$loop->iteration % 2 == 0 ? 'true' : ''"
                        src="{{URL('images/billy.webp')}}"
                        :alt="$step['title']"
                        :step="$loop->iteration"
                        :title="$step['title']"
                        :text="$step['text']"
                        :link="$step['link_label'] ?? ''"
                        icon="arrow_right"
                        button_class="button-orange flex-row-reverse"
                        :label="$step['link_label'] ?? ''"
                    />
                @endforeach
            </div>
        </x-layouts.section>
    </div>
</x-layouts.client>

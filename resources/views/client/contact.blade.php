<x-layouts.client>
    <x-layouts.section :title="__('contact.title')" class="py-10">
        <div class="flex flex-col md:flex-row md:justify-between gap-20">
            <div class="w-fit space-y-5 md:max-w-1/3">

                <div class="container space-y-10 md:max-h-[550px] md:overflow-scroll">
                    <div class="space-y-5">
                        <p>{{ __('contact.text_1') }}</p>
                        <p>{{ __('contact.text_2') }}</p>
                        <p>{{ __('contact.text_3') }}</p>
                        <p>{{ __('contact.text_4') }}</p>
                    </div>
                </div>

                <div class="bg-honeyyellow p-5 rounded-xl flex gap-4 items-start">
                    <x-svg.warning class="w-8 h-8 shrink-0"/>
                    <p class="font-semibold">{{ __('contact.alert') }}</p>
                </div>
            </div>

            <div class="w-full">
                <form action="" method="post" class="flex flex-col space-y-2.5">
                    @csrf

                    <x-form.input_label
                        id="name"
                        type="text"
                        name="name"
                        :label="__('contact.name')"
                        :placeholder="__('contact.placeholder_name')"
                        required
                    />

                    <x-form.input_label
                        id="firstname"
                        type="text"
                        name="firstname"
                        :label="__('contact.firstname')"
                        :placeholder="__('contact.placeholder_firstname')"
                        required
                    />

                    <x-form.input_label
                        id="email"
                        type="email"
                        name="email"
                        :label="__('contact.email')"
                        :placeholder="__('contact.placeholder_email')"
                        required
                    />

                    <x-form.input_label
                        id="tel"
                        type="tel"
                        name="tel"
                        :label="__('contact.phone')"
                        :placeholder="__('contact.placeholder_phone')"
                        required
                    />

                    <x-form.textarea_label
                        id="message"
                        name="message"
                        :label="__('contact.message')"
                        :placeholder="__('contact.placeholder_message')"
                        required
                    />

                    <x-buttons.submit_button
                        icon="send"
                        class="flex-row-reverse button-yellow subsubtitle place-self-center"
                        svgclass="svg-strokeblack"
                    >
                        {{ __('contact.submit') }}
                    </x-buttons.submit_button>

                </form>
            </div>
        </div>
    </x-layouts.section>
</x-layouts.client>

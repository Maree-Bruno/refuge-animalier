<x-layouts.client>
    <x-layouts.section :title="__('volunteer.title')" class="py-10">
        <div class="flex flex-col md:flex-row md:justify-between gap-20">
            <div class="md:max-w-1/3 container space-y-10 md:max-h-[550px] md:overflow-scroll">
                <x-volunteer.side_information
                    :title="__('volunteer.why_title')"
                    :text="__('volunteer.why_text')"
                />

                <x-volunteer.side_information
                    :title="__('volunteer.what_title')"
                    :text="__('volunteer.what_text')"
                />

                <x-volunteer.side_information
                    :title="__('volunteer.no_exp_title')"
                    :text="__('volunteer.no_exp_text')"
                />

                <x-volunteer.side_information
                    :title="__('volunteer.commitment_title')"
                    :text="__('volunteer.commitment_text')"
                />

            </div>

            <div class="w-full">
                <form action="" method="post" class="flex flex-col space-y-2.5">
                    @csrf
                    <fieldset class="flex flex-col sm:flex-row sm:gap-5">
                        <legend class="sr-only">{{ __('labels.identity') }}</legend>
                        <x-form.input_label
                            id="name"
                            type="text"
                            name="name"
                            :label="__('labels.name')"
                            :placeholder="__('placeholder.name')"
                            required
                            :value="old('name')"
                        />

                        <x-form.input_label
                            id="firstname"
                            type="text"
                            name="firstname"
                            :label="__('labels.firstname')"
                            :placeholder="__('placeholder.firstname')"
                            required
                            :value="old('firstname')"
                        />
                    </fieldset>
                    <fieldset class="flex flex-col sm:flex-row sm:gap-5 ">
                        <legend class="sr-only">{{ __('labels.contact') }}</legend>
                        <x-form.input_label
                            id="email"
                            type="email"
                            name="email"
                            :label="__('labels.email')"
                            :placeholder="__('placeholder.email')"
                            required
                            :value="old('email')"
                        />
                        <x-form.input_label
                            id="tel"
                            type="tel"
                            name="tel"
                            :label="__('labels.tel')"
                            :placeholder="__('placeholder.tel')"
                            required
                            :value="old('tel')"
                        />
                    </fieldset>

                    <fieldset class="">
                        <legend class="sr-only">{{ __('labels.address') }}</legend>
                        <div class="flex flex-col justify-center items-center sm:flex-row sm:gap-5">
                            <x-form.input_label
                                id="street"
                                type="text"
                                name="street"
                                :label="__('labels.street')"
                                :placeholder="__('placeholder.street')"
                                required
                                :value="old('street')"
                            />
                            <x-form.input_label
                                id="number"
                                type="text"
                                name="number"
                                :label="__('labels.number')"
                                :placeholder="__('placeholder.number')"
                                :value="old('number')"
                                required
                                class="sm:max-w-2/5"
                            />
                        </div>
                        <div class="flex flex-col justify-center items-center sm:flex-row sm:gap-5">
                            <x-form.input_label
                                id="cp"
                                type="text"
                                name="cp"
                                :label="__('labels.cp')"
                                :placeholder="__('placeholder.cp')"
                                required
                                :value="old('cp')"
                                class="sm:max-w-fit"
                            />
                            <x-form.input_label
                                id="city"
                                type="text"
                                name="city"
                                :label="__('labels.city')"
                                :placeholder="__('placeholder.city')"
                                required
                                :value="old('city')"
                            />
                        </div>
                    </fieldset>
                    <fieldset class="">
                        <legend class="sr-only">{{ __('labels.message') }}</legend>
                        <x-form.textarea_label
                            id="message"
                            name="message"
                            :label="__('labels.message')"
                            :placeholder="__('placeholder.message')"
                            required
                            :value="old('message')"
                        />
                    </fieldset>
                    <x-buttons.submit_button icon="send" class="flex-row-reverse button-yellow subsubtitle
                    place-self-center"
                                             svgclass="svg-strokeblack">
                        {{ __('volunteer.submit') }}
                    </x-buttons.submit_button>
                </form>
            </div>
        </div>
    </x-layouts.section>
</x-layouts.client>

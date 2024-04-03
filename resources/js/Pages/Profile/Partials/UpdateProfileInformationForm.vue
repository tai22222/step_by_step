<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="c-header__title">{{ $t('Profile Information') }}</h2>

            <p class="c-header__description">
                {{ $t("Update your account's profile information and email address.") }}
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="c-form">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="c-text-input__full-width"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="u-margin__top-s" :message="form.errors.name" />
            </div>

            <div class="u-margin__top-lg">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="c-text-input__full-width"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="u-margin__top-s" :message="form.errors.email" />
            </div>

            <div v-if="props.mustVerifyEmail && user.email_verified_at === null">
                <p class="p-link__text">
                    {{ $t('Your email address is unverified.') }}
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="p-link__verify-email"
                    >
                        {{ $t('Your email address is unverified.') }}
                    </Link>
                </p>

                <div
                    v-show="props.status === 'verification-link-sent'"
                    class="c-confirm-message"
                >
                    {{ $t('Your email address is unverified.') }}
                </div>
            </div>

            <div class="p-form__btn-save u-margin__top-lg">
                <PrimaryButton :disabled="form.processing">{{ $t('Save') }}</PrimaryButton>

                        <Transition 
                            enter-from-class="u-opacity-0" 
                            leave-to-class="u-opacity-0" 
                            class="p-transition__btn">
                    <p v-if="form.recentlySuccessful" class="p-form__btn-saved">{{ $t('Saved.') }}</p>
                </Transition>
            </div>
        </form>
    </section>
</template>

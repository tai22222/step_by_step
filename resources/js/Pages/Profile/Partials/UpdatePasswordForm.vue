<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="c-header__title">{{ $t('Update Password') }}</h2>

            <p class="c-header__description">
                {{ $t('Ensure your account is using a long, random password to stay secure.') }}
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="u-margin__top-2xl u-margin__bottom-2xl">
            <div>
                <InputLabel for="current_password" value="Current Password" />

                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="c-text-input__full-width"
                    autocomplete="current-password"
                />

                <InputError :message="form.errors.current_password" class="u-margin__top-s" />
            </div>

            <div class="u-margin__top-lg">
                <InputLabel for="password" value="New Password" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="c-text-input__full-width"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password" class="u-margin__top-s" />
            </div>

            <div class="u-margin__top-lg">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="c-text-input__full-width"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password_confirmation" class="u-margin__top-s" />
            </div>

            <div class="p-form__btn-save u-margin__top-lg">
                <PrimaryButton :disabled="form.processing">{{ $t('Save') }}</PrimaryButton>

                <Transition enter-from-class="u-opacity-0" 
                            leave-to-class="u-opacity-0" 
                            class="p-transition__btn">
                    <p v-if="form.recentlySuccessful" class="p-form__btn-saved">{{ $t('Saved.') }}</p>
                </Transition>
            </div>
        </form>
    </section>
</template>

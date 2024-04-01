<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
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

                <InputError class="c-text-input__error" :message="form.errors.name" />
            </div>

            <div class="c-text-input__between">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="c-text-input__full-width"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="c-text-input__error" :message="form.errors.email" />
            </div>

            <div class="c-text-input__between">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="c-text-input__full-width"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="c-text-input__error" :message="form.errors.password" />
            </div>

            <div class="c-text-input__between">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="c-text-input__full-width"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="c-text-input__error" :message="form.errors.password_confirmation" />
            </div>

            <div class="c-btn__position-end c-text-input__between">
                <Link
                    :href="route('login')"
                    class="p-link"
                >
                    Already registered?
                </Link>

                <PrimaryButton class="p-btn__margin-left" 
                              :class="{ 'p-btn__opacity-25': form.processing }" 
                              :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

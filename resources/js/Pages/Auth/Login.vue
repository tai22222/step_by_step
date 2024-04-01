<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="c-confirm-status">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="c-text-input__full-width"
                    v-model="form.email"
                    required
                    autofocus
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
                    autocomplete="current-password"
                />

                <InputError class="c-text-input__error" :message="form.errors.password" />
            </div>

            <div class="c-checkbox__login">
                <label class="c-checkbox__login-label">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="c-checkbox__login-text">Remember me</span>
                </label>
            </div>

            <div class="c-btn__position-end">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="p-link"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton class="p-btn__margin-left"
                               :class="{ 'p-btn__opacity-25': form.processing }" 
                               :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

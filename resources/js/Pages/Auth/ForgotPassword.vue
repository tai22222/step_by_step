<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";

defineProps({
  status: String,
});

const form = useForm({
  email: "",
});

const submit = () => {
  form.post(route("password.email"));
};
</script>

<template>
  <GuestLayout>
    <Head title="Forgot Password" />

    <div class="c-description">
      {{ $t('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <div v-if="status" class="c-confirm-message">
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

        <InputError class="u-margin__top-s" :message="form.errors.email" />
      </div>

      <div class="p-form__btn-position-end u-margin__top-lg">
        <PrimaryButton
          :class="{ 'u-opacity-25': form.processing }"
          :disabled="form.processing"
        >
          {{ $t('Email Password Reset Link') }}
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>

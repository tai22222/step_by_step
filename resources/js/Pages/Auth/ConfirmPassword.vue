<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

import { Head, useForm } from "@inertiajs/vue3";

const form = useForm({
  password: "",
});

const submit = () => {
  form.post(route("password.confirm"), {
    onFinish: () => form.reset(),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Confirm Password" />

    <div class="c-description">
      {{ $t('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="password" value="Password" />
        <TextInput
          id="password"
          type="password"
          class="c-text-input__full-width"
          v-model="form.password"
          required
          autocomplete="current-password"
          autofocus
        />
        <InputError class="u-margin__top-s" :message="form.errors.password" />
      </div>

      <div class="p-form__btn-position-end u-margin__top-lg">
        <PrimaryButton
          class="u-margin__left-m"
          :class="{ 'u-opacity-25': form.processing }"
          :disabled="form.processing"
        >
          {{ $t('Confirm') }}
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>

<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
  email: String,
  token: String,
});

const form = useForm({
  token: props.token,
  email: props.email,
  password: "",
  password_confirmation: "",
});

const submit = () => {
  form.post(route("password.store"), {
    onFinish: () => form.reset("password", "password_confirmation"),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Reset Password" />

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

      <div class="u-margin__top-lg">
        <InputLabel for="password" value="Password" />

        <TextInput
          id="password"
          type="password"
          class="c-text-input__full-width"
          v-model="form.password"
          required
          autocomplete="new-password"
        />

        <InputError class="u-margin__top-s" :message="form.errors.password" />
      </div>

      <div class="u-margin__top-lg">
        <InputLabel for="password_confirmation" value="Confirm Password" />

        <TextInput
          id="password_confirmation"
          type="password"
          class="c-text-input__full-width"
          v-model="form.password_confirmation"
          required
          autocomplete="new-password"
        />

        <InputError
          class="u-margin__top-s"
          :message="form.errors.password_confirmation"
        />
      </div>

      <div class="p-form__btn-position-end u-margin__top-lg">
        <PrimaryButton
          :class="{ 'u-opacity-25': form.processing }"
          :disabled="form.processing"
        >
          {{ $t('Reset Password') }}
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>

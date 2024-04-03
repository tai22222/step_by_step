<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
  terms: false,
});

const submit = () => {
  form.post(route("register"), {
    onFinish: () => form.reset("password", "password_confirmation"),
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

        <InputError class="u-margin__top-s" :message="form.errors.name" />
      </div>

      <div class="u-margin__top-l">
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
        <Link :href="route('login')" class="p-link"> {{ $t('Already registered?') }} </Link>

        <PrimaryButton
          class="u-margin__left-m"
          :class="{ 'u-opacity-25': form.processing }"
          :disabled="form.processing"
        >
          {{ $t('Register') }}
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>

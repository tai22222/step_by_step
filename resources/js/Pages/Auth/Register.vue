<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

// バリデーション
import {
  isValidEmail,
  isValidText,
  isValidPassword,
  doPasswordsMatch,
} from "@/Utils/validators";

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
  terms: false,
});

// フォームの送信
const submit = () => {
  form.post(route("register"), {
    onFinish: () => form.reset("password", "password_confirmation"),
  });
};

// バリデーション
const validText = (max, min) => {
  const { isValid, errorMessage } = isValidText(form.name, max, min);
  if(!isValid){
    form.errors.name = errorMessage;
  } else {
    form.errors.name = "";
  }
}

const validEmail = () => {
  const { isValid, errorMessage } = isValidEmail(form.email);
  if(!isValid) {
    form.errors.email = errorMessage;
  } else {
    form.errors.email = "";
  }
}

const validPassword = ( field ) => {
  const { isValid, errorMessage} = isValidPassword(form[field]);
  if(!isValid) {
    form.errors[field] = errorMessage;
  } else {
    form.errors[field] = "";
  }
}

const confirmPassword = () => {
  const { isValid, errorMessage } = doPasswordsMatch(form.password ,form.password_confirmation);
  if(!isValid){
    form.errors.password_confirmation = errorMessage;
  } else {
    form.errors.password_confirmation = "";
  }
}
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
          @blur="validText(50, 0)"
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
          @blur="validEmail"
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
          @blur="validPassword"
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
          @blur="confirmPassword"
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

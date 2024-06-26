<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

import { Head, Link, useForm } from "@inertiajs/vue3";

// バリデーションの読み込み
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

const hasErrors = () => {
  console.log(form.errors);
  return Object.keys(form.errors).length > 0;
}

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

const validPassword = ( ) => {
  const { isValid, errorMessage} = isValidPassword(form.password);
  if(!isValid) {
    form.errors.password = errorMessage;
  } else {
    form.errors.password = "";
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

    <div class="c-guest-layout__title">新規登録</div>
    <form @submit.prevent="submit">
      <div>
        <InputLabel for="name" :value="$t('Name')" />
        <TextInput
            id="name"
            type="text"
            class="c-text-input__full-width"
            v-model="form.name"
            required
            autofocus
            autocomplete="name"
            @input="validText(50, 0)"
        />
        <InputError class="u-margin__top-s" :message="form.errors.name" />
      </div>

      <div class="u-margin__top-l">
        <InputLabel for="email" :value="$t('Email')" />
        <TextInput
            id="email"
            type="email"
            class="c-text-input__full-width"
            v-model="form.email"
            required
            autocomplete="username"
            @input="validEmail"
        />
        <InputError class="u-margin__top-s" :message="form.errors.email" />
      </div>

      <div class="u-margin__top-lg">
        <InputLabel for="password" :value="$t('Password')" />
        <TextInput
            id="password"
            type="password"
            class="c-text-input__full-width"
            v-model="form.password"
            required
            autocomplete="new-password"
            @input="validPassword"
        />
        <InputError class="u-margin__top-s" :message="form.errors.password" />
      </div>

      <div class="u-margin__top-lg">
        <InputLabel for="password_confirmation" :value="$t('Confirm Password')" />
        <TextInput
            id="password_confirmation"
            type="password"
            class="c-text-input__full-width"
            v-model="form.password_confirmation"
            required
            autocomplete="new-password"
            @input="confirmPassword"
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

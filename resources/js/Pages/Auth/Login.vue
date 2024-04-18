<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

import { Head, Link, useForm } from "@inertiajs/vue3";

// バリデーションの読み込み
import {
  isValidEmail,
  isValidPassword,
} from "@/Utils/validators";

defineProps({
  canResetPassword: Boolean,
  status: String,
});

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post(route("login"), {
    onFinish: () => form.reset("password"),
  });
};

// バリデーション
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
</script>

<template>
  <GuestLayout>
    <Head title="Log in" />

    <div v-if="status" class="c-confirm-message">
      {{ status }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="email" :value="$t('Email')" />
        <TextInput
            id="email"
            type="email"
            class="c-text-input__full-width"
            v-model="form.email"
            required
            autofocus
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
            autocomplete="current-password"
            @input="validPassword"
        />
        <InputError class="u-margin__top-s" :message="form.errors.password" />
      </div>

      <div class="p-login__checkbox">
        <label class="p-login__checkbox-label">
          <Checkbox 
              name="remember" 
              v-model:checked="form.remember" 
          />
          <span class="p-login__checkbox-text">{{ $t('Remember me') }} </span>
        </label>
      </div>

      <div class="p-form__btn-position-end">
        <Link
            v-if="canResetPassword"
            :href="route('password.request')"
            class="p-link"
        >
          {{ $t('Forgot your password?') }}
        </Link>

        <PrimaryButton
            class="u-margin__left-m"
            :class="{ 'u-opacity-25': form.processing }"
            :disabled="form.processing"
        >
          {{ $t('Log in') }}
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>

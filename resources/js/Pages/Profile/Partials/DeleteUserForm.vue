<script setup>
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
  password: "",
});

const confirmUserDeletion = () => {
  confirmingUserDeletion.value = true;

  nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
  form.delete(route("profile.destroy"), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => passwordInput.value.focus(),
    onFinish: () => form.reset(),
  });
};

const closeModal = () => {
  confirmingUserDeletion.value = false;

  form.reset();
};
</script>

<template>
  <!-- todo -->
  <section class="u-margin__top-2xl u-margin__bottom-2xl">
    <header>
      <h2 class="c-header__title">{{ $t('Delete Account') }}</h2>

      <p class="c-header__description">
        {{ $t('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
      </p>
    </header>

    <DangerButton @click="confirmUserDeletion" class="u-margin__top-2xl">{{ $t('Delete Account') }}</DangerButton>

    <!-- クリック後のモーダル表示 -->
    <Modal :show="confirmingUserDeletion" @close="closeModal">
      <div class="u-padding-2xl">
        <h2 class="c-header__title">
          {{ $t('Are you sure you want to delete your account?') }}
        </h2>

        <p class="c-header__description">
          {{ $t('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
        </p>

        <div class="u-margin__top-2xl">
          <InputLabel for="password" value="Password" class="c-modal__position" />

          <TextInput
            id="password"
            ref="passwordInput"
            v-model="form.password"
            type="password"
            class="c-text-input__3-4"
            placeholder="Password"
            @keyup.enter="deleteUser"
          />

          <InputError :message="form.errors.password" class="u-margin__top-s" />
        </div>

        <div class="p-form__btn-position-end u-margin__top-2xl">
          <SecondaryButton @click="closeModal"> {{ $t('Cancel') }} </SecondaryButton>

          <DangerButton
            class="u-margin__left-m"
            :class="{ 'u-opacity-25': form.processing }"
            :disabled="form.processing"
            @click="deleteUser"
          >
            {{ $t('Delete Account') }}
          </DangerButton>
        </div>
      </div>
    </Modal>
  </section>
</template>

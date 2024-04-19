<script setup>
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

import { useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";

const props = defineProps({
  project_id: Number,
});

const confirmingProjectDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
  password: "",
});

const confirmProjectDeletion = () => {
  confirmingProjectDeletion.value = true;
  nextTick(() => passwordInput.value.focus());
};

const deleteProject = () => {
  form.delete(route('project.destroy', { id: props.project_id }), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => passwordInput.value.focus(),
    onFinish: () => form.reset(),
  });
};

const closeModal = () => {
  confirmingProjectDeletion.value = false;
  form.reset();
};
</script>

<template>
  <!-- todo -->
  <section class="u-margin__top-2xl u-margin__bottom-2xl">
    <DangerButton @click="confirmProjectDeletion" class="u-margin__top-2xl">{{
      $t("Delete")
    }}</DangerButton>

    <!-- クリック後のモーダル表示 -->
    <Modal :show="confirmingProjectDeletion" @close="closeModal">
      <div class="u-padding-2xl">
        <h2 class="c-header__title">
          {{ $t("Are you sure it is okay to delete the project you created?") }}
        </h2>

        <p class="c-header__description">
          {{
            $t(
              "Deleting the project will permanently erase all data. To completely delete the project, please enter your password for confirmation."
            )
          }}
        </p>

        <div class="u-margin__top-2xl">
          <InputLabel
            for="password"
            value="Password"
            class="c-modal__position"
          />
          <TextInput
            id="password"
            ref="passwordInput"
            v-model="form.password"
            type="password"
            class="c-text-input__3-4"
            placeholder="Password"
          />
          <InputError :message="form.errors.password" class="u-margin__top-s" />
        </div>

        <div class="p-form__btn-position-end u-margin__top-2xl">
          <SecondaryButton @click="closeModal">
            {{ $t("Cancel") }}
          </SecondaryButton>

          <DangerButton
            class="u-margin__left-m"
            :class="{ 'u-opacity-25': form.processing }"
            :disabled="form.processing"
            @click="deleteProject"
          >
            {{ $t("Delete Project") }}
          </DangerButton>
        </div>
      </div>
    </Modal>
  </section>
</template>

<script setup>
// コンポーネントの読み込み
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
// 各モジュールの読み込み
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia'
import { ref } from 'vue';

// バリデーション
// import {
//   isValidText,
//   isValidMax,
//   isValidImageSize,
//   isValidImageType,
// } from "@/utils/validators";

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

// フォームデータの受け取り
const form = useForm({
    name: user.name,
    email: user.email,
    icon_image: user.icon_image,
    introduction: user.introduction,
});

// ファイル選択ダイアログの表示
const photoInput = ref(null);
const selectPhoto = () => {
  photoInput.value.click();
}

// アイコン画像のプレビュー表示
const photoPreview = ref(null);
const updatePhotoPreview = () => {
  console.log('updatePreviewに入った');
  const photo = photoInput.value.files[0];
  console.log('photo:' + photo);
  if(!photo) return;

  // サイズのバリデーション
  // let validationResult = isValidImageSize(photo);
  // if (!validationResult.isValid) {
  //   form.errors[icon_image] = validationResult.errorMessage;
  //   return;
  // }

  // 形式のバリデーション
  // validationResult = isValidImageType(photo);
  // if (!validationResult.isValid) {
  //   form.errors[icon_image] = validationResult.errorMessage;
  //   return;
  // }

  // form.errors[icon_image] = ""; // エラーをクリア
  
  const reader = new FileReader();
  reader.onload = (e) => {
    // プレビュー表示のためBase64形式の文字列として取得した画像データをリアクティブに変化
    photoPreview.value = e.target.result;
  };
  // templateでimgタグのsrcでbase64形式を出力できるようにする
  reader.readAsDataURL(photo);
}

// 画像削除
const deleteImage = () => {
  console.log('画像削除');
  form.icon_image = null;
  photoPreview.value = null;
}

// アイコン画像をpost、その他情報をputで更新
// フォームデータにファイルデータを入れ込む(送信時に入れ込む)
// form.icon_image = photo;
// form.icon_imageがphotoPreviewと異なる場合post送信
// その他はformの内容と差異がある場合は
const updateProfileInformation = () => {
  if(photoInput.value) {
    form.icon_image = photoInput.value.files[0];
  }
  Inertia.post(route('profile.update'), form);
}
</script>

<template>
    <section>
        <header>
            <h2 class="c-header__title">{{ $t('Profile Information') }}</h2>

            <p class="c-header__description">
              {{ $t("Update your account's profile information and email address.") }}
            </p>
        </header>

        <form @submit.prevent="updateProfileInformation" class="c-form">
            <!-- アイコン画像 -->
            <div>
              <div @click="selectPhoto" style="display: inline-block;">
                <!-- hiddenで隠す -->
                <input type="file" 
                       ref="photoInput"
                       style="display: none;"
                       @change="updatePhotoPreview">

                <!-- プレビュー画像表示部分(アイコン設定時) -->
                <div v-if="photoPreview !== null">
                  <img :src="photoPreview"
                       alt="アイコンのプレビュー画像"
                       class="c-image__preview">
                </div>
                <div v-else>
                  <!-- アイコン画像初期表示(アイコン未設定時) -->
                  <div v-if="form.icon_image === null">
                    <img src="/storage/default/no-image.jpg"
                        :alt="user.name" 
                        class="c-image__preview">
                  </div>
                  <!-- アイコン画像初期表示(アイコン設定時) -->
                  <div v-else>
                    <!-- DBでの登録画像にする -->
                    <img :src="`/storage/${user.icon_image}`"
                        :alt="user.name" 
                        class="c-image__preview">
                  </div>
                </div>

              </div>

              <div>
                <!-- 画像選択ボタン -->
                <SecondaryButton
                        class="u-margin__y-s"
                        type="button"
                        @click.prevent="selectPhoto"
                >
                    画像を選択
                </SecondaryButton>

                <!-- 画像削除 -->
                <SecondaryButton
                      v-if="photoPreview || user.icon_image"
                      type="button"
                      class="c-btn__delete"
                      @click.prevent="deleteImage"
                    >
                      削除
                </SecondaryButton>
                <InputError
                      :message="form.errors.icon_image"
                      class="mt-2"
                    />
              </div>
            </div>
            <!-- ユーザネーム -->
            <div class="u-margin__top-lg">
                <InputLabel for="name" :value="$t('Name')" />
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

            <!-- email -->
            <div class="u-margin__top-lg">
                <InputLabel for="email" :value="$t('Email')" />
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
            <!-- email認証(mustVerifyEmailがtrueで未認証の場合) -->
            <div v-if="props.mustVerifyEmail && user.email_verified_at === null">
                <p class="p-link__text">
                    {{ $t('Your email address is unverified.') }}
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="p-link__verify-email"
                    >
                        {{ $t('Your email address is unverified.') }}
                    </Link>
                </p>
                <div
                    v-show="props.status === 'verification-link-sent'"
                    class="c-confirm-message"
                >
                    {{ $t('Your email address is unverified.') }}
                </div>
            </div>

            <!-- 自己紹介 -->
            <div class="u-margin__top-lg">
                <InputLabel for="introduction" :value="$t('introduction')" />
                <Textarea
                    id="introduction"
                    type="text"
                    class="c-text-input__full-width c-text-input__textarea"
                    v-model="form.introduction"
                    placeholder="例）英語の学習を6年ほど独学でやっています。
多言語にも共通する勉強方法もあると思うのでぜひチャレンジしてみてください"
                />
                <InputError class="u-margin__top-s" :message="form.errors.name" />
            </div>
            <!-- 保存ボタン -->
            <div class="p-form__btn-save u-margin__top-lg">
                <PrimaryButton :disabled="form.processing">{{ $t('Save') }}</PrimaryButton>

                <Transition 
                    enter-from-class="u-opacity-0" 
                    leave-to-class="u-opacity-0" 
                    class="p-transition__btn">
                <p v-if="form.recentlySuccessful" class="p-form__btn-saved">{{ $t('Saved.') }}</p>
                </Transition>
            </div>
        </form>
    </section>
</template>

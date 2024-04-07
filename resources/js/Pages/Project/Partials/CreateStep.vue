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
import { ref, watch, defineProps, defineEmits } from 'vue';

// 親コンポーネントから値の受け取り
const props = defineProps({
  stepData: Object,
  stepIndex: Number,
  lastIndex: Number,
});

// 親コンポーネント(Create.vue)への受け渡し
const emits = defineEmits(['updateStep', 'addStep', 'removeStep']);


// 入力文字のカウント(keyup)
const initialCountLength = props.stepData.content.length; // 初期値
const countInput = ref(initialCountLength);
const textCount = () => {
  countInput.value = props.stepData.content.length;
}

// ステップデータの変更を親コンポーネントに通知
watch(props.stepData, (newVal) => {
  emits('updateStep', { stepIndex: props.stepIndex, stepData: newVal });
}, { deep: true });
</script>

<template>
    <section>
        <header>
            <h2 class="c-header__title">{{ stepIndex + 1 }}.  {{ $t('Step') }}</h2>
            <p class="c-header__description">
              {{ $t("This service is designed to enable individuals to share their optimal learning paths and resources based on their experiences, allowing other learners to use this information as a reference for creating their own study plans.") }}
            </p>
        </header>

        <form class="c-form">
            <!-- Step タイトル -->
            {{ lastIndex }}
            <div class="u-margin__top-lg">
                <InputLabel for="step-title" :value="$t('Step Title')" />
                <TextInput
                    id="step-title"
                    type="text"
                    class="c-text-input__full-width"
                    v-model="stepData.title"
                    required
                />
                <!-- <InputError class="u-margin__top-s" :message="form.errors.step_title" /> -->
            </div>

            <!-- 目安達成時間 -->
            <div class="u-margin__top-lg">
                <InputLabel for="estimated-time" :value="$t('Estimated Time')" />
                <TextInput
                    id="estimated-time"
                    type="text"
                    class="c-text-input__full-width"
                    v-model="stepData.estimated_time"
                    required
                />
                <!-- <InputError class="u-margin__top-s" :message="form.errors.estimated_time" /> -->
            </div>

            <!-- Step 内容 -->
            <div class="u-margin__top-lg">
                <InputLabel for="step-content" :value="$t('Step Content')" />
                <Textarea
                    id="step-content"
                    type="text"
                    class="c-text-input__full-width c-text-input__textarea"
                    v-model="stepData.content"
                    @keyup="textCount"
                    placeholder="例）英語の学習を6年ほど独学でやっています。
多言語にも共通する勉強方法もあると思うのでぜひチャレンジしてみてください"
                />
                <!-- カウントアップと500文字を超えたら赤字 -->
                <div class="u-align__right">
                 ( <span :class="{ 'c-text__danger': countInput >= 500 }">{{ countInput }}</span> / 500 文字 )
                </div>
                <!-- <InputError class="u-margin__top-s" :message="form.errors.step_content" /> -->
            </div>

            <!-- stepの繰り返し回数ボタン -->
            <div>
              <SecondaryButton v-show="stepIndex === lastIndex-1" @click="emits('addStep')">Stepを追加する</SecondaryButton>
              <SecondaryButton v-show="lastIndex !== 1" @click="emits('removeStep', stepIndex)">Stepを削除する</SecondaryButton>
            </div>
        </form>
    </section>
</template>

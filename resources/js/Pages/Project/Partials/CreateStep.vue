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
import { ref, watch, reactive,} from 'vue';

// バリデーション
import {
  isValidText
} from "@/Utils/validators";

// 親コンポーネントから値の受け取り
const props = defineProps({
  stepData: Object,
  stepIndex: Number,
  lastIndex: Number,
  errors:Object,
});

// 親コンポーネント(Create.vue)への受け渡し
const emits = defineEmits(['updateStepData', 'addStep', 'removeStep']);

// 入力文字のカウント(keyup)
const initialCountLength = props.stepData.content.length; // 初期値
const countInput = ref(initialCountLength);
const textCount = () => {
  countInput.value = props.stepData.content.length;
}

const selectedUnit = ref('hours'); // 選択された単位
const estimatedTime = ref(0); // 入力された時間

// 推定時間とその単位が変更された時に親コンポーネントに通知(時間計算は簡易に)
const updateEstimatedTime = () => {
  let timeInHours = estimatedTime.value;
  switch (selectedUnit.value) {
    case 'days':
      timeInHours *= 24;
      break;
    case 'months':
      timeInHours *= 24 * 30;
      break;
    case 'years':
      timeInHours *= 24 * 30 * 12;
      break;
  }

  const updatedStepData = { ...props.stepData, estimated_time: timeInHours };
  emits('updateStepData', { stepIndex: props.stepIndex, stepData: updatedStepData });
};

// 推定時間と単位に基づいて時間を計算し、親コンポーネントに通知
watch([selectedUnit, estimatedTime], () => {
  updateEstimatedTime();
});

// バリデーション
const validationErrors = reactive({});
const validText = (max, min, column, index) => {
  const text = props.stepData[column] ?? "";
  const { isValid, errorMessage } = isValidText(text, max, min);
  const key = `${index}.${column}`; // 修正: キーの生成方法

  if(!isValid){
     validationErrors[key] = errorMessage; // エラーメッセージを適切なキーに割り当てる
  } else {
    delete validationErrors[key]; // エラーがない場合はキーを削除
  }
}
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
            <div class="u-margin__top-lg">
                <InputLabel for="step-title" :value="$t('Step Title')" />
                <TextInput
                    id="step-title"
                    type="text"
                    class="c-text-input__full-width"
                    v-model="stepData.title"
                    required
                    @input="validText(50, 1, 'title', `${stepIndex}`)"
                />
                <!-- Laravel側のエラーメッセージ -->
                <InputError class="u-margin__top-s" :message="errors[`steps.${stepIndex}.title`]" />
                <!-- フロント側のエラーメッセージ -->
                <InputError class="u-margin__top-s" :message="validationErrors[`${stepIndex}.title`]" />
            </div>

            <!-- 目安達成時間 -->
            <div class="u-margin__top-lg">
                <InputLabel for="estimated-time" :value="$t('Estimated Time')" />
                <input type="number" 
                       id="estimated-time" 
                       min="0"
                       class="c-text-input c-text-input__1-2 u-margin__right-m"
                       v-model="estimatedTime">
                <select class="c-text-input c-text-input__1-2"
                        v-model="selectedUnit">
                  <option value="hours">時間</option>
                  <option value="days">日</option>
                  <option value="months">ヶ月</option>
                  <option value="years">年</option>
                </select>
                <InputError class="u-margin__top-s" :message="errors[`steps.${stepIndex}.estimated_time`]" />
            </div>

            <!-- Step 内容 -->
            <div class="u-margin__top-lg">
                <InputLabel for="step-content" :value="$t('Step Content')" />
                <Textarea
                    id="step-content"
                    type="text"
                    class="c-text-input__full-width"
                    v-model="stepData.content"
                    @keyup="textCount"
                    @input="validText(1000, 1, 'content', `${stepIndex}`)"
                    placeholder="例）英語の学習を6年ほど独学でやっています。
多言語にも共通する勉強方法もあると思うのでぜひチャレンジしてみてください"
                />
                <!-- カウントアップと500文字を超えたら赤字 -->
                <div class="u-align__right">
                 ( <span :class="{ 'c-text__danger': countInput > 500 }">{{ countInput }}</span> / 1000 文字 )
                </div>
                <InputError class="u-margin__top-s" :message="errors[`steps.${stepIndex}.content`]" />
                <!-- フロント側のエラーメッセージ -->
                <InputError class="u-margin__top-s" :message="validationErrors[`${stepIndex}.content`]" />
            </div>

            <!-- stepの繰り返し回数ボタン -->
            <div>
              <SecondaryButton v-show="stepIndex === lastIndex-1" 
                               @click="emits('addStep')"
                               class="c-btn__register">Stepを追加する</SecondaryButton>
              <SecondaryButton v-show="lastIndex !== 1" 
                               @click="emits('removeStep', stepIndex)"
                               class="c-btn__danger">Stepを削除する</SecondaryButton>
            </div>
        </form>
    </section>
</template>

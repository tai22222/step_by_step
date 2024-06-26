<script setup>
// コンポーネントの読み込み
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
// 各モジュールの読み込み
import { Link, useForm, usePage, } from '@inertiajs/vue3';
import { ref, watch, computed, } from 'vue';

// バリデーション
import {
  isValidText
} from "@/Utils/validators";

// 親コンポーネントから値の受け取り
const props = defineProps({
  categories:Object,
  projectData: Object,
  errors:Object,
});

// 親コンポーネント(Create.vueへの受け渡し)
const emits = defineEmits(['updateProjectData']);

// 合計推定時間を年月日時間に変換
const formattedEstimatedTime = computed(() => {
  const hours = props.projectData.estimated_time;
  const years = Math.floor(hours / (24 * 12 * 30));
  const months = Math.floor((hours % (24 * 12 * 30)) / (24 * 30));
  const days = Math.floor((hours % (24 * 30)) / 24);
  const remainingHours = hours % 24;
  return `${years}年 ${months}ヶ月 ${days}日 ${remainingHours}時間`;
});

// 入力文字のカウント(keyup)
const initialCountLength =props.projectData.content.length; // 初期値
const countInput = ref(initialCountLength);
const textCount = () => {
  countInput.value = props.projectData.content.length;
}

// データの変更を監視し、変化があればemitsで親コンポーネントに通知
watch(props.projectData , (newVal) => {
  emits('updateProjectData', newVal );
}, { deep: true });

// バリデーション
const validationErrors = ref({});
const validText = (max, min, column) => {
  const { isValid, errorMessage } = isValidText(props.projectData[column], max, min);
  if(!isValid){
    validationErrors.value[column] = errorMessage;
  } else {
    if (validationErrors.value[column]) {
      delete validationErrors.value[column];
    }
  }
}
</script>

<template>
    <section>
        <header>
           <h2 class="c-header__title">{{ $t('Create Project') }}</h2>

            <p class="c-header__description">
              {{ $t("This service is designed to enable individuals to share their optimal learning paths and resources based on their experiences, allowing other learners to use this information as a reference for creating their own study plans.") }}
            </p>
        </header>

        <form class="c-form">
            <!-- 目安達成時間 -->
            <div v-if="projectData.estimated_time">
              {{ $t('total estimated time') }} : {{ formattedEstimatedTime }} 
            </div>
            <!-- プロジェクト タイトル -->
            <div class="u-margin__top-lg">
                <InputLabel for="project-title" :value="$t('Project Title')" />
                <TextInput
                    id="project-title"
                    type="text"
                    class="c-text-input__full-width"
                    v-model="projectData.title"
                    required
                    autofocus
                    @input="validText(50, 1, 'title')"
                />
                <!-- Laravelからのエラーメッセージ -->
                  <InputError 
                    class="u-margin__top-s"
                    :message="errors['project.title']" />
                <!-- フロント側のエラーメッセージ -->
                <InputError class="u-margin__top-s" :message="validationErrors['title']" />
            </div>

            <!-- カテゴリ -->
            <div class="u-margin__top-lg">
                <InputLabel for="category-id" :value="$t('category')" />
                <select v-model="projectData.category_id"
                        class="c-text-input c-text-input__3-4">
                  <option value="0">選択してください</option>
                  <option 
                      v-for="category in $page.props.categories"
                      :key="category.id"
                      :value="category.sort_order"> {{ category.name }}</option>
                </select>
                <!-- Laravelからのエラーメッセージ -->
                <InputError class="u-margin__top-s" :message="errors['project.category_id']" />
            </div>

            <!-- プロジェクト 内容 -->
            <div class="u-margin__top-lg">
                <InputLabel for="project-content" :value="$t('Project Content')" />
                <Textarea
                    id="project-content"
                    type="text"
                    class="c-text-input__full-width"
                    v-model="projectData.content"
                    @keyup="textCount"
                    @input="validText(1000, 1, 'content')"
                    placeholder="例）英語を独学で最短最速で習得するための方法を5つのStepで説明。

私が6年間色々な方法で英語を学び、今振り返ってよかった学習方法、悪かった学習方法を厳選しお伝えします。

対象者：高校レベルの英単語はわかる人方
内容：旅行などで困ることがないくらいのリスニングとスピーキング"
                />
                <!-- カウントアップと500文字を超えたら赤字 -->
                <div class="u-align__right">
                 ( <span :class="{ 'c-text__danger': countInput > 500 }">{{ countInput }}</span> / 1000 文字 )
                </div>
                <!-- Laravelからのエラーメッセージ -->
                <InputError class="u-margin__top-s" :message="errors['project.content']" />
                <!-- フロント側のエラーメッセージ -->
                <InputError class="u-margin__top-s" :message="validationErrors['content']" />
            </div>
        </form>
    </section>
</template>

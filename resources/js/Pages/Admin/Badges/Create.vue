<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    courses: Array,
});

const form = useForm({
    name: '',
    description: '',
    icon: '🏆',
    color: '#FFD700',
    type: 'lessons_count',
    requirement_value: 1,
    course_id: '',
});

const submit = () => {
    form.post(route('admin.badges.store'));
};

const iconOptions = ['🏆', '⭐', '🎖️', '🥇', '🥈', '🥉', '🎯', '🔥', '💎', '👑', '🚀', '💪', '📚', '✨', '🎓'];
</script>

<template>
    <Head title="バッジ作成" />

    <AdminLayout>
        <template #title>バッジ作成</template>

        <div class="max-w-2xl">
            <Link :href="route('admin.badges.index')" class="inline-flex items-center text-slate-400 hover:text-white mb-6">
                ← バッジ一覧に戻る
            </Link>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="rounded-xl bg-slate-800 border border-slate-700 p-6 space-y-6">
                    <!-- Preview -->
                    <div class="flex items-center gap-4 p-4 rounded-lg bg-slate-900/50 border border-slate-700">
                        <div 
                            class="w-20 h-20 rounded-xl flex items-center justify-center text-5xl"
                            :style="{ backgroundColor: form.color + '30' }"
                        >
                            {{ form.icon }}
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">{{ form.name || 'バッジ名' }}</h3>
                            <p class="text-sm text-slate-400">{{ form.description || '説明文' }}</p>
                        </div>
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">バッジ名 *</label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                            placeholder="例: 初めての一歩"
                            required
                        />
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">説明 *</label>
                        <textarea
                            v-model="form.description"
                            rows="2"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                            placeholder="例: 最初のレッスンを完了する"
                            required
                        ></textarea>
                    </div>

                    <!-- Icon -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">アイコン</label>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="icon in iconOptions"
                                :key="icon"
                                type="button"
                                @click="form.icon = icon"
                                class="w-12 h-12 rounded-lg text-2xl transition"
                                :class="form.icon === icon ? 'bg-cyan-500 ring-2 ring-cyan-400' : 'bg-slate-700 hover:bg-slate-600'"
                            >
                                {{ icon }}
                            </button>
                        </div>
                    </div>

                    <!-- Color -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">カラー</label>
                        <input
                            v-model="form.color"
                            type="color"
                            class="w-full h-12 rounded-lg bg-slate-900 border-slate-600 cursor-pointer"
                        />
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">条件タイプ *</label>
                        <select
                            v-model="form.type"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                        >
                            <option value="first_lesson">初レッスン完了</option>
                            <option value="lessons_count">レッスン完了数</option>
                            <option value="course_complete">コース完了</option>
                            <option value="streak">連続学習日数</option>
                            <option value="points">獲得ポイント</option>
                            <option value="perfect_score">満点スコア</option>
                            <option value="special">特別（手動付与）</option>
                        </select>
                    </div>

                    <!-- Requirement Value -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">条件値</label>
                        <input
                            v-model="form.requirement_value"
                            type="number"
                            min="1"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                        />
                        <p class="text-xs text-slate-500 mt-1">例: レッスン数=5、連続日数=7、ポイント=1000</p>
                    </div>

                    <!-- Course (for course_complete type) -->
                    <div v-if="form.type === 'course_complete'">
                        <label class="block text-sm font-medium text-slate-300 mb-2">対象コース</label>
                        <select
                            v-model="form.course_id"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                        >
                            <option value="">選択してください</option>
                            <option v-for="course in courses" :key="course.id" :value="course.id">
                                {{ course.title }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <Link
                        :href="route('admin.badges.index')"
                        class="px-6 py-3 rounded-lg bg-slate-700 text-white hover:bg-slate-600 transition"
                    >
                        キャンセル
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-3 rounded-lg bg-cyan-500 text-white font-medium hover:bg-cyan-600 transition disabled:opacity-50"
                    >
                        {{ form.processing ? '作成中...' : 'バッジを作成' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>


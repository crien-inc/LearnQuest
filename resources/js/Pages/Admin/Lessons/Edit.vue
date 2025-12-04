<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    course: Object,
    lesson: Object,
});

const form = useForm({
    title: props.lesson.title,
    description: props.lesson.description || '',
    type: props.lesson.type,
    content: props.lesson.content || '',
    video_url: props.lesson.video_url || '',
    duration_minutes: props.lesson.duration_minutes,
    order: props.lesson.order,
    points_reward: props.lesson.points_reward,
    is_published: props.lesson.is_published,
});

const submit = () => {
    form.put(route('admin.courses.lessons.update', [props.course.id, props.lesson.id]));
};
</script>

<template>
    <Head title="レッスン編集" />

    <AdminLayout>
        <template #title>レッスン編集</template>

        <div class="max-w-2xl">
            <Link :href="route('admin.courses.lessons.index', course.id)" class="inline-flex items-center text-slate-400 hover:text-white mb-6">
                ← レッスン一覧に戻る
            </Link>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="rounded-xl bg-slate-800 border border-slate-700 p-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">レッスン名 *</label>
                        <input
                            v-model="form.title"
                            type="text"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                            required
                        />
                        <p v-if="form.errors.title" class="mt-1 text-sm text-red-400">{{ form.errors.title }}</p>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">レッスンタイプ *</label>
                        <select
                            v-model="form.type"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                        >
                            <option value="video">🎬 動画</option>
                            <option value="text">📄 テキスト</option>
                            <option value="quiz">❓ クイズ</option>
                            <option value="assignment">📝 課題</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">説明</label>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                        ></textarea>
                    </div>

                    <!-- Video URL -->
                    <div v-if="form.type === 'video'">
                        <label class="block text-sm font-medium text-slate-300 mb-2">動画URL</label>
                        <input
                            v-model="form.video_url"
                            type="url"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                            placeholder="https://www.youtube.com/watch?v=... または Google DriveのURL"
                        />
                        <p class="mt-2 text-xs text-slate-400">
                            対応形式: YouTube（通常URL・短縮URL）、Google Drive（共有URL）<br>
                            ※ Google Driveは「リンクを知っている全員」に閲覧権限を付与してください
                        </p>
                    </div>

                    <!-- Content -->
                    <div v-if="form.type !== 'video'">
                        <label class="block text-sm font-medium text-slate-300 mb-2">コンテンツ</label>
                        <textarea
                            v-model="form.content"
                            rows="8"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500 font-mono text-sm"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">所要時間（分）</label>
                            <input
                                v-model="form.duration_minutes"
                                type="number"
                                min="0"
                                class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">表示順序</label>
                            <input
                                v-model="form.order"
                                type="number"
                                min="0"
                                class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">ポイント</label>
                            <input
                                v-model="form.points_reward"
                                type="number"
                                min="0"
                                class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                            />
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <input
                            v-model="form.is_published"
                            type="checkbox"
                            id="is_published"
                            class="rounded bg-slate-900 border-slate-600 text-cyan-500 focus:ring-cyan-500"
                        />
                        <label for="is_published" class="text-sm text-slate-300">公開する</label>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <Link
                        :href="route('admin.courses.lessons.index', course.id)"
                        class="px-6 py-3 rounded-lg bg-slate-700 text-white hover:bg-slate-600 transition"
                    >
                        キャンセル
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-3 rounded-lg bg-cyan-500 text-white font-medium hover:bg-cyan-600 transition disabled:opacity-50"
                    >
                        {{ form.processing ? '更新中...' : '変更を保存' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>


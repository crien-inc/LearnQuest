<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    course: Object,
    lessons: Array,
});

const deleteLesson = (lesson) => {
    if (confirm(`「${lesson.title}」を削除してもよろしいですか？`)) {
        router.delete(route('admin.courses.lessons.destroy', [lesson.course_id, lesson.id]));
    }
};

const getTypeIcon = (type) => {
    switch (type) {
        case 'video': return '🎬';
        case 'text': return '📄';
        case 'quiz': return '❓';
        case 'assignment': return '📝';
        default: return '📚';
    }
};
</script>

<template>
    <Head :title="`レッスン管理 - ${course.title}`" />

    <AdminLayout>
        <template #title>レッスン管理</template>

        <Link :href="route('admin.courses.index')" class="inline-flex items-center text-slate-400 hover:text-white mb-4">
            ← コース一覧に戻る
        </Link>

        <!-- Course Info -->
        <div class="rounded-xl bg-slate-800 border border-slate-700 p-4 mb-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-2xl">
                📖
            </div>
            <div>
                <h2 class="text-lg font-semibold text-white">{{ course.title }}</h2>
                <p class="text-sm text-slate-400">{{ lessons.length }}レッスン</p>
            </div>
        </div>

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-white">レッスン一覧</h3>
            <Link
                :href="route('admin.courses.lessons.create', course.id)"
                class="px-4 py-2 rounded-lg bg-cyan-500 text-white font-medium hover:bg-cyan-600 transition"
            >
                ＋ 新規レッスン作成
            </Link>
        </div>

        <!-- Lessons List -->
        <div class="space-y-3">
            <div
                v-for="lesson in lessons"
                :key="lesson.id"
                class="rounded-xl bg-slate-800 border border-slate-700 p-4 flex items-center gap-4"
            >
                <div class="w-10 h-10 rounded-lg bg-slate-700 flex items-center justify-center text-lg shrink-0">
                    {{ lesson.order }}
                </div>
                <div class="w-10 h-10 rounded-lg bg-slate-700/50 flex items-center justify-center text-lg shrink-0">
                    {{ getTypeIcon(lesson.type) }}
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-white truncate">{{ lesson.title }}</h4>
                    <div class="flex items-center gap-4 text-sm text-slate-400">
                        <span v-if="lesson.duration_minutes">{{ lesson.duration_minutes }}分</span>
                        <span class="text-amber-400">+{{ lesson.points_reward }}pt</span>
                    </div>
                </div>
                <span 
                    class="px-2 py-1 rounded-full text-xs font-medium shrink-0"
                    :class="lesson.is_published 
                        ? 'bg-emerald-500/20 text-emerald-400' 
                        : 'bg-slate-600 text-slate-400'"
                >
                    {{ lesson.is_published ? '公開中' : '下書き' }}
                </span>
                <div class="flex items-center gap-2 shrink-0">
                    <Link
                        :href="route('admin.courses.lessons.edit', [course.id, lesson.id])"
                        class="px-3 py-1 rounded-lg bg-slate-700 text-slate-300 text-sm hover:bg-slate-600 transition"
                    >
                        編集
                    </Link>
                    <button
                        @click="deleteLesson(lesson)"
                        class="px-3 py-1 rounded-lg bg-red-500/20 text-red-400 text-sm hover:bg-red-500/30 transition"
                    >
                        削除
                    </button>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="lessons.length === 0" class="rounded-xl bg-slate-800 border border-slate-700 p-12 text-center">
            <div class="text-6xl mb-4">📖</div>
            <h3 class="text-lg font-semibold text-white mb-2">まだレッスンがありません</h3>
            <p class="text-slate-400 mb-4">最初のレッスンを作成しましょう</p>
            <Link
                :href="route('admin.courses.lessons.create', course.id)"
                class="inline-block px-4 py-2 rounded-lg bg-cyan-500 text-white font-medium hover:bg-cyan-600 transition"
            >
                ＋ 新規レッスン作成
            </Link>
        </div>
    </AdminLayout>
</template>


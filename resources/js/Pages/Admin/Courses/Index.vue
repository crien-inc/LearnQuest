<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    courses: Array,
});

const deleteCourse = (course) => {
    if (confirm(`「${course.title}」を削除してもよろしいですか？`)) {
        router.delete(route('admin.courses.destroy', course.id));
    }
};
</script>

<template>
    <Head title="コース管理" />

    <AdminLayout>
        <template #title>コース管理</template>

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-slate-400">コースの追加・編集・削除ができます</p>
            </div>
            <Link
                :href="route('admin.courses.create')"
                class="px-4 py-2 rounded-lg bg-cyan-500 text-white font-medium hover:bg-cyan-600 transition"
            >
                ＋ 新規コース作成
            </Link>
        </div>

        <!-- Courses Table -->
        <div class="rounded-xl bg-slate-800 border border-slate-700 overflow-hidden">
            <table class="w-full">
                <thead class="bg-slate-700/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-medium text-slate-300">順序</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-slate-300">コース名</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-slate-300">レッスン数</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-slate-300">ポイント</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-slate-300">状態</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-300">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    <tr v-for="course in courses" :key="course.id" class="hover:bg-slate-700/30">
                        <td class="px-6 py-4 text-slate-400">{{ course.order }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-lg shrink-0">
                                    📖
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ course.title }}</div>
                                    <div class="text-sm text-slate-400 truncate max-w-xs">{{ course.description }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-300">{{ course.lessons_count }}</td>
                        <td class="px-6 py-4 text-amber-400">+{{ course.points_reward }}pt</td>
                        <td class="px-6 py-4">
                            <span 
                                class="px-2 py-1 rounded-full text-xs font-medium"
                                :class="course.is_published 
                                    ? 'bg-emerald-500/20 text-emerald-400' 
                                    : 'bg-slate-600 text-slate-400'"
                            >
                                {{ course.is_published ? '公開中' : '下書き' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <Link
                                    :href="route('admin.courses.lessons.index', course.id)"
                                    class="px-3 py-1 rounded-lg bg-slate-700 text-slate-300 text-sm hover:bg-slate-600 transition"
                                >
                                    📖 レッスン
                                </Link>
                                <Link
                                    :href="route('admin.courses.edit', course.id)"
                                    class="px-3 py-1 rounded-lg bg-slate-700 text-slate-300 text-sm hover:bg-slate-600 transition"
                                >
                                    編集
                                </Link>
                                <button
                                    @click="deleteCourse(course)"
                                    class="px-3 py-1 rounded-lg bg-red-500/20 text-red-400 text-sm hover:bg-red-500/30 transition"
                                >
                                    削除
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Empty State -->
            <div v-if="courses.length === 0" class="p-12 text-center">
                <div class="text-6xl mb-4">📚</div>
                <h3 class="text-lg font-semibold text-white mb-2">まだコースがありません</h3>
                <p class="text-slate-400 mb-4">最初のコースを作成しましょう</p>
                <Link
                    :href="route('admin.courses.create')"
                    class="inline-block px-4 py-2 rounded-lg bg-cyan-500 text-white font-medium hover:bg-cyan-600 transition"
                >
                    ＋ 新規コース作成
                </Link>
            </div>
        </div>
    </AdminLayout>
</template>


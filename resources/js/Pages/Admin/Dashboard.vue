<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
    recentUsers: Array,
    recentProgress: Array,
});
</script>

<template>
    <Head title="管理者ダッシュボード" />

    <AdminLayout>
        <template #title>ダッシュボード</template>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
            <div class="rounded-xl bg-slate-800 border border-slate-700 p-6">
                <div class="text-3xl mb-2">👥</div>
                <div class="text-2xl font-bold text-white">{{ stats.totalUsers }}</div>
                <div class="text-sm text-slate-400">総ユーザー数</div>
            </div>
            <div class="rounded-xl bg-slate-800 border border-slate-700 p-6">
                <div class="text-3xl mb-2">📚</div>
                <div class="text-2xl font-bold text-white">{{ stats.totalCourses }}</div>
                <div class="text-sm text-slate-400">コース数</div>
            </div>
            <div class="rounded-xl bg-slate-800 border border-slate-700 p-6">
                <div class="text-3xl mb-2">📖</div>
                <div class="text-2xl font-bold text-white">{{ stats.totalLessons }}</div>
                <div class="text-sm text-slate-400">レッスン数</div>
            </div>
            <div class="rounded-xl bg-slate-800 border border-slate-700 p-6">
                <div class="text-3xl mb-2">🏆</div>
                <div class="text-2xl font-bold text-white">{{ stats.totalBadges }}</div>
                <div class="text-sm text-slate-400">バッジ数</div>
            </div>
            <div class="rounded-xl bg-slate-800 border border-slate-700 p-6">
                <div class="text-3xl mb-2">✅</div>
                <div class="text-2xl font-bold text-emerald-400">{{ stats.completedLessons }}</div>
                <div class="text-sm text-slate-400">完了レッスン</div>
            </div>
            <div class="rounded-xl bg-slate-800 border border-slate-700 p-6">
                <div class="text-3xl mb-2">🔥</div>
                <div class="text-2xl font-bold text-amber-400">{{ stats.activeUsers }}</div>
                <div class="text-sm text-slate-400">アクティブユーザー</div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Recent Users -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-white">👥 最新のユーザー</h2>
                    <Link :href="route('admin.users.index')" class="text-sm text-cyan-400 hover:text-cyan-300">
                        すべて見る →
                    </Link>
                </div>
                <div class="rounded-xl bg-slate-800 border border-slate-700 divide-y divide-slate-700">
                    <div 
                        v-for="user in recentUsers"
                        :key="user.id"
                        class="p-4 flex items-center justify-between"
                    >
                        <div>
                            <div class="font-medium text-white">{{ user.name }}</div>
                            <div class="text-sm text-slate-400">{{ user.email }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-amber-400">Lv.{{ user.level }}</div>
                            <div class="text-xs text-slate-500">{{ user.total_points }}pt</div>
                        </div>
                    </div>
                    <div v-if="recentUsers.length === 0" class="p-8 text-center text-slate-400">
                        まだユーザーがいません
                    </div>
                </div>
            </div>

            <!-- Recent Progress -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-white">📝 最近の学習活動</h2>
                </div>
                <div class="rounded-xl bg-slate-800 border border-slate-700 divide-y divide-slate-700">
                    <div 
                        v-for="progress in recentProgress"
                        :key="progress.id"
                        class="p-4 flex items-center gap-4"
                    >
                        <div class="w-10 h-10 rounded-lg bg-emerald-500/20 flex items-center justify-center text-lg">
                            ✅
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-white truncate">{{ progress.user?.name }}</div>
                            <div class="text-sm text-slate-400 truncate">{{ progress.lesson?.title }}</div>
                        </div>
                    </div>
                    <div v-if="recentProgress.length === 0" class="p-8 text-center text-slate-400">
                        まだ学習活動がありません
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>


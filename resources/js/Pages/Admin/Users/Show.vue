<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    user: Object,
    progressByCourse: Array,
});
</script>

<template>
    <Head :title="`ユーザー詳細 - ${user.name}`" />

    <AdminLayout>
        <template #title>ユーザー詳細</template>

        <Link :href="route('admin.users.index')" class="inline-flex items-center text-slate-400 hover:text-white mb-6">
            ← ユーザー一覧に戻る
        </Link>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- User Info -->
            <div class="lg:col-span-1">
                <div class="rounded-xl bg-slate-800 border border-slate-700 p-6">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-cyan-500 to-purple-500 flex items-center justify-center text-4xl mb-4">
                            {{ user.name.charAt(0) }}
                        </div>
                        <h2 class="text-xl font-bold text-white">{{ user.name }}</h2>
                        <p class="text-slate-400">{{ user.email }}</p>
                        <span 
                            class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium"
                            :class="user.role === 'admin' 
                                ? 'bg-amber-500/20 text-amber-400' 
                                : 'bg-slate-600 text-slate-300'"
                        >
                            {{ user.role === 'admin' ? '管理者' : '一般ユーザー' }}
                        </span>
                    </div>

                    <div class="space-y-4 pt-6 border-t border-slate-700">
                        <div class="flex justify-between">
                            <span class="text-slate-400">レベル</span>
                            <span class="text-cyan-400 font-semibold">Lv.{{ user.level }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">総ポイント</span>
                            <span class="text-amber-400 font-semibold">{{ user.total_points }}pt</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">連続学習</span>
                            <span class="text-rose-400 font-semibold">{{ user.streak_days }}日</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">獲得バッジ</span>
                            <span class="text-white font-semibold">{{ user.badges?.length || 0 }}個</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-700">
                        <Link
                            :href="route('admin.users.edit', user.id)"
                            class="block w-full px-4 py-2 rounded-lg bg-cyan-500 text-white font-medium text-center hover:bg-cyan-600 transition"
                        >
                            ユーザーを編集
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Progress & Badges -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Badges -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">🏆 獲得バッジ</h3>
                    <div v-if="user.badges?.length" class="grid grid-cols-4 sm:grid-cols-6 gap-3">
                        <div
                            v-for="badge in user.badges"
                            :key="badge.id"
                            class="aspect-square rounded-xl flex items-center justify-center text-3xl"
                            :style="{ backgroundColor: badge.color + '30' }"
                            :title="badge.name"
                        >
                            {{ badge.icon }}
                        </div>
                    </div>
                    <div v-else class="rounded-xl bg-slate-800 border border-slate-700 p-8 text-center text-slate-400">
                        まだバッジを獲得していません
                    </div>
                </div>

                <!-- Course Progress -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">📚 コース進捗</h3>
                    <div v-if="progressByCourse?.length" class="space-y-4">
                        <div
                            v-for="item in progressByCourse"
                            :key="item.course?.id"
                            class="rounded-xl bg-slate-800 border border-slate-700 p-4"
                        >
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-white">{{ item.course?.title }}</h4>
                                <span 
                                    class="text-sm font-semibold"
                                    :class="item.percentage === 100 ? 'text-emerald-400' : 'text-cyan-400'"
                                >
                                    {{ item.percentage }}%
                                </span>
                            </div>
                            <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                                <div 
                                    class="h-full rounded-full transition-all"
                                    :class="item.percentage === 100 ? 'bg-emerald-400' : 'bg-cyan-400'"
                                    :style="{ width: item.percentage + '%' }"
                                ></div>
                            </div>
                            <div class="mt-2 text-sm text-slate-400">
                                {{ item.completed }} / {{ item.total }} レッスン完了
                            </div>
                        </div>
                    </div>
                    <div v-else class="rounded-xl bg-slate-800 border border-slate-700 p-8 text-center text-slate-400">
                        まだ学習を開始していません
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>


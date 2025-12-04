<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    badges: Array,
});

const deleteBadge = (badge) => {
    if (confirm(`「${badge.name}」を削除してもよろしいですか？`)) {
        router.delete(route('admin.badges.destroy', badge.id));
    }
};
</script>

<template>
    <Head title="バッジ管理" />

    <AdminLayout>
        <template #title>バッジ管理</template>

        <div class="flex items-center justify-between mb-6">
            <p class="text-slate-400">バッジの追加・編集・削除ができます</p>
            <Link
                :href="route('admin.badges.create')"
                class="px-4 py-2 rounded-lg bg-cyan-500 text-white font-medium hover:bg-cyan-600 transition"
            >
                ＋ 新規バッジ作成
            </Link>
        </div>

        <!-- Badges Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
                v-for="badge in badges"
                :key="badge.id"
                class="rounded-xl bg-slate-800 border border-slate-700 p-6"
            >
                <div class="flex items-start gap-4 mb-4">
                    <div 
                        class="w-16 h-16 rounded-xl flex items-center justify-center text-4xl shrink-0"
                        :style="{ backgroundColor: badge.color + '30' }"
                    >
                        {{ badge.icon }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-white truncate">{{ badge.name }}</h3>
                        <p class="text-sm text-slate-400 line-clamp-2">{{ badge.description }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 mb-4">
                    <span 
                        class="px-2 py-1 rounded-full text-xs font-medium"
                        :style="{ backgroundColor: badge.color + '20', color: badge.color }"
                    >
                        {{ badge.type }}
                    </span>
                    <span class="text-sm text-slate-400">
                        条件: {{ badge.requirement_value }}
                    </span>
                    <span v-if="badge.course" class="text-sm text-slate-400">
                        | {{ badge.course.title }}
                    </span>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-700">
                    <span class="text-sm text-slate-400">
                        {{ badge.users_count }}人が獲得
                    </span>
                    <div class="flex gap-2">
                        <Link
                            :href="route('admin.badges.edit', badge.id)"
                            class="px-3 py-1 rounded-lg bg-slate-700 text-slate-300 text-sm hover:bg-slate-600 transition"
                        >
                            編集
                        </Link>
                        <button
                            @click="deleteBadge(badge)"
                            class="px-3 py-1 rounded-lg bg-red-500/20 text-red-400 text-sm hover:bg-red-500/30 transition"
                        >
                            削除
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="badges.length === 0" class="rounded-xl bg-slate-800 border border-slate-700 p-12 text-center">
            <div class="text-6xl mb-4">🏆</div>
            <h3 class="text-lg font-semibold text-white mb-2">まだバッジがありません</h3>
            <p class="text-slate-400 mb-4">最初のバッジを作成しましょう</p>
            <Link
                :href="route('admin.badges.create')"
                class="inline-block px-4 py-2 rounded-lg bg-cyan-500 text-white font-medium hover:bg-cyan-600 transition"
            >
                ＋ 新規バッジ作成
            </Link>
        </div>
    </AdminLayout>
</template>


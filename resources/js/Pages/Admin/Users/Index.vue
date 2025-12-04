<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    users: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const role = ref(props.filters.role || '');

const applyFilters = () => {
    router.get(route('admin.users.index'), {
        search: search.value || undefined,
        role: role.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    });
};

const deleteUser = (user) => {
    if (confirm(`「${user.name}」を削除してもよろしいですか？`)) {
        router.delete(route('admin.users.destroy', user.id));
    }
};

watch([search, role], () => {
    applyFilters();
}, { debounce: 300 });
</script>

<template>
    <Head title="ユーザー管理" />

    <AdminLayout>
        <template #title>ユーザー管理</template>

        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-4 mb-6">
            <input
                v-model="search"
                type="text"
                placeholder="名前またはメールで検索..."
                class="flex-1 min-w-[200px] rounded-lg bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-cyan-500 focus:ring-cyan-500"
            />
            <select
                v-model="role"
                class="rounded-lg bg-slate-800 border-slate-700 text-white focus:border-cyan-500 focus:ring-cyan-500"
            >
                <option value="">すべての権限</option>
                <option value="user">一般ユーザー</option>
                <option value="admin">管理者</option>
            </select>
            <Link
                :href="route('admin.users.create')"
                class="px-4 py-2 rounded-lg bg-cyan-500 text-white font-medium hover:bg-cyan-600 transition"
            >
                ＋ 新規ユーザー作成
            </Link>
        </div>

        <!-- Users Table -->
        <div class="rounded-xl bg-slate-800 border border-slate-700 overflow-hidden">
            <table class="w-full">
                <thead class="bg-slate-700/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-medium text-slate-300">ユーザー</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-slate-300">権限</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-slate-300">レベル</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-slate-300">ポイント</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-slate-300">バッジ</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-300">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    <tr v-for="user in users.data" :key="user.id" class="hover:bg-slate-700/30">
                        <td class="px-6 py-4">
                            <div>
                                <div class="font-medium text-white">{{ user.name }}</div>
                                <div class="text-sm text-slate-400">{{ user.email }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span 
                                class="px-2 py-1 rounded-full text-xs font-medium"
                                :class="user.role === 'admin' 
                                    ? 'bg-amber-500/20 text-amber-400' 
                                    : 'bg-slate-600 text-slate-300'"
                            >
                                {{ user.role === 'admin' ? '管理者' : '一般' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-cyan-400">Lv.{{ user.level }}</td>
                        <td class="px-6 py-4 text-amber-400">{{ user.total_points }}pt</td>
                        <td class="px-6 py-4 text-slate-300">{{ user.badges_count }}個</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <Link
                                    :href="route('admin.users.show', user.id)"
                                    class="px-3 py-1 rounded-lg bg-slate-700 text-slate-300 text-sm hover:bg-slate-600 transition"
                                >
                                    詳細
                                </Link>
                                <Link
                                    :href="route('admin.users.edit', user.id)"
                                    class="px-3 py-1 rounded-lg bg-slate-700 text-slate-300 text-sm hover:bg-slate-600 transition"
                                >
                                    編集
                                </Link>
                                <button
                                    @click="deleteUser(user)"
                                    class="px-3 py-1 rounded-lg bg-red-500/20 text-red-400 text-sm hover:bg-red-500/30 transition"
                                >
                                    削除
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="users.data.length === 0" class="p-12 text-center">
                <div class="text-6xl mb-4">👥</div>
                <h3 class="text-lg font-semibold text-white mb-2">ユーザーが見つかりません</h3>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="users.links && users.links.length > 3" class="flex justify-center gap-2 mt-6">
            <Link
                v-for="link in users.links"
                :key="link.label"
                :href="link.url || '#'"
                class="px-4 py-2 rounded-lg text-sm transition"
                :class="link.active 
                    ? 'bg-cyan-500 text-white' 
                    : link.url 
                        ? 'bg-slate-700 text-slate-300 hover:bg-slate-600' 
                        : 'bg-slate-800 text-slate-500 cursor-not-allowed'"
                v-html="link.label"
            />
        </div>
    </AdminLayout>
</template>


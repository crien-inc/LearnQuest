<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user',
});

const submit = () => {
    form.post(route('admin.users.store'));
};
</script>

<template>
    <Head title="ユーザー作成" />

    <AdminLayout>
        <template #title>ユーザー作成</template>

        <div class="max-w-2xl">
            <Link :href="route('admin.users.index')" class="inline-flex items-center text-slate-400 hover:text-white mb-6">
                ← ユーザー一覧に戻る
            </Link>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="rounded-xl bg-slate-800 border border-slate-700 p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">名前 *</label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                            required
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-400">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">メールアドレス *</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                            required
                        />
                        <p v-if="form.errors.email" class="mt-1 text-sm text-red-400">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">パスワード *</label>
                        <input
                            v-model="form.password"
                            type="password"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                            required
                        />
                        <p v-if="form.errors.password" class="mt-1 text-sm text-red-400">{{ form.errors.password }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">パスワード（確認） *</label>
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                            required
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">権限 *</label>
                        <select
                            v-model="form.role"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                        >
                            <option value="user">一般ユーザー</option>
                            <option value="admin">管理者</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <Link
                        :href="route('admin.users.index')"
                        class="px-6 py-3 rounded-lg bg-slate-700 text-white hover:bg-slate-600 transition"
                    >
                        キャンセル
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-3 rounded-lg bg-cyan-500 text-white font-medium hover:bg-cyan-600 transition disabled:opacity-50"
                    >
                        {{ form.processing ? '作成中...' : 'ユーザーを作成' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>


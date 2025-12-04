<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="ログイン" />

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-white mb-2">おかえりなさい！</h1>
            <p class="text-white/60">アカウントにログインして学習を続けましょう</p>
        </div>

        <!-- Status message -->
        <div v-if="status" class="mb-6 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-sm text-center">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-white/80 mb-2">
                    メールアドレス
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">📧</span>
                    <input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        class="w-full pl-12 pr-4 py-3 rounded-xl bg-white/5 border border-white/20 text-white placeholder-white/40 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 focus:outline-none transition"
                        placeholder="your@email.com"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-white/80 mb-2">
                    パスワード
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">🔒</span>
                    <input
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        class="w-full pl-12 pr-4 py-3 rounded-xl bg-white/5 border border-white/20 text-white placeholder-white/40 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 focus:outline-none transition"
                        placeholder="••••••••"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- Remember & Forgot -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input
                        type="checkbox"
                        v-model="form.remember"
                        class="w-5 h-5 rounded border-white/20 bg-white/5 text-cyan-500 focus:ring-cyan-500/20 focus:ring-offset-0 cursor-pointer"
                    />
                    <span class="text-sm text-white/60 group-hover:text-white/80 transition">
                        ログイン状態を保持
                    </span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-cyan-400 hover:text-cyan-300 transition"
                >
                    パスワードを忘れた？
                </Link>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full py-4 rounded-xl bg-gradient-to-r from-cyan-500 to-purple-500 text-white font-semibold text-lg transition-all hover:opacity-90 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center justify-center gap-2"
            >
                <span v-if="form.processing" class="animate-spin">⏳</span>
                <span v-else>🚀</span>
                <span>{{ form.processing ? 'ログイン中...' : 'ログイン' }}</span>
            </button>
        </form>

        <!-- Register Link -->
        <div class="mt-8 pt-6 border-t border-white/10 text-center">
            <p class="text-white/60">
                アカウントをお持ちでないですか？
                <Link :href="route('register')" class="text-cyan-400 hover:text-cyan-300 font-medium transition">
                    新規登録
                </Link>
            </p>
        </div>
    </GuestLayout>
</template>

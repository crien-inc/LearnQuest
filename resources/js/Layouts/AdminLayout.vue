<script setup>
import { ref, computed } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingSidebar = ref(true);

const page = usePage();
const user = computed(() => page.props.auth.user);

const navItems = [
    { name: 'ダッシュボード', route: 'admin.dashboard', icon: '📊', pattern: 'admin.dashboard' },
    { name: 'コース管理', route: 'admin.courses.index', icon: '📚', pattern: 'admin.courses.*' },
    { name: 'バッジ管理', route: 'admin.badges.index', icon: '🏆', pattern: 'admin.badges.*' },
    { name: 'ユーザー管理', route: 'admin.users.index', icon: '👥', pattern: 'admin.users.*' },
];
</script>

<template>
    <div class="min-h-screen bg-slate-900">
        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-800 border-r border-slate-700 transition-transform duration-300"
            :class="showingSidebar ? 'translate-x-0' : '-translate-x-full'"
        >
            <!-- Logo -->
            <div class="h-16 flex items-center justify-between px-4 border-b border-slate-700">
                <Link :href="route('admin.dashboard')" class="flex items-center gap-2">
                    <span class="text-2xl">⚙️</span>
                    <span class="text-lg font-bold text-white">管理画面</span>
                </Link>
                <button 
                    @click="showingSidebar = false"
                    class="lg:hidden p-2 text-slate-400 hover:text-white"
                >
                    ✕
                </button>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-1">
                <Link
                    v-for="item in navItems"
                    :key="item.route"
                    :href="route(item.route)"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-300 transition"
                    :class="route().current(item.pattern) 
                        ? 'bg-cyan-500/20 text-cyan-400' 
                        : 'hover:bg-slate-700 hover:text-white'"
                >
                    <span class="text-xl">{{ item.icon }}</span>
                    <span>{{ item.name }}</span>
                </Link>
            </nav>

            <!-- Back to Site -->
            <div class="absolute bottom-4 left-4 right-4">
                <Link
                    :href="route('dashboard')"
                    class="flex items-center gap-2 px-4 py-3 rounded-lg bg-slate-700 text-slate-300 hover:bg-slate-600 hover:text-white transition"
                >
                    <span>←</span>
                    <span>サイトに戻る</span>
                </Link>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="lg:ml-64 transition-all duration-300" :class="showingSidebar ? 'ml-64' : 'ml-0'">
            <!-- Top Bar -->
            <header class="h-16 bg-slate-800 border-b border-slate-700 flex items-center justify-between px-4 lg:px-8">
                <div class="flex items-center gap-4">
                    <button 
                        @click="showingSidebar = !showingSidebar"
                        class="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-slate-700"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold text-white">
                        <slot name="title">管理画面</slot>
                    </h1>
                </div>

                <!-- User Menu -->
                <div class="relative">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-700 text-slate-300 hover:bg-slate-600 hover:text-white transition">
                                <span>{{ user?.name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('profile.edit')">
                                プロフィール
                            </DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">
                                ログアウト
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 lg:p-8">
                <slot />
            </main>
        </div>

        <!-- Mobile Overlay -->
        <div 
            v-if="showingSidebar"
            @click="showingSidebar = false"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden"
        ></div>
    </div>
</template>


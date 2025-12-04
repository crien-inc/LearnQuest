<script setup>
import { ref, computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

const page = usePage();
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => user.value?.role === 'admin');
</script>

<template>
    <div>
        <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
            <nav class="border-b border-white/10 bg-black/20 backdrop-blur-xl">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')" class="flex items-center gap-2">
                                    <span class="text-2xl">🎓</span>
                                    <span class="text-xl font-bold bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">
                                        LearnQuest
                                    </span>
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-1 sm:-my-px sm:ms-8 sm:flex">
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    🏠 ダッシュボード
                                </NavLink>
                                <NavLink
                                    :href="route('courses.index')"
                                    :active="route().current('courses.*')"
                                >
                                    📚 コース一覧
                                </NavLink>
                                <NavLink
                                    :href="route('badges.index')"
                                    :active="route().current('badges.*')"
                                >
                                    🏆 バッジ
                                </NavLink>
                                <NavLink
                                    v-if="isAdmin"
                                    :href="route('admin.dashboard')"
                                    :active="route().current('admin.*')"
                                    class="!text-amber-400/80 hover:!text-amber-400"
                                >
                                    ⚙️ 管理者
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center gap-4">
                            <!-- User Stats -->
                            <div class="flex items-center gap-3 text-sm">
                                <div class="flex items-center gap-1 px-3 py-1 rounded-full bg-gradient-to-r from-amber-500/20 to-orange-500/20 border border-amber-500/30">
                                    <span>⭐</span>
                                    <span class="text-amber-400 font-semibold">{{ user?.total_points || 0 }}</span>
                                </div>
                                <div class="flex items-center gap-1 px-3 py-1 rounded-full bg-gradient-to-r from-cyan-500/20 to-blue-500/20 border border-cyan-500/30">
                                    <span>🎖️</span>
                                    <span class="text-cyan-400 font-semibold">Lv.{{ user?.level || 1 }}</span>
                                </div>
                                <div v-if="user?.streak_days > 0" class="flex items-center gap-1 px-3 py-1 rounded-full bg-gradient-to-r from-rose-500/20 to-pink-500/20 border border-rose-500/30">
                                    <span>🔥</span>
                                    <span class="text-rose-400 font-semibold">{{ user?.streak_days }}日</span>
                                </div>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-medium text-white/80 transition hover:bg-white/20 hover:text-white focus:outline-none"
                                            >
                                                {{ user?.name }}
                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')">
                                            プロフィール
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            ログアウト
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center rounded-md p-2 text-white/60 transition hover:bg-white/10 hover:text-white focus:bg-white/10 focus:text-white focus:outline-none"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                    <div class="space-y-1 pb-3 pt-2 border-t border-white/10">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')" class="!text-white/70">
                            🏠 ダッシュボード
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('courses.index')" :active="route().current('courses.*')" class="!text-white/70">
                            📚 コース一覧
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('badges.index')" :active="route().current('badges.*')" class="!text-white/70">
                            🏆 バッジ
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="isAdmin" :href="route('admin.dashboard')" :active="route().current('admin.*')" class="!text-amber-400">
                            ⚙️ 管理者
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="border-t border-white/10 pb-1 pt-4">
                        <div class="px-4">
                            <div class="text-base font-medium text-white">{{ user?.name }}</div>
                            <div class="text-sm font-medium text-white/60">{{ user?.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')" class="!text-white/70">
                                プロフィール
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="!text-white/70">
                                ログアウト
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-black/20 backdrop-blur-sm border-b border-white/5">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

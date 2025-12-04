<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 relative overflow-hidden">
        <!-- Background decorations -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-cyan-500/20 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-pink-500/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 w-full max-w-md px-4">
            <!-- Logo -->
            <div class="text-center mb-8">
                <Link href="/" class="inline-flex items-center gap-3 group">
                    <span class="text-5xl transition-transform group-hover:scale-110">🎓</span>
                    <span class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">
                        LearnQuest
                    </span>
                </Link>
                <p class="mt-2 text-white/60">学びの冒険を始めよう</p>
            </div>

            <!-- Card -->
            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-8 shadow-2xl">
                <slot />
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center text-white/40 text-sm">
                © 2024 LearnQuest. All rights reserved.
            </div>
        </div>
    </div>
</template>

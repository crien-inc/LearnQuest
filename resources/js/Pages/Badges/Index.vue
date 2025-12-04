<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    badges: Array,
    earnedCount: Number,
    totalCount: Number,
});
</script>

<template>
    <Head title="バッジ一覧" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-white mb-2">🏆 バッジコレクション</h1>
                    <p class="text-white/60">学習を続けてバッジを集めよう！</p>
                </div>

                <!-- Progress -->
                <div class="rounded-2xl bg-gradient-to-r from-amber-500/20 to-orange-500/20 border border-amber-500/30 p-6 mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-xl font-bold text-white">コレクション進捗</h2>
                            <p class="text-white/60 text-sm">すべてのバッジを獲得しよう</p>
                        </div>
                        <div class="text-right">
                            <div class="text-4xl font-bold text-amber-400">{{ earnedCount }} / {{ totalCount }}</div>
                            <div class="text-white/60 text-sm">獲得済み</div>
                        </div>
                    </div>
                    <div class="h-3 bg-white/10 rounded-full overflow-hidden">
                        <div 
                            class="h-full bg-gradient-to-r from-amber-400 to-orange-400 transition-all duration-500 rounded-full"
                            :style="{ width: (totalCount > 0 ? (earnedCount / totalCount) * 100 : 0) + '%' }"
                        ></div>
                    </div>
                </div>

                <!-- Badges Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <div
                        v-for="badge in badges"
                        :key="badge.id"
                        class="group relative"
                    >
                        <div 
                            class="rounded-2xl border p-6 text-center transition-all duration-300"
                            :class="badge.earned 
                                ? 'bg-white/10 border-white/20 hover:bg-white/15' 
                                : 'bg-white/5 border-white/10 opacity-50 grayscale'"
                        >
                            <!-- Badge Icon -->
                            <div 
                                class="w-24 h-24 mx-auto rounded-2xl flex items-center justify-center text-5xl mb-4 transition-transform duration-300 group-hover:scale-110"
                                :style="{ 
                                    backgroundColor: badge.earned ? badge.color + '30' : 'rgba(255,255,255,0.1)',
                                    boxShadow: badge.earned ? `0 0 30px ${badge.color}40` : 'none'
                                }"
                            >
                                {{ badge.icon }}
                            </div>

                            <!-- Badge Info -->
                            <h3 class="font-bold text-white mb-1">{{ badge.name }}</h3>
                            <p class="text-sm text-white/60 mb-3 line-clamp-2">{{ badge.description }}</p>
                            
                            <!-- Type Badge -->
                            <span 
                                class="inline-block px-3 py-1 rounded-full text-xs font-medium"
                                :style="{ 
                                    backgroundColor: badge.earned ? badge.color + '20' : 'rgba(255,255,255,0.1)',
                                    color: badge.earned ? badge.color : 'rgba(255,255,255,0.5)'
                                }"
                            >
                                {{ badge.type_label }}
                            </span>

                            <!-- Earned Date -->
                            <div v-if="badge.earned && badge.earned_at" class="mt-3 pt-3 border-t border-white/10">
                                <span class="text-xs text-white/40">
                                    {{ new Date(badge.earned_at).toLocaleDateString('ja-JP') }} 獲得
                                </span>
                            </div>
                        </div>

                        <!-- Lock Icon for Unearned -->
                        <div 
                            v-if="!badge.earned"
                            class="absolute inset-0 flex items-center justify-center pointer-events-none"
                        >
                            <div class="w-12 h-12 rounded-full bg-black/50 flex items-center justify-center text-2xl">
                                🔒
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="badges.length === 0" class="text-center py-16">
                    <div class="text-8xl mb-6">🏆</div>
                    <h2 class="text-2xl font-bold text-white mb-2">まだバッジがありません</h2>
                    <p class="text-white/60">管理者がバッジを追加するとここに表示されます</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


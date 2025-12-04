<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    courses: Array,
    recentProgress: Array,
    userBadges: Array,
    availableBadges: Array,
    stats: Object,
});

const getLevelProgress = () => {
    const levelThresholds = [0, 100, 300, 600, 1000, 1500, 2100, 2800, 3600, 4500, 9999999];
    const currentLevel = props.stats.level;
    const currentPoints = props.stats.totalPoints;
    const currentThreshold = levelThresholds[currentLevel - 1];
    const nextThreshold = levelThresholds[currentLevel];
    const progress = ((currentPoints - currentThreshold) / (nextThreshold - currentThreshold)) * 100;
    return Math.min(100, Math.max(0, progress));
};
</script>

<template>
    <Head title="ダッシュボード" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-white mb-2">
                        おかえりなさい！ 🎉
                    </h1>
                    <p class="text-white/60">今日も学習を続けましょう</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500/20 to-orange-600/20 border border-amber-500/30 p-6">
                        <div class="text-4xl mb-2">⭐</div>
                        <div class="text-3xl font-bold text-amber-400">{{ stats.totalPoints }}</div>
                        <div class="text-sm text-white/60">総ポイント</div>
                        <div class="absolute -right-4 -top-4 text-8xl opacity-10">⭐</div>
                    </div>
                    
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-cyan-500/20 to-blue-600/20 border border-cyan-500/30 p-6">
                        <div class="text-4xl mb-2">🎖️</div>
                        <div class="text-3xl font-bold text-cyan-400">Lv.{{ stats.level }}</div>
                        <div class="text-sm text-white/60">現在のレベル</div>
                        <div class="mt-2 h-2 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-cyan-400 to-blue-400 transition-all duration-500" :style="{ width: getLevelProgress() + '%' }"></div>
                        </div>
                        <div class="absolute -right-4 -top-4 text-8xl opacity-10">🎖️</div>
                    </div>
                    
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500/20 to-pink-600/20 border border-rose-500/30 p-6">
                        <div class="text-4xl mb-2">🔥</div>
                        <div class="text-3xl font-bold text-rose-400">{{ stats.streakDays }}日</div>
                        <div class="text-sm text-white/60">連続学習</div>
                        <div class="absolute -right-4 -top-4 text-8xl opacity-10">🔥</div>
                    </div>
                    
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500/20 to-green-600/20 border border-emerald-500/30 p-6">
                        <div class="text-4xl mb-2">✅</div>
                        <div class="text-3xl font-bold text-emerald-400">{{ stats.completedLessons }}</div>
                        <div class="text-sm text-white/60">完了レッスン</div>
                        <div class="absolute -right-4 -top-4 text-8xl opacity-10">✅</div>
                    </div>
                </div>

                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Courses Progress -->
                    <div class="lg:col-span-2">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-white">📚 学習中のコース</h2>
                            <Link :href="route('courses.index')" class="text-sm text-cyan-400 hover:text-cyan-300 transition">
                                すべて見る →
                            </Link>
                        </div>
                        
                        <div class="space-y-4">
                            <Link
                                v-for="course in courses"
                                :key="course.id"
                                :href="route('courses.show', course.id)"
                                class="block group"
                            >
                                <div class="rounded-2xl bg-white/5 border border-white/10 p-6 transition hover:bg-white/10 hover:border-white/20">
                                    <div class="flex items-start gap-4">
                                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-2xl shrink-0">
                                            📖
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-white group-hover:text-cyan-400 transition truncate">
                                                {{ course.title }}
                                            </h3>
                                            <p class="text-sm text-white/60 mt-1 line-clamp-2">{{ course.description }}</p>
                                            <div class="flex items-center gap-4 mt-3">
                                                <span class="text-xs text-white/40">{{ course.lessons_count }}レッスン</span>
                                                <span class="text-xs text-amber-400">+{{ course.points_reward }}pt</span>
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <div class="text-2xl font-bold" :class="course.progress === 100 ? 'text-emerald-400' : 'text-cyan-400'">
                                                {{ course.progress }}%
                                            </div>
                                            <div class="w-24 h-2 bg-white/10 rounded-full overflow-hidden mt-2">
                                                <div 
                                                    class="h-full rounded-full transition-all duration-500"
                                                    :class="course.progress === 100 ? 'bg-gradient-to-r from-emerald-400 to-green-400' : 'bg-gradient-to-r from-cyan-400 to-purple-400'"
                                                    :style="{ width: course.progress + '%' }"
                                                ></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </Link>
                            
                            <div v-if="courses.length === 0" class="rounded-2xl bg-white/5 border border-white/10 p-12 text-center">
                                <div class="text-6xl mb-4">📚</div>
                                <h3 class="text-lg font-semibold text-white mb-2">まだコースがありません</h3>
                                <p class="text-white/60 mb-4">管理者がコースを追加するとここに表示されます</p>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Earned Badges -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-xl font-bold text-white">🏆 獲得バッジ</h2>
                                <Link :href="route('badges.index')" class="text-sm text-cyan-400 hover:text-cyan-300 transition">
                                    すべて見る →
                                </Link>
                            </div>
                            
                            <div class="rounded-2xl bg-white/5 border border-white/10 p-6">
                                <div v-if="userBadges.length > 0" class="grid grid-cols-3 gap-3">
                                    <div 
                                        v-for="badge in userBadges" 
                                        :key="badge.id"
                                        class="aspect-square rounded-xl flex items-center justify-center text-3xl transition hover:scale-110 cursor-pointer"
                                        :style="{ backgroundColor: badge.color + '30', borderColor: badge.color + '50' }"
                                        :title="badge.name"
                                    >
                                        {{ badge.icon }}
                                    </div>
                                </div>
                                <div v-else class="text-center py-4">
                                    <div class="text-4xl mb-2 opacity-50">🎯</div>
                                    <p class="text-sm text-white/40">まだバッジを獲得していません</p>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-white/10 text-center">
                                    <span class="text-2xl font-bold text-white">{{ stats.earnedBadges }}</span>
                                    <span class="text-white/40 text-sm ml-1">個獲得</span>
                                </div>
                            </div>
                        </div>

                        <!-- Available Badges -->
                        <div v-if="availableBadges.length > 0">
                            <h2 class="text-xl font-bold text-white mb-4">🎯 次に獲得できるバッジ</h2>
                            <div class="space-y-3">
                                <div 
                                    v-for="badge in availableBadges" 
                                    :key="badge.id"
                                    class="rounded-xl bg-white/5 border border-white/10 p-4 flex items-center gap-3"
                                >
                                    <div 
                                        class="w-12 h-12 rounded-lg flex items-center justify-center text-2xl opacity-50 grayscale"
                                        :style="{ backgroundColor: badge.color + '20' }"
                                    >
                                        {{ badge.icon }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-white/80 truncate">{{ badge.name }}</h4>
                                        <p class="text-xs text-white/40 truncate">{{ badge.description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div v-if="recentProgress.length > 0">
                            <h2 class="text-xl font-bold text-white mb-4">📝 最近の活動</h2>
                            <div class="rounded-2xl bg-white/5 border border-white/10 divide-y divide-white/5">
                                <div 
                                    v-for="progress in recentProgress" 
                                    :key="progress.id"
                                    class="p-4 flex items-center gap-3"
                                >
                                    <div class="w-10 h-10 rounded-lg bg-emerald-500/20 flex items-center justify-center text-lg">
                                        ✅
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-white/80 truncate text-sm">{{ progress.lesson?.title }}</h4>
                                        <p class="text-xs text-white/40">{{ progress.lesson?.course?.title }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    course: Object,
    lessons: Array,
    courseProgress: Number,
});

const getStatusIcon = (status) => {
    switch (status) {
        case 'completed': return '✅';
        case 'in_progress': return '▶️';
        default: return '⬜';
    }
};

const getTypeIcon = (type) => {
    switch (type) {
        case 'video': return '🎬';
        case 'text': return '📄';
        case 'quiz': return '❓';
        case 'assignment': return '📝';
        default: return '📚';
    }
};
</script>

<template>
    <Head :title="course.title" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Back Link -->
                <Link :href="route('courses.index')" class="inline-flex items-center text-white/60 hover:text-white transition mb-6">
                    ← コース一覧に戻る
                </Link>

                <!-- Course Header -->
                <div class="rounded-2xl bg-white/5 border border-white/10 overflow-hidden mb-8">
                    <div class="md:flex">
                        <!-- Thumbnail -->
                        <div class="md:w-1/3 aspect-video md:aspect-auto bg-gradient-to-br from-purple-600/50 to-pink-600/50">
                            <img 
                                v-if="course.thumbnail" 
                                :src="`/storage/${course.thumbnail}`" 
                                :alt="course.title"
                                class="w-full h-full object-cover"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center text-8xl">
                                📖
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="md:w-2/3 p-8">
                            <h1 class="text-3xl font-bold text-white mb-4">{{ course.title }}</h1>
                            <p class="text-white/70 mb-6">{{ course.description }}</p>

                            <!-- Stats -->
                            <div class="flex flex-wrap gap-4 mb-6">
                                <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/10">
                                    <span>📹</span>
                                    <span class="text-white">{{ lessons.length }}レッスン</span>
                                </div>
                                <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500/20 text-amber-400">
                                    <span>⭐</span>
                                    <span>+{{ course.points_reward }}pt（完了時）</span>
                                </div>
                            </div>

                            <!-- Progress -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-white/60">進捗</span>
                                    <span class="text-2xl font-bold" :class="courseProgress === 100 ? 'text-emerald-400' : 'text-cyan-400'">
                                        {{ courseProgress }}%
                                    </span>
                                </div>
                                <div class="h-3 bg-white/10 rounded-full overflow-hidden">
                                    <div 
                                        class="h-full rounded-full transition-all duration-500"
                                        :class="courseProgress === 100 ? 'bg-gradient-to-r from-emerald-400 to-green-400' : 'bg-gradient-to-r from-cyan-400 to-purple-400'"
                                        :style="{ width: courseProgress + '%' }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lessons List -->
                <div>
                    <h2 class="text-2xl font-bold text-white mb-6">📋 レッスン一覧</h2>
                    
                    <div class="space-y-3">
                        <Link
                            v-for="(lesson, index) in lessons"
                            :key="lesson.id"
                            :href="route('courses.lessons.show', [course.id, lesson.id])"
                            class="group block"
                        >
                            <div class="rounded-xl bg-white/5 border border-white/10 p-5 flex items-center gap-4 transition-all hover:bg-white/10 hover:border-white/20">
                                <!-- Status Icon -->
                                <div 
                                    class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl shrink-0"
                                    :class="{
                                        'bg-emerald-500/20': lesson.user_progress?.status === 'completed',
                                        'bg-cyan-500/20': lesson.user_progress?.status === 'in_progress',
                                        'bg-white/10': !lesson.user_progress || lesson.user_progress?.status === 'not_started'
                                    }"
                                >
                                    {{ getStatusIcon(lesson.user_progress?.status) }}
                                </div>

                                <!-- Lesson Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-white/40 text-sm">{{ index + 1 }}.</span>
                                        <span class="text-white/60 text-sm">{{ getTypeIcon(lesson.type) }}</span>
                                        <h3 class="font-semibold text-white group-hover:text-cyan-400 transition truncate">
                                            {{ lesson.title }}
                                        </h3>
                                    </div>
                                    <p v-if="lesson.description" class="text-sm text-white/50 truncate">
                                        {{ lesson.description }}
                                    </p>
                                </div>

                                <!-- Meta -->
                                <div class="flex items-center gap-4 shrink-0 text-sm">
                                    <span v-if="lesson.duration_minutes" class="text-white/40">
                                        ⏱️ {{ lesson.duration_minutes }}分
                                    </span>
                                    <span class="text-amber-400">+{{ lesson.points_reward }}pt</span>
                                    <span class="text-white/40 group-hover:text-white transition">→</span>
                                </div>
                            </div>
                        </Link>
                    </div>

                    <!-- Empty State -->
                    <div v-if="lessons.length === 0" class="text-center py-16 rounded-2xl bg-white/5 border border-white/10">
                        <div class="text-6xl mb-4">📭</div>
                        <h3 class="text-lg font-semibold text-white mb-2">まだレッスンがありません</h3>
                        <p class="text-white/60">管理者がレッスンを追加するとここに表示されます</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


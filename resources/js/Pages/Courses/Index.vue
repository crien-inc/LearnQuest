<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    courses: Array,
});
</script>

<template>
    <Head title="コース一覧" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-white mb-2">📚 コース一覧</h1>
                    <p class="text-white/60">あなたの学習の旅を始めましょう</p>
                </div>

                <!-- Courses Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link
                        v-for="course in courses"
                        :key="course.id"
                        :href="route('courses.show', course.id)"
                        class="group"
                    >
                        <div class="h-full rounded-2xl bg-white/5 border border-white/10 overflow-hidden transition-all duration-300 hover:bg-white/10 hover:border-white/20 hover:scale-[1.02] hover:shadow-xl hover:shadow-purple-500/10">
                            <!-- Thumbnail -->
                            <div class="aspect-video bg-gradient-to-br from-purple-600/50 to-pink-600/50 relative overflow-hidden">
                                <img 
                                    v-if="course.thumbnail" 
                                    :src="`/storage/${course.thumbnail}`" 
                                    :alt="course.title"
                                    class="w-full h-full object-cover"
                                />
                                <div v-else class="w-full h-full flex items-center justify-center text-6xl">
                                    📖
                                </div>
                                
                                <!-- Progress Badge -->
                                <div class="absolute top-3 right-3">
                                    <div 
                                        v-if="course.progress === 100"
                                        class="px-3 py-1 rounded-full bg-emerald-500 text-white text-xs font-bold"
                                    >
                                        ✅ 完了
                                    </div>
                                    <div 
                                        v-else-if="course.progress > 0"
                                        class="px-3 py-1 rounded-full bg-cyan-500 text-white text-xs font-bold"
                                    >
                                        {{ course.progress }}% 進行中
                                    </div>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-white group-hover:text-cyan-400 transition mb-2 line-clamp-2">
                                    {{ course.title }}
                                </h3>
                                <p class="text-sm text-white/60 line-clamp-3 mb-4">
                                    {{ course.description }}
                                </p>

                                <!-- Meta -->
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-4 text-white/40">
                                        <span>📹 {{ course.lessons_count }}レッスン</span>
                                        <span v-if="course.formatted_duration">⏱️ {{ course.formatted_duration }}</span>
                                    </div>
                                    <div class="text-amber-400 font-semibold">
                                        +{{ course.points_reward }}pt
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mt-4 h-2 bg-white/10 rounded-full overflow-hidden">
                                    <div 
                                        class="h-full rounded-full transition-all duration-500"
                                        :class="course.progress === 100 ? 'bg-gradient-to-r from-emerald-400 to-green-400' : 'bg-gradient-to-r from-cyan-400 to-purple-400'"
                                        :style="{ width: course.progress + '%' }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Empty State -->
                <div v-if="courses.length === 0" class="text-center py-16">
                    <div class="text-8xl mb-6">📚</div>
                    <h2 class="text-2xl font-bold text-white mb-2">まだコースがありません</h2>
                    <p class="text-white/60">管理者がコースを追加するとここに表示されます</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


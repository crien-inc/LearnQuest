<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    course: Object,
    lesson: Object,
    progress: Object,
    prevLesson: Object,
    nextLesson: Object,
    lessonNumber: Number,
    totalLessons: Number,
});

const submissionText = ref(props.progress?.submission || '');
const isSubmitting = ref(false);

const isCompleted = computed(() => props.progress?.status === 'completed');

const completeForm = useForm({});
const submitForm = useForm({
    submission: '',
});

const completeLesson = () => {
    completeForm.post(route('courses.lessons.complete', [props.course.id, props.lesson.id]));
};

const submitAssignment = () => {
    submitForm.submission = submissionText.value;
    submitForm.post(route('courses.lessons.submit', [props.course.id, props.lesson.id]), {
        preserveScroll: true,
    });
};

const getTypeLabel = (type) => {
    switch (type) {
        case 'video': return '🎬 動画レッスン';
        case 'text': return '📄 テキストレッスン';
        case 'quiz': return '❓ クイズ';
        case 'assignment': return '📝 課題';
        default: return '📚 レッスン';
    }
};

// 動画URLを埋め込み用URLに変換（YouTube / Google Drive対応）
const embedVideoUrl = computed(() => {
    const url = props.lesson.video_url;
    if (!url) return null;
    
    // === YouTube ===
    // すでに埋め込み用URLの場合はそのまま返す
    if (url.includes('youtube.com/embed/')) {
        return url;
    }
    
    // 通常のYouTube URL (youtube.com/watch?v=VIDEO_ID)
    const watchMatch = url.match(/(?:youtube\.com\/watch\?v=|youtube\.com\/watch\?.*&v=)([a-zA-Z0-9_-]{11})/);
    if (watchMatch) {
        return `https://www.youtube.com/embed/${watchMatch[1]}`;
    }
    
    // 短縮URL (youtu.be/VIDEO_ID)
    const shortMatch = url.match(/youtu\.be\/([a-zA-Z0-9_-]{11})/);
    if (shortMatch) {
        return `https://www.youtube.com/embed/${shortMatch[1]}`;
    }
    
    // === Google Drive ===
    // すでにプレビュー用URLの場合はそのまま返す
    if (url.includes('drive.google.com/file/d/') && url.includes('/preview')) {
        return url;
    }
    
    // Google Drive共有URL (drive.google.com/file/d/FILE_ID/view)
    const driveMatch = url.match(/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/);
    if (driveMatch) {
        return `https://drive.google.com/file/d/${driveMatch[1]}/preview`;
    }
    
    // Google Drive別形式 (drive.google.com/open?id=FILE_ID)
    const driveOpenMatch = url.match(/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/);
    if (driveOpenMatch) {
        return `https://drive.google.com/file/d/${driveOpenMatch[1]}/preview`;
    }
    
    // その他のURLはそのまま返す
    return url;
});
</script>

<template>
    <Head :title="lesson.title" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-sm text-white/60 mb-6">
                    <Link :href="route('courses.index')" class="hover:text-white transition">コース一覧</Link>
                    <span>›</span>
                    <Link :href="route('courses.show', course.id)" class="hover:text-white transition">{{ course.title }}</Link>
                    <span>›</span>
                    <span class="text-white">{{ lesson.title }}</span>
                </div>

                <!-- Lesson Header -->
                <div class="rounded-2xl bg-white/5 border border-white/10 p-6 mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-3 py-1 rounded-full bg-white/10 text-white/70 text-sm">
                                    {{ getTypeLabel(lesson.type) }}
                                </span>
                                <span class="text-white/40 text-sm">
                                    {{ lessonNumber }} / {{ totalLessons }}
                                </span>
                            </div>
                            <h1 class="text-2xl font-bold text-white">{{ lesson.title }}</h1>
                        </div>
                        
                        <div v-if="isCompleted" class="px-4 py-2 rounded-full bg-emerald-500/20 text-emerald-400 font-semibold flex items-center gap-2">
                            <span>✅</span>
                            <span>完了済み</span>
                        </div>
                    </div>
                    
                    <p v-if="lesson.description" class="text-white/70">{{ lesson.description }}</p>
                    
                    <div class="flex items-center gap-4 mt-4 text-sm">
                        <span v-if="lesson.duration_minutes" class="text-white/40">⏱️ {{ lesson.duration_minutes }}分</span>
                        <span class="text-amber-400">⭐ +{{ lesson.points_reward }}pt</span>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="rounded-2xl bg-white/5 border border-white/10 overflow-hidden mb-6">
                    <!-- Video Content -->
                    <div v-if="lesson.type === 'video' && embedVideoUrl" class="aspect-video bg-black">
                        <iframe 
                            :src="embedVideoUrl" 
                            class="w-full h-full" 
                            allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        ></iframe>
                    </div>
                    
                    <!-- Text/Quiz Content -->
                    <div v-if="lesson.content" class="p-8 prose prose-invert max-w-none">
                        <div v-html="lesson.content"></div>
                    </div>

                    <!-- Assignment Submission -->
                    <div v-if="lesson.type === 'assignment'" class="p-8 border-t border-white/10">
                        <h3 class="text-lg font-semibold text-white mb-4">📝 課題提出</h3>
                        <textarea
                            v-model="submissionText"
                            class="w-full h-48 rounded-xl bg-white/5 border border-white/20 p-4 text-white placeholder-white/40 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 resize-none"
                            placeholder="ここに課題の回答を入力してください..."
                            :disabled="submitForm.processing"
                        ></textarea>
                        <div class="flex justify-end mt-4">
                            <button
                                @click="submitAssignment"
                                :disabled="submitForm.processing || !submissionText.trim()"
                                class="px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-purple-500 text-white font-semibold transition hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {{ submitForm.processing ? '送信中...' : '課題を提出する' }}
                            </button>
                        </div>
                    </div>

                    <!-- Placeholder for empty content -->
                    <div v-if="!lesson.video_url && !lesson.content && lesson.type !== 'assignment'" class="p-16 text-center">
                        <div class="text-6xl mb-4">📭</div>
                        <p class="text-white/60">このレッスンにはまだコンテンツがありません</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between">
                    <!-- Navigation -->
                    <div class="flex items-center gap-3">
                        <Link
                            v-if="prevLesson"
                            :href="route('courses.lessons.show', [course.id, prevLesson.id])"
                            class="px-4 py-2 rounded-xl bg-white/10 text-white/70 hover:bg-white/20 hover:text-white transition"
                        >
                            ← 前のレッスン
                        </Link>
                        <Link
                            v-else
                            :href="route('courses.show', course.id)"
                            class="px-4 py-2 rounded-xl bg-white/10 text-white/70 hover:bg-white/20 hover:text-white transition"
                        >
                            ← コースに戻る
                        </Link>
                    </div>

                    <!-- Complete Button -->
                    <div class="flex items-center gap-3">
                        <button
                            v-if="!isCompleted"
                            @click="completeLesson"
                            :disabled="completeForm.processing"
                            class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-green-500 text-white font-semibold transition hover:opacity-90 disabled:opacity-50 flex items-center gap-2"
                        >
                            <span>✅</span>
                            <span>{{ completeForm.processing ? '処理中...' : 'レッスン完了' }}</span>
                        </button>

                        <Link
                            v-if="nextLesson"
                            :href="route('courses.lessons.show', [course.id, nextLesson.id])"
                            class="px-4 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-purple-500 text-white font-semibold hover:opacity-90 transition"
                        >
                            次のレッスン →
                        </Link>
                        <Link
                            v-else
                            :href="route('courses.show', course.id)"
                            class="px-4 py-2 rounded-xl bg-white/10 text-white/70 hover:bg-white/20 hover:text-white transition"
                        >
                            コースに戻る →
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


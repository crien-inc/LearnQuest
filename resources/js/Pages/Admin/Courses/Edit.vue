<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    course: Object,
});

const form = useForm({
    title: props.course.title,
    description: props.course.description,
    thumbnail: null,
    order: props.course.order,
    points_reward: props.course.points_reward,
    is_published: props.course.is_published,
});

const submit = () => {
    form.post(route('admin.courses.update', props.course.id), {
        _method: 'put',
        forceFormData: true,
    });
};

const handleFileChange = (e) => {
    form.thumbnail = e.target.files[0];
};
</script>

<template>
    <Head title="コース編集" />

    <AdminLayout>
        <template #title>コース編集</template>

        <div class="max-w-2xl">
            <Link :href="route('admin.courses.index')" class="inline-flex items-center text-slate-400 hover:text-white mb-6">
                ← コース一覧に戻る
            </Link>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="rounded-xl bg-slate-800 border border-slate-700 p-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">コース名 *</label>
                        <input
                            v-model="form.title"
                            type="text"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white placeholder-slate-500 focus:border-cyan-500 focus:ring-cyan-500"
                            required
                        />
                        <p v-if="form.errors.title" class="mt-1 text-sm text-red-400">{{ form.errors.title }}</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">説明 *</label>
                        <textarea
                            v-model="form.description"
                            rows="4"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white placeholder-slate-500 focus:border-cyan-500 focus:ring-cyan-500"
                            required
                        ></textarea>
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-400">{{ form.errors.description }}</p>
                    </div>

                    <!-- Current Thumbnail -->
                    <div v-if="course.thumbnail">
                        <label class="block text-sm font-medium text-slate-300 mb-2">現在のサムネイル</label>
                        <img :src="`/storage/${course.thumbnail}`" class="w-32 h-20 object-cover rounded-lg" />
                    </div>

                    <!-- Thumbnail -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">新しいサムネイル画像</label>
                        <input
                            type="file"
                            @change="handleFileChange"
                            accept="image/*"
                            class="w-full text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-slate-700 file:text-white hover:file:bg-slate-600"
                        />
                        <p v-if="form.errors.thumbnail" class="mt-1 text-sm text-red-400">{{ form.errors.thumbnail }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <!-- Order -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">表示順序</label>
                            <input
                                v-model="form.order"
                                type="number"
                                min="0"
                                class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                            />
                        </div>

                        <!-- Points -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">完了時ポイント</label>
                            <input
                                v-model="form.points_reward"
                                type="number"
                                min="0"
                                class="w-full rounded-lg bg-slate-900 border-slate-600 text-white focus:border-cyan-500 focus:ring-cyan-500"
                            />
                        </div>
                    </div>

                    <!-- Published -->
                    <div class="flex items-center gap-3">
                        <input
                            v-model="form.is_published"
                            type="checkbox"
                            id="is_published"
                            class="rounded bg-slate-900 border-slate-600 text-cyan-500 focus:ring-cyan-500"
                        />
                        <label for="is_published" class="text-sm text-slate-300">公開する</label>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex justify-end gap-4">
                    <Link
                        :href="route('admin.courses.index')"
                        class="px-6 py-3 rounded-lg bg-slate-700 text-white hover:bg-slate-600 transition"
                    >
                        キャンセル
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-3 rounded-lg bg-cyan-500 text-white font-medium hover:bg-cyan-600 transition disabled:opacity-50"
                    >
                        {{ form.processing ? '更新中...' : '変更を保存' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>


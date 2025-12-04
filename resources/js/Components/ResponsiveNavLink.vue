<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full ps-3 pe-4 py-2 text-start text-base font-medium text-white bg-white/10 rounded-lg focus:outline-none transition duration-150 ease-in-out'
        : 'block w-full ps-3 pe-4 py-2 text-start text-base font-medium text-white/70 hover:text-white hover:bg-white/5 rounded-lg focus:outline-none transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

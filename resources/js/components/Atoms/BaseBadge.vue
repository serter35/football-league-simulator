<script lang="ts" setup>
defineProps({
    variant: { type: String, default: 'default' }, // default, success, warning
});

const variants = {
    default: 'bg-slate-100 text-slate-700',
    success: 'bg-emerald-100 text-emerald-700',
    warning: 'bg-amber-100 text-amber-700',
    danger:  'bg-red-100 text-red-700',
};
</script>

<template>
    <span :class="['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium', variants[variant]]">
        <slot />
    </span>
</template>

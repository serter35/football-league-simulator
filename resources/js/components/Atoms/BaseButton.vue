<script lang="ts" setup>
defineProps({
    type: { type: String, default: 'button' },
    variant: { type: String, default: 'primary' }, // primary, secondary, ghost, success
    disabled: { type: Boolean, default: false },
});

const variants = {
    primary: 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm',
    secondary: 'bg-slate-800 text-white hover:bg-slate-900',
    ghost: 'bg-transparent border border-slate-200 text-slate-600 hover:bg-slate-50',
    success: 'bg-emerald-600 text-white hover:bg-emerald-700',
};
</script>

<template>
    <button
        :type="type"
        :disabled="disabled"
        :class="[
            'inline-flex items-center justify-center rounded-md px-4 py-2 text-sm font-semibold transition-all duration-200 disabled:cursor-not-allowed disabled:opacity-50',
            variants[variant],
        ]"
    >
        <slot />
    </button>
</template>

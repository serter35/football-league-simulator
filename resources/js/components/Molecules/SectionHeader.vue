<script lang="ts" setup>
defineProps({
    title: String,
    description: String,
});
</script>

<template>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-lg leading-none font-bold text-slate-900">{{ title }}</h3>
            <p v-if="description" class="mt-1 text-sm text-slate-500">{{ description }}</p>
        </div>
        <div class="flex gap-2">
            <slot name="actions" />
        </div>
    </div>
</template>

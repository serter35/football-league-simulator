<script lang="ts" setup>
defineProps({
    value: { type: Number, default: 0 },
    max: { type: Number, default: 100 },
});
</script>

<template>
    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
        <div class="h-full bg-indigo-600 transition-all duration-1000 ease-out" :style="{ width: `${(value / max) * 100}%` }"></div>
    </div>
</template>

<script lang="ts" setup>
const model = defineModel({ type: [String, Number] });

defineProps({
    type: { type: String, default: 'text' },
    placeholder: { type: String, default: '' },
});
</script>

<template>
    <input
        v-model="model"
        :type="type"
        :placeholder="placeholder"
        class="block w-full rounded-md border-slate-300 text-center font-bold shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
    />
</template>

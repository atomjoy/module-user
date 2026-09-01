<script setup>
defineProps({
    name: String
})
</script>

<template>
    <div class="profile-container">
        <h1>Komponent Vue z modułu User</h1>
        <p>Witaj, {{ name }}! Ten widok renderuje się przez Inertia.js.</p>
    </div>
</template>

<style scoped>
.profile-container { padding: 20px; font-family: sans-serif; }
</style>

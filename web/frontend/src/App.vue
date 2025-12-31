<template>
  <div id="app" class="min-h-screen">
    <header class="p-4 border-b">
      <h1 class="text-xl font-bold">NETES</h1>
    </header>

    <main class="p-6">
      <pre class="bg-black text-green-400 p-4 rounded">
{{ status }}
      </pre>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const status = ref('loading...')

onMounted(async () => {
  const res = await fetch('https://netes.onrender.com/api/status')
  status.value = JSON.stringify(await res.json(), null, 2)
})
</script>

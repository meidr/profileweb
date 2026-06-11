<template>
  <div class="flex h-screen">
    <aside class="bg-surface-container-low h-screen w-64 fixed left-0 top-0 hidden md:flex flex-col border-r border-subtle">
      <div class="p-6">
        <div class="flex items-center gap-3 mb-2">
          <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center">
            <span class="material-symbols-outlined text-on-primary">school</span>
          </div>
          <div>
            <h1 class="font-headline-sm text-headline-sm text-primary">Admin Central</h1>
          </div>
        </div>
      </div>
      <nav class="flex flex-col h-full py-2 space-y-2">
        <router-link to="/" class="text-on-secondary-container hover:bg-surface-container-high mx-2 my-1 rounded-lg flex items-center gap-3 px-4 h-touch-target transition-all">
          <span class="material-symbols-outlined">dashboard</span> Dashboard
        </router-link>
        <router-link to="/support" class="bg-primary-container text-on-primary-container mx-2 my-1 rounded-lg flex items-center gap-3 px-4 h-touch-target">
          <span class="material-symbols-outlined">help</span> Support
        </router-link>
      </nav>
    </aside>

    <main class="flex-1 flex flex-col md:ml-64 w-full h-screen overflow-hidden">
      <header class="bg-surface w-full top-0 sticky border-b border-subtle flex items-center px-8 h-touch-target">
        <h2 class="text-headline-md font-bold text-primary">Support Center</h2>
      </header>
      <div class="p-8">
        <p class="text-on-surface-variant">Contact IT Support here.</p>
      </div>
    </main>
  </div>
</template>
<script setup></script>

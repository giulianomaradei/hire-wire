<template>
  <div class="flex justify-center items-center h-screen w-full p-2">
    <NuxtLayout>
      <div class="flex justify-center items-center h-screen max-w-screen-lg">
        <NuxtPage v-if="canShowPage" />
      </div>
    </NuxtLayout>
  </div>
</template>

<script setup>
const router = useRouter();

const canShowPage = ref(false);

if (import.meta.client) {
  const token = localStorage.getItem("token");

  if (token !== null && token !== undefined) {
    router.push("/").then(() => {
      canShowPage.value = true;
    });
  } else {
    router.push("/login").then(() => {
      canShowPage.value = true;
    });
  }
}
</script>

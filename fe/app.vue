<template>
  <div>
    <NuxtLayout>
      <NuxtPage v-if="canShowPage" />
    </NuxtLayout>
  </div>
</template>

<script setup>
const router = useRouter();

const canShowPage = ref(false);

if (import.meta.client) {
  const token = localStorage.getItem("token");

  if (token) {
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

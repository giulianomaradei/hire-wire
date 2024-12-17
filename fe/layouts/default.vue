<template>
  <div>
    <slot />
  </div>
</template>

<script setup>
const router = useRouter();

if (import.meta.client) {
  const token = localStorage.getItem("token");

  if (!token) {
    router.push("/login");
  }
}
</script>

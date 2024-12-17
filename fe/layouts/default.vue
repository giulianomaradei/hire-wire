<template>
  <div>
    <slot />
  </div>
</template>

<script setup>
const router = useRouter();

if (import.meta.client) {
  const token = localStorage.getItem("token");

  console.log(token);

  if (token === null || token === undefined) {
    router.push("/login");
  }
}
</script>

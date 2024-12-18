<template>
  <div>
    <slot />
  </div>
  <div class="fixed top-0 right-0 m-4">
    <button @click="logout" class="hover:text-red-500 hover:underline">
      Logout
    </button>
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

const logout = () => {
  localStorage.removeItem("token");
  router.push("/login");
};
</script>

<template>
  <div>
    <slot />
  </div>
  <div class="fixed top-0 right-0 m-4">
    <button
      v-if="user"
      @click="logout"
      class="hover:text-red-500 hover:underline"
    >
      Logout
    </button>
  </div>
</template>

<script setup>
import { useStore } from "~/stores/index";

const store = useStore();
const router = useRouter();

const user = computed(() => store.user);

if (import.meta.client) {
  const token = localStorage.getItem("token");

  console.log(token);

  if (token === null || token === undefined) {
    router.push("/login");
  }
}

const logout = () => {
  localStorage.removeItem("token");
  store.setUser(null);
  router.push("/login");
};
</script>

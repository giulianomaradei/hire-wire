/// pinia store
import { defineStore } from "pinia";

export const useStore = defineStore("store", {
  state: () => ({
    user: null,
  }),
  actions: {
    setUser(user) {
      this.user = user;
    },
  },
});

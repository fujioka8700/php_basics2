import { defineStore } from "pinia";

export const useStoreAuth = defineStore("auth", {
  state: () => ({
    user: null,
  }),

  actions: {
    async currentUser() {
      await axios
        .get("/api/user")
        .then((res) => {
          this.user = res.data;
        })
        .catch((err) => {});
    },
  },
});

<template>
  <div class="flex flex-col gap-2">
    <div v-if="user && user.accounts" class="mt-4">
      <h2 class="text-xl font-bold mb-2">Accounts</h2>
      <div class="flex flex-wrap gap-4">
        <div
          v-for="(account, index) in user.accounts"
          :key="index"
          class="p-4 mb-4 border rounded-lg shadow-md bg-white flex-1"
        >
          <p class="text-lg font-semibold">Type: {{ account.type }}</p>
          <p class="text-lg">Balance: ${{ account.balance.toFixed(2) }}</p>
          <div class="mt-2">
            <input
              type="number"
              v-model="depositAmount[index]"
              placeholder="Deposit Amount"
              class="border p-2 rounded w-full"
            />
            <button
              @click="makeDeposit(index)"
              class="mt-2 bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
            >
              Deposit
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { sendRequest } from "~/composables/global";
const store = useStore();

const user = computed(() => {
  return store.user;
});

const depositAmount = ref([]);

onMounted(async () => {
  if (user.value === null) {
    const user = await sendRequest({ url: "/user", method: "GET" });
    store.setUser(user.data);
  }
});

function makeDeposit(index) {
  const amount = depositAmount.value[index];
  if (amount > 0) {
    // Logic to handle deposit
    console.log(`Depositing $${amount} to account ${index}`);
    // Reset the input field
    depositAmount.value[index] = "";
  } else {
    alert("Please enter a valid deposit amount.");
  }
}
</script>

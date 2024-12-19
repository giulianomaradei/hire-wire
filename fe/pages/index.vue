<template>
  <div class="flex flex-col gap-2">
    <div v-if="user && user.accounts" class="mt-4">
      <h2 class="text-xl font-bold mb-2">Accounts</h2>
      <div class="flex flex-wrap gap-4">
        <div
          v-for="(account, index) in user.accounts"
          :key="index"
          class="p-4 mb-4 border rounded-lg shadow-md bg-white flex-1 min-h-full flex flex-col gap-2"
        >
          <p class="text-lg font-semibold">
            Type: {{ account.type.replace("Account", "") }}
          </p>
          <p class="text-lg">Balance: ${{ account.balance.toFixed(2) }}</p>
          <p class="text-lg">
            Deposit Increment: ${{ account.deposit_increment?.toFixed(2) ?? 0 }}
          </p>
          <div class="mt-4">
            <input
              type="number"
              v-model="depositAmount[index]"
              placeholder="Deposit Amount"
              class="border p-2 rounded w-full"
            />
            <button
              @click="makeDeposit(index)"
              class="mt-2 text-white py-2 px-4 rounded"
              :class="{
                'bg-blue-500 hover:bg-blue-600 cursor-pointer':
                  depositAmount[index] > 0,
                'bg-gray-300 cursor-none': depositAmount[index] <= 0,
              }"
              :disabled="depositAmount[index] <= 0"
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

  depositAmount.value = user.value.accounts.map((account) => 0);
});

async function makeDeposit(index) {
  const amount = depositAmount.value[index];
  const account = user.value.accounts[index];
  if (amount > 0) {
    const accountId = user.value.accounts[index].id;

    sendRequest({
      method: "POST",
      url: `/accounts/${accountId}/deposit`,
      data: {
        amount,
      },
    });

    if (account) {
      store.user.accounts[index].balance +=
        amount + (account.deposit_increment ?? 0);
      depositAmount.value[index] = 0;
    }

    console.log(account);
  }
}
</script>

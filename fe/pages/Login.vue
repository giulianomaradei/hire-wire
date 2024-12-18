<template>
  <div
    class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8"
  >
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h2
        class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900"
      >
        Sign in to your account
      </h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6" @submit.prevent="handleSubmit">
        <div v-if="isRegistering">
          <label for="name" class="block text-sm/6 font-medium text-gray-900"
            >Name*</label
          >
          <div class="mt-2">
            <input
              type="text"
              name="name"
              id="name"
              required
              v-model="name"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
            />
          </div>
        </div>

        <div>
          <label for="email" class="block text-sm/6 font-medium text-gray-900"
            >Email address <span v-if="isRegistering">*</span></label
          >
          <div class="mt-2">
            <input
              type="email"
              name="email"
              id="email"
              v-model="email"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
            />
          </div>
        </div>

        <div v-if="isRegistering">
          <label for="cpf" class="block text-sm/6 font-medium text-gray-900"
            >Cpf*</label
          >
          <div class="mt-2">
            <input
              type="text"
              name="cpf"
              id="cpf"
              v-model="cpf"
              @input="formatCPF"
              maxlength="14"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
            />
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label
              for="password"
              class="block text-sm/6 font-medium text-gray-900"
              >Password<span v-if="isRegistering">*</span></label
            >
          </div>
          <div class="mt-2">
            <input
              type="password"
              name="password"
              id="password"
              v-model="password"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
            />
          </div>
        </div>

        <div v-if="isRegistering">
          <div class="flex items-center justify-between">
            <label
              for="confirmPassword"
              class="block text-sm/6 font-medium text-gray-900"
              >Confirm Password*</label
            >
          </div>
          <div class="mt-2">
            <input
              type="password"
              name="confirmPassword"
              id="confirmPassword"
              v-model="confirmPassword"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
            />
          </div>
        </div>

        <div>
          <button
            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
          >
            {{ isRegistering ? "Create an account" : "Sign in" }}
          </button>
        </div>
      </form>
      <div class="min-h-5">
        <p class="text-sm text-red-500 max-w-full">
          {{ errorMessage }}
        </p>
      </div>
      <p
        v-if="!isRegistering"
        class="mt-10 text-center text-sm/6 text-gray-500"
      >
        Not a member?
        {{ " " }}
        <span
          class="font-semibold text-indigo-600 hover:text-indigo-500 cursor-pointer"
          @click="isRegistering = true"
          >Create an account</span
        >
      </p>

      <p v-if="isRegistering" class="mt-10 text-center text-sm/6 text-gray-500">
        Already a member?
        {{ " " }}
        <span
          class="font-semibold text-indigo-600 hover:text-indigo-500 cursor-pointer"
          @click="isRegistering = false"
          >Sign in</span
        >
      </p>
    </div>
  </div>
</template>

<script setup>
import { sendRequest } from "~/composables/global";
import { useStore } from "~/stores";

const router = useRouter();
const store = useStore();

const email = ref("");
const password = ref("");
const name = ref("");
const cpf = ref("");
const confirmPassword = ref("");

const isRegistering = ref(false);

const errorMessage = ref("");

function handleSubmit() {
  if (isRegistering.value) {
    register();
  } else {
    login();
  }
}

function resetValues() {
  errorMessage.value = "";
  email.value = "";
  password.value = "";
  name.value = "";
  cpf.value = "";
  confirmPassword.value = "";
}

async function register() {
  if (!cpfIsValid.value) {
    errorMessage.value = "Invalid CPF.";
    return;
  }

  if (password.value !== confirmPassword.value) {
    errorMessage.value = "Passwords do not match.";
    return;
  }

  const { data, error } = await sendRequest({
    method: "POST",
    url: "/register",
    data: {
      email: email.value,
      password: password.value,
      name: name.value,
      cpf: cpf.value,
    },
  });

  if (error) {
    errorMessage.value = "Error registering user.";
  } else {
    localStorage.setItem("token", data.access_token);
    store.setUser(data.user);
    resetValues();
    router.push("/");
  }
}

async function login() {
  const { data, error } = await sendRequest({
    method: "POST",
    url: "/login",
    data: {
      email: email.value,
      password: password.value,
    },
  });

  if (error) {
    errorMessage.value = "Error logging in.";
  } else {
    localStorage.setItem("token", data.access_token);
    store.setUser(data.user);
    resetValues();
    router.push("/");
  }
}

function formatCPF() {
  let value = cpf.value.replace(/\D/g, ""); // Remove tudo que não é número

  if (value.length <= 11) {
    value = value.replace(/(\d{3})(\d)/, "$1.$2");
    value = value.replace(/(\d{3})(\d)/, "$1.$2");
    value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
  }

  cpf.value = value;
}

const cpfIsValid = computed(() => {
  return cpf.value.length === 14;
});
</script>

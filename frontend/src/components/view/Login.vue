<template>
  <div class="w-full h-screen flex justify-center items-center">
    <form
      class="w-full max-w-[1317px] h-[800px] bg-white flex rounded-3xl"
      v-on:submit.prevent="onSubmit"
    >
      <div
        class="flex flex-1 justify-center items-center bg-[#033497] rounded-l-3xl"
      >
        <h1 class="text-white text-[3rem] font-bold">Desafio DidaTikos</h1>
      </div>
      <div class="flex flex-1 justify-center items-center gap-">
        <div
          class="w-4/5 h-3/5 flex flex-col justify-center items-center gap-8"
        >
          <div>
            <h1 class="text-[3rem] font-bold">Login</h1>
          </div>
          <input
            :class="[
              'w-full max-w-[350px] h-[45px] rounded-lg border-2 border-slate-400 p-2',
              emailError ? 'border-red-500' : '',
            ]"
            type="text"
            v-model="formData.email"
            placeholder="Digite seu E-mail"
            @blur="clearEmailError"
          />
          <div v-if="emailError">
            <p class="text-[1.2rem] text-red-500">{{ emailError }}</p>
          </div>
          <input
            :class="[
              'w-full max-w-[350px] h-[45px] rounded-lg border-2 border-slate-400 p-2',
              passwordError ? 'border-red-500' : '',
            ]"
            type="password"
            v-model="formData.password"
            placeholder="Digite sua Senha"
            @blur="clearPasswordError"
          />
          <div v-if="passwordError">
            <p class="text-[1.2rem] text-red-500">{{ passwordError }}</p>
          </div>
          <button
            class="w-full max-w-[350px] h-[45px] text-white text-lg rounded-lg border-none bg-[#033497] p-2 flex justify-center items-center hover:opacity-75 transition-all duration-300 ease-in-out"
            type="submit"
          >
            Login
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import axios from "axios";
import { useRouter } from "vue-router";
import Cookies from "js-cookie";
import { defineComponent, ref, onMounted } from "vue";
import { z } from "zod";

const formData = ref({
  email: "",
  password: "",
});

const emailError = ref("");
const passwordError = ref("");
const router = useRouter();

const validationSchema = z.object({
  email: z.string().email("E-mail inválido"),
  password: z.string().min(8, "Senha deve ter pelo menos 8 caracteres"),
});

const clearEmailError = () => {
  emailError.value = "";
};

const clearPasswordError = () => {
  passwordError.value = "";
};

const onSubmit = async () => {
  try {
    emailError.value = "";
    passwordError.value = "";

    const data = validationSchema.parse(formData.value);
    await axios
      .post("http://127.0.0.1:8000/api/login", data)
      .then((res) => {
        Cookies.set("my_token", res.data.token);
        if (res.status == 200) {
          router.push({ name: "Home" });
        }
      })
      .catch((err) => {
        console.error(err);
      });
  } catch (error) {
    console.error("Erro de validação:", error.errors);
    if (error.errors && Array.isArray(error.errors)) {
      error.errors.forEach((validationError) => {
        if (validationError.path[0] === "email") {
          emailError.value = validationError.message;
        } else if (validationError.path[0] === "password") {
          passwordError.value = validationError.message;
        }
      });
    } else {
      console.error("Usuário não encontrado. Verifique suas credenciais.");
    }
  }
};

onMounted(() => {
  if (Cookies.get("my_token")) {
    router.push({ name: "Home" });
  }
}),
  defineComponent({
    name: "LoginComponent",
  });
</script>

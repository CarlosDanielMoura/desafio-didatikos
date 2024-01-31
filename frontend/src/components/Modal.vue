<template>
  <div
    class="fixed -top-32 left-0 w-full h-full bg-black bg-opacity-5 flex justify-center items-center"
    v-show="props.showModal"
  >
    <div class="bg-white p-3 rounded-lg w-[500px] flex flex-col">
      <!-- Header -->
      <header class="p-2 border-b-2">
        <h1 class="font-bold text-3xl">Cadastrar Produto</h1>
      </header>

      <!-- Main Content -->
      <main class="w-full flex-1 p-3 justify-center">
        <form
          class="flex flex-col w-full justify-center items-center"
          action=""
        >
          <div
            class="mb-5 flex flex-col items-center w-full justify-center gap-4"
          >
            <input
              class="w-full max-w-[300px] h-[45px] rounded-lg border p-2 placeholder:text-black"
              placeholder="Digite nome do produto"
              type="text"
              autocomplete="off"
              v-model="formData.NAME_PRODUCT"
            />
            <p class="text-red-700">{{ formErrors.NAME_PRODUCT }}</p>
          </div>
          <div
            class="mb-5 flex flex-col w-full justify-center items-center gap-4"
          >
            <input
              class="w-full max-w-[300px] h-[45px] rounded-lg border p-2 placeholder:text-black"
              type="text"
              placeholder="Digite valor do produto: Ex: 10.00 ou 10 "
              autocomplete="off"
              v-model="formData.PRICE"
            />
            <p class="text-red-700">{{ formErrors.PRICE }}</p>
          </div>
          <div
            class="mb-5 flex flex-col w-full justify-center items-center gap-4"
          >
            <input
              class="w-full max-w-[300px] h-[45px] rounded-lg border p-2 placeholder:text-black"
              type="text"
              placeholder="Digite a quantidade em estoque"
              autocomplete="off"
              v-model="formData.STOCK"
            />
            <p class="text-red-700">{{ formErrors.STOCK }}</p>
          </div>
          <div class="flex gap-4 flex-col w-full justify-center items-center">
            <select
              class="w-full max-w-[300px] h-[45px] rounded-lg border p-2 placeholder:text-black"
              v-model="formData.BRAND_PRODUCT"
            >
              <option value="" disabled selected>
                {{
                  brands.length === 0
                    ? "Adicione uma Marca"
                    : "Selecione uma Marca"
                }}
              </option>

              <option
                class="hover:cursor-pointer"
                v-for="brand in brands"
                :key="brand.id"
                :value="brand.id"
              >
                {{ brand.Nome }}
              </option>
            </select>
            <p class="text-red-700">{{ formErrors.BRAND_PRODUCT }}</p>
          </div>
          <div
            class="flex flex-col gap-4 w-full justify-center items-center mt-5"
          >
            <select
              class="w-full max-w-[300px] h-[45px] rounded-lg border p-2 placeholder:text-black"
              v-model="formData.CITY"
            >
              <option value="" disabled selected>
                {{
                  cities.length === 0
                    ? "Adicione uma Cidade"
                    : "Selecione uma Cidade"
                }}
              </option>

              <option
                class="hover:cursor-pointer"
                v-for="city in cities"
                :key="city.id"
                :value="parseInt(city.id)"
              >
                {{ city.nome }}
              </option>
            </select>
            <p class="text-red-700">{{ formErrors.CITY }}</p>
          </div>

          <div class="w-full p-3 flex justify-center">
            <p v-text="message"></p>
          </div>
        </form>
      </main>

      <!-- Footer -->
      <footer class="flex justify-between gap-4 border-t-2 items-center">
        <button
          class="w-[100px] h-[45px] rounded-lg bg-red-700 border-none text-white mt-3 hover:cursor-pointer hover:bg-opacity-90 ease-in-out"
          @click="closeModal"
        >
          Fechar
        </button>
        <button
          class="w-[100px] h-[45px] rounded-lg bg-blue-600 border-none text-white mt-3 hover:cursor-pointer hover:bg-opacity-90 ease-in-out"
          @click="onSubmit"
        >
          Adicionar
        </button>
      </footer>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import Cookies from "js-cookie";
import { ref, onMounted, defineComponent, defineEmits, defineProps } from "vue";
import { z } from "zod";

const brands = ref([]);
const cities = ref([]);
const message = ref("");
const emit = defineEmits(["update:showModal"]);

const formData = ref({
  NAME_PRODUCT: "",
  PRICE: "",
  STOCK: "",
  BRAND_PRODUCT: "",
  CITY: "",
});

const formErrors = ref({
  NAME_PRODUCT: "",
  PRICE: "",
  STOCK: "",
  BRAND_PRODUCT: "",
  CITY: "",
});

const validationSchema = z.object({
  NAME_PRODUCT: z.string().min(3, "Nome deve ter pelo menos 3 caracteres"),
  PRICE: z
    .string()
    .regex(/^\d+(\.\d{1,2})?$/)
    .refine((value) => parseFloat(value) >= 0, {
      message: "O preço deve ser um número positivo. Ex: (10.00),(10),(0.10)",
    }),
  STOCK: z.string().refine((value) => parseFloat(value) >= 0, {
    message: "O estoque deve ser um número positivo. Ex: (0) , (1)",
  }),
  BRAND_PRODUCT: z
    .union([z.number(), z.string()])
    .refine((value) => value !== "", "Selecione uma marca."),
  CITY: z
    .union([z.number(), z.string()])
    .refine((value) => value !== "", "Selecione uma cidade."),
});

const props = defineProps({
  showModal: Boolean,
});

const closeModal = () => {
  emit("update:showModal", false);
  window.location.reload();
};

const getAllBrands = async () => {
  try {
    const response = await axios.get("http://127.0.0.1:8000/api/brand", {
      headers: {
        Authorization: `Bearer ${Cookies.get("my_token")}`,
        Accept: "application/json",
        ContentType: "application/json",
      },
    });
    brands.value = response.data.data;
  } catch (error) {
    console.error("Erro ao obter Marca:", error.message);
  }
};

const getAllCity = async () => {
  try {
    const response = await axios.get("http://127.0.0.1:8000/api/city", {
      headers: {
        Authorization: `Bearer ${Cookies.get("my_token")}`,
        Accept: "application/json",
        ContentType: "application/json",
      },
    });
    cities.value = response.data.data;
  } catch (error) {
    console.error("Erro ao obter Cidades:", error.message);
  }
};

const onSubmit = async () => {
  try {
    message.value = "";

    const data = validationSchema.parse(formData.value);
    const response = await axios.post(
      "http://127.0.0.1:8000/api/product",
      data,
      {
        headers: {
          Authorization: `Bearer ${Cookies.get("my_token")}`,
          Accept: "application/json",
          ContentType: "application/json",
        },
      }
    );
    if (response.status === 201) {
      message.value = response.data.message;
      setTimeout(() => {
        message.value = "";
      }, 2000);
    }
  } catch (error) {
    if (error instanceof z.ZodError) {
      error.errors.forEach((validationError) => {
        if (validationError.path[0] === "NAME_PRODUCT") {
          formErrors.value.NAME_PRODUCT = validationError.message;
        } else if (validationError.path[0] === "PRICE") {
          formErrors.value.PRICE = validationError.message;
        } else if (validationError.path[0] === "STOCK") {
          formErrors.value.STOCK = validationError.message;
        } else if (validationError.path[0] === "BRAND_PRODUCT") {
          formErrors.value.BRAND_PRODUCT = validationError.message;
        } else {
          formErrors.value.CITY = validationError.message;
        }
        setTimeout(() => {
          formErrors.value = {
            NAME_PRODUCT: "",
            PRICE: "",
            STOCK: "",
            BRAND_PRODUCT: "",
            CITY: "",
          };
        }, 2000);
      });
    } else if (error.response && error.response.status) {
      if (error.response.status === 404) {
        console.error(error.response.data.message);
      } else if (error.response.status === 409) {
        console.error(error.response.data.message);
        message.value = error.response.data.message;
        setTimeout(() => {
          message.value = "";
        }, 2000);
      } else {
        console.error(error);
      }
    } else {
      // Erros desconhecidos
      console.error(error);
    }
  }
};

onMounted(() => {
  getAllBrands();
  getAllCity();
});

defineComponent({
  name: "ModalComponent",
  props: {
    showModal: Boolean,
  },
});
</script>

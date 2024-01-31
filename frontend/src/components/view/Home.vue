<template lang="html">
  <div class="w-full h-[150px] flex flex-col items-center justify-center gap-4">
    <div class="w-full flex justify-center">
      <p
        class="font-bold text-red-600 text-3xl"
        v-text="messageErrorDelete"
      ></p>
    </div>
    <div class="w-full max-w-[1300px] flex justify-between">
      <button
        class="w-[150px] h-[45px] rounded-lg bg-blue-600 border-none text-white mt-3 hover:cursor-pointer hover:bg-opacity-90 ease-in-out"
        @click="openModal"
      >
        Adicionar Produto
      </button>
      <button
        class="w-[45px] h-[45px] rounded-full bg-red-600 border-none text-white mt-3 hover:cursor-pointer hover:bg-opacity-90 ease-in-out"
        @click="logout"
      >
        Sair
      </button>
    </div>

    <!-- Modal Resgistrar Produto -->
    <ModalComponent :showModal="showModal" @update:showModal="closeModal" />
  </div>

  <div class="flex-grow flex justify-center items-center mt-6">
    <div
      v-if="products.length == 0"
      class="w-[400px] h-[45px] rounded flex justify-center items-center bg-gray-400"
    >
      <p class="text-white font-bold" v-text="messageError"></p>
    </div>
    <div
      v-else
      class="w-full max-w-[1300px] flex flex-col justify-center items-center flex-wrap gap-3"
    >
      <div class="flex items-center gap-3">
        <label for="filtroProdutos">Filtrar Produtos:</label>
        <select
          class="border rounded-xl w-[120px] h-[45px] p-1"
          id="filtroProdutos"
          @change="filtrarProdutos($event.target.value)"
        >
          <option value="todos">Listar Todos</option>
          <option value="acima">Acima da média</option>
          <option value="abaixo">Abaixo da média</option>
        </select>
      </div>
      <div class="flex items-center gap-7">
        <div class="flex items-center gap-3">
          <label for="minValue">Valor Mínimo:</label>
          <input
            class="w-[100px] h-[45px] border rounded-xl"
            type="number"
            v-model="minValue"
            id="minValue"
          />
        </div>
        <div class="flex items-center gap-3">
          <label for="maxValue">Valor Máximo:</label>
          <input
            class="w-[100px] h-[45px] border rounded-xl"
            type="number"
            v-model="maxValue"
            id="maxValue"
          />
        </div>
        <button
          class="w-[100px] bg-[#5785c1] h-[45px] border rounded-xl text-white"
          @click="filterProductsMaxMin"
        >
          Filtrar
        </button>
      </div>

      <div>
        <select
          class="w-full max-w-[300px] h-[45px] rounded-lg border p-2 placeholder:text-black"
          @change="filterCity($event.target.value)"
        >
          <option value="todos" selected>Todas</option>

          <option
            class="hover:cursor-pointer text-black"
            v-for="city in cities"
            :key="city.id"
            :value="city.nome"
            @change="filterCity($event.target.value)"
          >
            {{ city.nome }}
          </option>
        </select>
      </div>
      <table class="w-full border-[2px] bg-transparent rounded-lg p-3">
        <thead class="bg-[#5785c1]">
          <tr class="text-white">
            <th class="border p-4">Nome</th>
            <th class="border">Valor</th>
            <th class="border">Estoque</th>
            <th class="border">Marca</th>
            <th class="border">Cidade</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in produtosExibidos" :key="product.id">
            <td align="center" class="border">{{ product.nome }}</td>
            <td align="center" class="border">
              {{ formatMoney(product.valor) }}
            </td>
            <td align="center" class="border">
              {{ parseInt(product.estoque).toFixed(0) }}
            </td>
            <td align="center" class="border">{{ product.marca }}</td>
            <td align="center" class="border">{{ product.cidade }}</td>
            <td align="center">
              <button
                class="w-[100px] h-10 text-white bg-red-600 rounded-lg"
                @click="deleteProduct(product.id)"
              >
                Excluir
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="w-full flex justify-center">
    <div
      class="w-full max-w-[1300px] h-[150px] bottom-0 fixed flex justify-between mb-3"
    >
      <div
        class="w-[250px] flex flex-col justify-center items-center border rounded-xl gap-4 bg-blue-600 text-white p-4 font-bold tracking-wide"
      >
        <p>Produtos Cadastrados</p>
        <p>{{ products.length }}</p>
      </div>
      <div
        class="w-[250px] flex flex-col justify-center items-center border rounded-xl gap-4 bg-blue-600 text-white p-4 font-bold tracking-wide"
      >
        <p>Quantidade em estoque</p>
        <p>
          {{
            products.reduce(
              (total, product) => total + parseInt(product.estoque),
              0
            )
          }}
        </p>
      </div>
      <div
        class="w-[250px] flex flex-col justify-center items-center border rounded-xl gap-4 bg-blue-600 text-white p-4 font-bold tracking-wide"
      >
        <p>Valor médio dos produtos</p>
        <p>{{ formatMoney(calcularMedia()) }}</p>
      </div>
      <div
        class="w-[250px] flex flex-col justify-center items-center border rounded-xl gap-4 bg-blue-600 text-white p-4 font-bold tracking-wide"
      >
        <p>Produto mais caro</p>
        <p>
          {{
            formatMoney(
              products.reduce(
                (total, product) =>
                  total > parseFloat(product.valor)
                    ? total
                    : parseFloat(product.valor),
                0
              )
            )
          }}
        </p>
      </div>
      <div
        class="w-[250px] flex flex-col justify-center items-center border rounded-xl gap-4 bg-blue-600 text-white p-4 font-bold tracking-wide"
      >
        <p>Produto mais barato</p>
        <p>
          {{
            formatMoney(
              products.reduce(
                (minPrice, product) =>
                  minPrice < parseFloat(product.valor)
                    ? minPrice
                    : parseFloat(product.valor),
                parseFloat(products[0]?.valor) || 0
              )
            )
          }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import Cookies from "js-cookie";
import ModalComponent from "../Modal";

import { ref, onMounted, defineComponent } from "vue";
import { modules as formatMoney } from "../../utils/formatMoney";
const showModal = ref(false);
const products = ref([]);
const messageError = ref("");
const messageErrorDelete = ref("");
const produtosExibidos = ref([]);
const cities = ref([]);
const minValue = ref(null);
const maxValue = ref(null);

const getProducts = async () => {
  try {
    const response = await axios.get("http://127.0.0.1:8000/api/product", {
      headers: {
        Authorization: `Bearer ${Cookies.get("my_token")}`,
        Accept: "application/json",
        ContentType: "application/json",
      },
    });

    const data = response.data.data;

    if (data.length === 0) {
      products.value = [];
    } else {
      produtosExibidos.value = data;
      products.value = data;
    }
  } catch (error) {
    messageError.value = "Não há produtos disponíveis.";
    console.error("Erro ao obter os produtos:", error.message);
  }
};

const deleteProduct = async (id) => {
  try {
    const response = await axios.delete(
      `http://127.0.0.1:8000/api/product/${id}`,
      {
        headers: {
          Authorization: `Bearer ${Cookies.get("my_token")}`,
          Accept: "application/json",
          ContentType: "application/json",
        },
      }
    );
    if (response.data.status === 200 && products.value.length == 1) {
      window.location.reload();
    }

    getProducts();
  } catch (error) {
    if (error.response.status === 409) {
      messageErrorDelete.value =
        "Não é possível excluir um produto que possui estoque.";
      setTimeout(() => {
        messageErrorDelete.value = "";
      }, 3000);
    }
    console.error("Produtos não encontrados:", error.message);
  }
};

const openModal = () => {
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

onMounted(() => {
  getProducts();
  getAllCity();
});

const calcularMedia = () => {
  if (products.value.length === 0) {
    return 0;
  }
  const somaValores = products.value.reduce(
    (total, product) => total + parseFloat(product.valor),
    0
  );
  return somaValores / products.value.length; // Retorna a média
};

const logout = () => {
  Cookies.remove("my_token");
  window.location.reload();
};

const filtrarProdutos = (tipoFiltro) => {
  switch (tipoFiltro) {
    case "acima":
      produtosExibidos.value = products.value.filter(
        (product) => product.valor > calcularMedia()
      );
      console.table(produtosExibidos.value);
      break;
    case "abaixo":
      produtosExibidos.value = products.value.filter(
        (product) => product.valor < calcularMedia()
      );
      console.table(produtosExibidos.value);
      break;
    default:
      produtosExibidos.value = products.value;
      console.table(produtosExibidos.value);
      console.log("Média:", calcularMedia()); // Adiciona os parênteses para chamar a função
  }
};

const filterCity = (tipoCidade) => {
  if (tipoCidade != "todos") {
    produtosExibidos.value = products.value.filter(
      (product) => product.cidade == tipoCidade
    );
  } else {
    produtosExibidos.value = products.value;
  }
};

const filterProductsMaxMin = () => {
  if (!minValue.value && !maxValue.value) {
    return (produtosExibidos.value = products.value);
  }

  if (minValue.value && !maxValue.value) {
    produtosExibidos.value = products.value.filter(
      (product) => product.valor >= minValue.value
    );
    return produtosExibidos.value;
  } else if (!minValue.value && maxValue.value) {
    produtosExibidos.value = products.value.filter(
      (product) => product.valor <= maxValue.value
    );
    console.table(produtosExibidos.value);
    return produtosExibidos.value;
  } else {
    produtosExibidos.value = products.value.filter(
      (product) =>
        product.valor >= minValue.value && product.valor <= maxValue.value
    );
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
defineComponent({
  name: "HomePage",
  components: {
    ModalComponent,
  },
});
</script>

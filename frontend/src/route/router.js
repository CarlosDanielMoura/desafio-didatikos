// router.js
import { createRouter, createWebHistory } from "vue-router";
import HomePage from "@/components/view/Home.vue";
import LoginComponent from "@/components/view/Login.vue";
import Guard from "@/services/middleware";

const routes = [
  { path: "/login", name: "Login", component: LoginComponent },
  { path: "/", name: "Home", component: HomePage, beforeEnter: Guard.auth },
  { path: "/:pathMatch(.*)*", name: "NotFound", component: LoginComponent },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;

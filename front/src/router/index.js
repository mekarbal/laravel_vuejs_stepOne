import { createRouter, createWebHistory } from "vue-router";
import Home from "@/components/Home.vue";
import Login from "@/components/Login.vue";
import AddAdmin from "@/components/AddAdmin.vue";
import Company from "@/components/Company.vue";
import InviteEmployee from "@/components/InviteEmployee.vue";
const routes = [
  {
    path: "",
    component: Login,
  },
  {
    path: "/home",
    component: Home,
  },
  {
    path: "/add_admin",
    component: AddAdmin,
  },
  {
    path: "/company",
    component: Company,
  },
  {
    path: "/inviter",
    component: InviteEmployee,
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;

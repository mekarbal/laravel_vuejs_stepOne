<template>
  <div v-if="!error" class="container">
    <header class="d-flex justify-content-center py-3">
      <ul class="nav nav-pills">
        <li class="nav-item">
          <routerLink class="nav-link" to="/" aria-current="page">
            Home
          </routerLink>
        </li>
        <li class="nav-item">
          <routerLink class="nav-link" to="/add_admin" aria-current="page">
            Ajouer Admin
          </routerLink>
        </li>
        <li class="nav-item">
          <routerLink class="nav-link" to="/company" aria-current="page">
            Ajouer Entreprise
          </routerLink>
        </li>
        <li class="nav-item">
          <routerLink class="nav-link" to="/inviter" aria-current="page">
            Inviter
          </routerLink>
        </li>
      </ul>
    </header>
  </div>
</template>

<script>
import axios from "axios";
import { useRouter } from "vue-router";
export default {
  name: "Navbar",
  data() {
    return {
      admin: {},
      token: null,
      error: false,
    };
  },
  methods: {
    getAdmin: async function () {
      const token = await localStorage.getItem("token");

      this.token = token;
      console.log(token);
      axios
        .get("http://localhost:8000/api/admin/user-profile", {
          headers: {
            Authorization: "Bearer " + token,
          },
        })
        .then((response) => {
          console.log("heeeeeeeeeee", response.data);
          localStorage.setItem("userData", JSON.stringify(response.data));
          this.admin = response.data;
        })
        .catch((error) => {
          console.log("heeeeeeeeeee", error);
        });
    },
  },
  mounted() {
    this.getAdmin();
    const router = useRouter();
    let token = localStorage.getItem("token");

    if (token != undefined || this.error) {
      router.push("/");
    } else {
      router.push("/home");
    }
  },
};
</script>

<style lang="scss" scoped></style>

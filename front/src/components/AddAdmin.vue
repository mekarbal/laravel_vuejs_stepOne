<template>
  <div class="container">
    <main class="form-signin w-100 m-auto">
      <form @submit.prevent="submit">
        <h1 class="h3 mb-3 fw-normal">Add admin</h1>

        <p v-if="error">Error: {{ error }}</p>
        <div class="form-floating">
          <input
            type="text"
            class="form-control"
            id="floatingNameInput"
            placeholder="Name"
            v-model="data.name"
          />
          <label for="floatingNameInput">Name</label>
        </div>
        <div class="form-floating">
          <input
            type="email"
            class="form-control"
            id="floatingInput"
            placeholder="Email"
            v-model="data.email"
          />
          <label for="floatingInput">Email</label>
        </div>
        <div class="form-floating">
          <input
            type="password"
            class="form-control"
            id="floatingPassword"
            placeholder="Password"
            v-model="data.password"
          />
          <label for="floatingPassword">Password</label>
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit">
          Sign in
        </button>
      </form>
    </main>

    <table class="table">
      <thead>
        <tr>
          <th>id</th>
          <th>name</th>
          <th>email</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="admin in admins" :key="admin.id">
          <td>{{ admin.id }}</td>
          <td>{{ admin.name }}</td>
          <td>{{ admin.email }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import { reactive } from "vue";
import axios from "axios";
export default {
  name: "Add_Admin",
  data() {
    return {
      admins: [],
    };
  },
  methods: {
    getAdmins: function () {
      const token = localStorage.getItem("token");
      console.log(token);
      axios
        .get("http://localhost:8000/api/admin/admins", {
          headers: {
            Authorization: "Bearer " + token,
          },
        })
        .then((response) => {
          console.log(response);
          this.admins = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    this.getAdmins();
  },
  setup() {
    const data = reactive({
      email: "",
      password: "",
      name: "",
    });
    const submit = async () => {
      console.log(data);
      await axios
        .post("http://localhost:8000/api/admin/register", data)
        .then(async (response) => {
          console.log(response);
          await this.getAdmins.apply(this);
        })
        .catch(async () => {});
    };
    return { data, submit };
  },
};
</script>

<style lang="scss" scoped></style>

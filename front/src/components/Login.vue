<template>
  <div class="container">
    <main class="form-signin w-100 m-auto">
      <form @submit.prevent="submit">
        <h1 class="h3 mb-3 fw-normal">sign in</h1>

        <div class="form-floating">
          <input
            type="email"
            class="form-control"
            id="floatingInput"
            placeholder="Email"
            v-model="data.email"
          />
          <label for="floatingInput">Email address</label>
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
  </div>
</template>

<script>
import { reactive } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
export default {
  name: "Login",
  setup() {
    const data = reactive({
      email: "",
      password: "",
    });
    const router = useRouter();
    const submit = async () => {
      console.log(data);
      await axios
        .post("http://localhost:8000/api/admin/login", data)
        .then(async (response) => {
          await localStorage.setItem("token", response?.data?.access_token);
          router.push("/home");
        })
        .catch((error) => {
          console.log(error);
        });
    };
    return { data, submit };
  },
  mounted() {
    const router = useRouter();
    let token = localStorage.getItem("token");

    if (token != undefined) {
      router.push("/");
    } else {
      router.push("/home");
    }
  },
};
</script>

<style>
.form-signin {
  max-width: 330px;
  padding: 15px;
}

.form-signin .form-floating:focus-within {
  z-index: 2;
}

.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>

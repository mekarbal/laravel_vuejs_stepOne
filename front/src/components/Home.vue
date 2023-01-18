<template>
  <div>Hello admin {{ admin?.name }}</div>
</template>

<script>
import axios from "axios";
export default {
  name: "Home",
  data() {
    return {
      admin: {},
    };
  },
  methods: {
    getAdmin: function () {
      const token = localStorage.getItem("token");
      console.log(token);
      axios
        .get("http://localhost:8000/api/admin/user-profile", {
          headers: {
            Authorization: "Bearer " + token,
          },
        })
        .then((response) => {
          console.log(response.data);
          localStorage.setItem("userData", response.data);
          this.admin = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    this.getAdmin();
  },
};
</script>

<style></style>

<template>
  <!-- Button trigger modal -->
  <div class="container">
    <button
      type="button"
      class="btn btn-primary"
      data-bs-toggle="modal"
      data-bs-target="#exampleModal"
    >
      Ajouter une entreprise
    </button>
    <form @submit.prevent="search">
      <div class="mb-3">
        <label for="exampleInputSearch" class="form-label">Search</label>
        <input
          @input="setCompanyName"
          type="text"
          class="form-control"
          id="exampleInputSearch"
        />
      </div>

      <button type="submit" class="btn btn-primary">Search</button>
    </form>
    <button @click="sortByName" type="submit" class="btn btn-primary mt-5">
      Sort By name
    </button>
    <table class="table mt-5">
      <thead>
        <tr>
          <th>id</th>
          <th>name</th>
          <th>location</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="company in companies" :key="company.id">
          <td>{{ company.id }}</td>
          <td>{{ company.name }}</td>
          <td>{{ company.location }}</td>
          <td>
            <button
              class="btn btn-danger"
              @click="deleteCompany(company.id)"
              type="submit"
            >
              delete
            </button>
            <button
              class="btn btn-info"
              @click="storeCompany(company)"
              data-bs-toggle="modal"
              data-bs-target="#modal"
            >
              edit
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Modal -->
  <div
    class="modal fade"
    id="exampleModal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">
            Ajouter Entreprise
          </h1>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
            @click="deselectCompany()"
          ></button>
        </div>
        <div class="modal-body">
          <main class="form-signin m-auto">
            <form
              @submit.prevent="
                update ? updateCompany(storedCompanyData) : submit()
              "
            >
              <div class="form-floating">
                <input
                  type="text"
                  class="form-control"
                  id="floatingNameInput"
                  placeholder="Name"
                  :value="update ? storedCompanyData.name : data.name"
                  @input="nameChanged"
                />
                <label for="floatingNameInput">Name</label>
              </div>
              <div class="form-floating">
                <input
                  type="text"
                  class="form-control"
                  id="floatingInput"
                  placeholder="location"
                  :value="update ? storedCompanyData.location : data.location"
                  @input="locationChanged"
                />
                <label for="floatingInput">Location</label>
              </div>

              <button
                class="w-100 btn btn-lg btn-primary"
                data-bs-dismiss="modal"
                aria-label="Close"
                type="submit"
              >
                {{ update ? "Modifier" : "Ajouter" }}
              </button>
            </form>
          </main>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import { reactive } from "vue";
import axios from "axios";

export default {
  name: "Company",
  data() {
    return {
      companies: [],
      storedCompanyData: {},
      data: {},
      update: false,
      companyName: "",
    };
  },
  methods: {
    async getCompanies() {
      const token = localStorage.getItem("token");

      await axios
        .get("http://localhost:8000/api/companies", {
          headers: {
            Authorization: "Bearer " + token,
          },
        })
        .then((response) => {
          console.log(response.data.data);
          this.companies = response.data.data;
        })
        .catch((error) => {
          alert(error.response.data.message);
        });
    },
    async deleteCompany(id) {
      await axios
        .delete("http://localhost:8000/api/company/" + id)
        .then((response) => {
          console.log(response.data.message);
          this.getCompanies();
        })
        .catch((error) => {
          alert(error.response.data.message);
        });
    },
    async submit() {
      console.log("ddddddddddd", this.data);
      await axios
        .post("http://localhost:8000/api/add_company", this.data)
        .then(() => {
          this.getCompanies();
        })
        .catch(async (error) => {
          console.log(error?.response?.data);
        });
    },
    async updateCompany(storedCompanyData) {
      await axios
        .put(`http://localhost:8000/api/company/${storedCompanyData.id}`, {
          name: storedCompanyData.name,
          location: storedCompanyData.location,
        })
        .then((res) => {
          console.log("update", res);
          this.getCompanies();
        })
        .catch(async (error) => {
          console.log(error?.response?.data);
        });
    },
    async search() {
      await axios
        .post(`http://localhost:8000/api/company/${this.companyName}`)
        .then((res) => {
          this.companies = res.data.data;
        })
        .catch(async (error) => {
          console.log(error?.response?.data);
        });
    },
    async sortByName() {
      await axios
        .post(`http://localhost:8000/api/companies/sort/`)
        .then((res) => {
          this.companies = res.data.data;
        })
        .catch(async (error) => {
          console.log(error?.response?.data);
        });
    },
    async storeCompany(company) {
      this.storedCompanyData = company;
      this.update = true;
      console.log(this.storedCompanyData);
    },
    async setCompanyName(e) {
      this.companyName = e.target.value;
      if (this.companyName === "") {
        this.getCompanies();
      }
    },
    async deselectCompany() {
      this.storedCompanyData = {};
      this.update = false;
      console.log(this.storedCompanyData);
    },
    locationChanged(event) {
      this.update
        ? (this.storedCompanyData = {
            ...this.storedCompanyData,
            location: event.target.value,
          })
        : (this.data = {
            ...this.data,
            location: event.target.value,
          });
    },
    nameChanged(event) {
      this.update
        ? (this.storedCompanyData = {
            ...this.storedCompanyData,
            name: event.target.value,
          })
        : (this.data = {
            ...this.data,
            name: event.target.value,
          });
    },
  },
  mounted() {
    this.getCompanies();
  },
  computed: {
    // data: {
    //   get() {
    //     return reactive({
    //       name: "",
    //       location: "",
    //     });
    //   },
    // },
  },
};
</script>

<style lang="scss" scoped></style>

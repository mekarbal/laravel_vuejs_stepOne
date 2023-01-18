<template>
  <div class="container w-50">
    <form @submit.prevent="addInvitation">
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Email</label>
        <input
          type="email"
          class="form-control"
          id="exampleInputEmail1"
          aria-describedby="emailHelp"
          v-model="data.email"
          name="email"
          @input="inputData"
        />
      </div>
      <div class="mb-3">
        <label for="exampleInpuName1" class="form-label">Employee Name</label>
        <input
          type="text"
          class="form-control"
          id="exampleInpuName1"
          aria-describedby="emailHelp"
          v-model="data.receiver"
          name="receiver"
          @input="inputData"
        />
      </div>
      <div class="mb-3">
        <label for="companyName" class="form-label">Company Name</label>
        <input
          type="text"
          class="form-control"
          id="companyName"
          aria-describedby="emailHelp"
          v-model="data.company_name"
          @input="inputData"
          name="company_name"
        />
      </div>

      <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <table class="table">
      <thead>
        <tr>
          <th>inviation</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="invitation in invitations" :key="invitation.id">
          <td>
            {{ formatDate(invitation.created_at) + " "
            }}{{
              invitation.is_Admin
                ? invitation.is_Admin &&
                  "Admin " +
                    '"' +
                    invitation?.sender_name +
                    "\" a invite l'employé " +
                    '"' +
                    invitation?.receiver +
                    '" à joindre la société ' +
                    invitation?.company_name
                : invitation?.is_profile_confirmed
                ? invitation?.receiver + " à confirmer son profile"
                : invitation?.validated &&
                  invitation?.receiver + " à valider son profile"
            }}
          </td>
          <td>
            <button
              v-if="!invitation?.sent"
              type="submit"
              class="btn btn-success mr-5"
              @click="validateInvitation(invitation)"
            >
              Envoyer
            </button>
            <button
              @click="deleteInvitation(invitation?.id)"
              type="submit"
              class="btn btn-danger"
            >
              annuler
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "InviteEmployee",
  data() {
    return {
      invitations: [],
      data: {
        email: "",
        company_name: "",
        receiver: "",
      },
      userData: {},
    };
  },
  methods: {
    async getInvitations() {
      await axios
        .get("http://127.0.0.1:8000/api/invitations")
        .then((response) => {
          console.log(response.data.data);
          this.invitations = response.data.data;
        })
        .catch((error) => {
          alert(error.response.data.message);
        });
    },
    formatDate(dateStr) {
      const date = new Date(dateStr);
      const [day, month, year, hours, minutes, seconds] = [
        date.getDate().toString().padStart(2, "0"),
        (date.getMonth() + 1).toString().padStart(2, "0"),
        date.getFullYear(),
        date.getHours().toString().padStart(2, "0"),
        date.getMinutes().toString().padStart(2, "0"),
        date.getSeconds().toString().padStart(2, "0"),
      ];
      return `${day}-${month}-${year} - ${hours}:${minutes}:${seconds}`;
    },
    async deleteInvitation(id) {
      await axios
        .delete(`${"http://127.0.0.1:8000/api/invitation/"}${id}`)
        .then((response) => {
          console.log(response.data.data);
          this.getInvitations();
          alert("l'invitation est annulée");
        })
        .catch((error) => {
          alert(error.response.data.message);
        });
    },
    async addInvitation() {
      await axios
        .post(`${"http://127.0.0.1:8000/api/invitation"}`, {
          email: await this?.data?.email,
          company_name: this.data.company_name,
          receiver: this.data.receiver,
          sender_name: this.userData?.name,
        })
        .then((response) => {
          console.log(response.data);
          this.getInvitations();
        })
        .catch((error) => {
          alert(error.response.data.message);
        });
    },
    async validateInvitation(invitation) {
      await axios
        .put(`${"http://127.0.0.1:8000/api/invitation/"}${invitation.id}`, {
          sent: true,
        })
        .then(async (response) => {
          console.log(response?.data?.data);
          await this.getInvitations();
          await this.sendEmail(response?.data?.data);
        })
        .catch((error) => {
          alert(error.response.data.message);
        });
    },
    async sendEmail(data) {
      console.log(data);
      await axios
        .post(`${"http://127.0.0.1:8000/api/sendemail"}`, {
          email: await data?.email,
          name: await data?.sender_name,
          subject: `inviation to join the company ${await data?.company_name}`,
        })
        .then((response) => {
          console.log(response.data);
          this.getInvitations();
        })
        .catch((error) => {
          alert(error.response.data.message);
        });
    },

    async inputData(e) {
      const { target } = e;
      this.data = {
        ...this.data,
        [target.name]: target.value,
      };
    },
  },
  mounted() {
    this.getInvitations();
    const userDataFromStorage = localStorage.getItem("userData");
    this.userData = JSON.parse(userDataFromStorage);
    console.log(JSON.parse(userDataFromStorage));
  },
};
</script>

<style lang="scss" scoped></style>

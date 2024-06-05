<template>
  <div class="user-deletion">
    <h2>Slett Meg</h2>

    <div>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>

      <div class="user-deletion-list">
        <h3><b>You will permanently lose:</b></h3>

        <ul>
          <li>Your Profile</li>
          <li>Access to courses</li>
          <li>Saved progress</li>
          <li>Diplomas</li>
          <li>Etc...</li>
        </ul>
      </div>

      <div class="user-deletion-loading" v-if="formLoading">
        <LoadingIndicator />
      </div>

      <form class="user-delete-form" @submit.prevent="submitDeleteForm" v-if="!formLoading && showDeleteForm">
        <label class="user-delete-form-accept" for="accept-risk">
          <input id="accept-risk" type="checkbox" v-model="acceptedRisk" required>
          <p>I understand my user will be permanently delete and there is no way to undo this action after 30 days.</p>
        </label>

        <button class="kpas-button" type="submit" :disabled="!acceptedRisk">Send kode på epost</button>

        <span class="kpas-button-error" v-if="formErrorMessage != ''"><b>Error:</b> {{ formErrorMessage }}</span>
      </form>

      <form class="user-verify-form" @submit.prevent="submitVerifyForm" v-if="!formLoading && showVerifyForm">
        <h4>Verify request</h4>

        <p>Something about the email and instructions on how to proceed. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
        
        <div class="user-verify-form-code">
          <label for="email-code">Tast inn kode:</label>

          <input id="email-code" v-model="emailCode" minlength="4" required>
        </div>

        <div class="user-verify-form-buttons">
          <button class="kpas-button" type="submit">Verifiser kode</button>

          <button class="kpas-button btn-secondary" @click="submitCancelForm">Avbryt</button>
        </div>

        <span class="kpas-button-error" v-if="formErrorMessage != ''"><b>Error:</b> {{ formErrorMessage }}</span>
      </form>

      <form class="user-cancel-form" @submit.prevent="submitCancelForm" v-if="!formLoading && showCancelForm">
        <h4>Quarantine request</h4>

        <p>We have now recived a verified request to delete your user account at: <b>{{ formatDateToLocal(tokenPayload.createdAt) }}</b></p>
        <p>You have the option to cancel this request by clicking the button below for at least 30 days, after this you user account will permanently be deleted and cannot be recoverd.</p>

        <button class="kpas-button" type="submit">Avbryt slett meg</button>

        <span class="kpas-button-error" v-if="formErrorMessage != ''"><b>Error:</b> {{ formErrorMessage }}</span>
      </form>
    </div>
  </div>
</template>

<script>
import api from "../api";
import LoadingIndicator from "../components/LoadingIndicator"

export default {
  name: "UserDeletionView",
  components: {
    LoadingIndicator
  },
  data() {
    return {
      formLoading: true,
      formErrorMessage: "",
      tokenPayload: null,

      showDeleteForm: false,
      acceptedRisk: false,

      showVerifyForm: false,
      emailCode: "",

      showCancelForm: false,
    };
  },
  async mounted() {
    await this.getToken();
  },
  computed: {},
  methods: {
    showForm(name) {
      this.showDeleteForm = name == "delete";
      this.showVerifyForm = name == "verify";
      this.showCancelForm = name == "cancel";
    },
    formatDateToLocal(dateString) {
      const date = new Date(dateString);
      return date.toLocaleString();
    },
    async getToken() {
        try {
          const result = await api.get("/user/delete/token", {
            params: { cookie: window.cookie },
          });
          const noToken = result.status != 200;
          console.log("USER_DELETION_PAYLOAD", result.data)

          if (noToken) {
            this.tokenPayload = null;
            this.showForm("delete");
          } else {
            this.tokenPayload = result.data.payload;

            if (this.tokenPayload.confirmedAt != null) {
              this.showForm("cancel");
            } else {
              this.showForm("verify");
            }
          }
        } catch (e) {
          console.log("USER_DELETION_PAYLOAD_ERROR", e);
          this.tokenPayload = null;
          this.showForm("delete");
        }

        this.formLoading = false;
    },
    async submitDeleteForm() {
      this.formLoading = true;
      this.formErrorMessage = "";

      try {
        await api.post("/user/delete/token", {
            cookie: window.cookie
        });
      } catch (error) {
        this.formErrorMessage = error.response.data.message;
        console.error(error);
      } finally {
        await this.getToken();
      } 
    },
    async submitVerifyForm() {
      this.formLoading = true;
      this.formErrorMessage = "";

      try {
        await api.put("/user/delete/verify", {
            cookie: window.cookie,
            emailToken: this.emailCode
        });
        this.emailCode = "";
      } catch (error) {
        this.formErrorMessage = error.response.data.message;
        console.error(error);
      } finally {
        await this.getToken();
      }
    },
    async submitCancelForm() {
      this.formLoading = true;
      this.formErrorMessage = "";

      try {
        await api.put("/user/delete/cancel", {
            cookie: window.cookie
        });
      } catch (error) {
        this.formErrorMessage = error.response.data.message;
        console.error(error);
      } finally {
        await this.getToken();
      } 
    }
  }
};
</script>

<style scoped>
.user-deletion {
  max-width: 800px;
  margin: 0 auto;
}

.user-deletion-list {
  margin-bottom: 40px;
}

.user-deletion-list h3 {
  padding: 0px;
  padding-bottom: .5em;
}

.user-delete-form, .user-verify-form, .user-cancel-form {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
}

.user-delete-form-accept {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  width: 100%;
  max-width: 500px;
  padding: 20px;
  background: #ffebee;
  border-radius: 5px;
  margin-bottom: 20px;
  cursor: pointer;
}

.user-delete-form-accept input {
  margin-right: 15px;
}

.user-delete-form-accept p {
  margin-bottom: 0px;
}

@media only screen and (max-width: 500px) {
  .user-delete-form-accept {
    padding: 15px;
  }

  .user-delete-form-accept input {
    margin-right: 10px;
  }
}

.user-verify-form-code {
  display: flex;
  flex-direction: column;
  width: 100%;
  margin-bottom: 20px;
  margin-top: 10px;
}

.user-verify-form-buttons {
  display: flex;
  flex-wrap: wrap;
}

.user-verify-form-buttons button {
  margin-right: 20px;
}

.user-verify-form-code label {
  padding: 0;
  margin-bottom: 8px;
}

.user-verify-form-code input {
  max-width: 300px;
}

.kpas-button-error {
  margin-top: 15px;
  background: #ffebee;
  padding: 4px 15px;
  border-radius: 5px;
}
</style>
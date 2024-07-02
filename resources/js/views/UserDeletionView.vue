<template>
  <div class="user-deletion">
    <h2>Slett Meg</h2>

    <div>
      <p>Ved å benytte slett meg funksjonen kan du permanent fjerne kontoen din fra Kompetanseportalen. Vennligst vær oppmerksom på at denne handlingen er permanent og at brukeren ikke kan gjenopprettes.   Vi setter pris på tiden du har brukt hos oss og håper å se deg igjen i fremtiden!</p>

      <div class="user-deletion-list">
        <h3><b>Når du sletter kontoen din, godtar du følgende vilkår:</b></h3>

        <ul>
          <li>Jeg forstår at ved å slette kontoen min, vil min profil, tilgang til kurs, lagret fremgang i kurs, og kompetansebevis bli permanent fjernet etter en karanteneperiode på 30 dager.</li>
          <li>Jeg forstår at jeg kan angre meg og avbryte prossesen ved å logge inn igjen i løpet av karanteneperioden.</li>
          <li>Jeg bekrefter at jeg har lastet ned alle mine kompetansebevis og annen viktig informasjon før jeg sletter kontoen, da disse ikke kan gjenopprettes etter sletting.</li>
        </ul>
      </div>

      <div class="user-deletion-loading" v-if="formLoading">
        <LoadingIndicator />
      </div>

      <form class="user-delete-form" @submit.prevent="submitDeleteForm" v-if="!formLoading && showDeleteForm">
        <label class="user-delete-form-accept" for="accept-risk">
          <input id="accept-risk" type="checkbox" v-model="acceptedRisk" required>
          <p>Jeg godtar vilkårene for sletting av kontoen min</p>
        </label>

        <button class="kpas-button" type="submit" :disabled="!acceptedRisk">Send kode på epost</button>

        <span class="kpas-button-error" v-if="formErrorMessage != ''"><b>Error:</b> {{ formErrorMessage }}</span>
      </form>

      <form class="user-verify-form" @submit.prevent="submitVerifyForm" v-if="!formLoading && showVerifyForm">
        <h4>Verifiser slettingen</h4>

        <p>Du har nå motatt en epost med en verifiseringskode. Når du limer inn koden fra e-posten og trykker på "Verifiser kode", vil prosessen være over, og kontoen din vil bli satt i en karanteneperiode på 30 dager før den slettes permanent. I løpet av disse 30 dagene kan du ombestemme deg og gjenopprette kontoen ved å logge inn igjen og trykke avbryt. Etter karanteneperioden vil all informasjon knyttet til kontoen din bli permanent slettet.</p>

        <div class="user-verify-form-code">
          <label for="email-code">Bekreftelseskode:</label>

          <input id="email-code" v-model="emailCode" minlength="4" required>
        </div>

        <div class="user-verify-form-buttons">
          <button class="kpas-button" type="submit">Verifiser</button>

          <button class="kpas-button btn-secondary" @click="submitCancelForm">Avbryt</button>
        </div>

        <span class="kpas-button-error" v-if="formErrorMessage != ''"><b>Error:</b> {{ formErrorMessage }}</span>
      </form>

      <form class="user-cancel-form" @submit.prevent="submitCancelForm" v-if="!formLoading && showCancelForm">
        <h4>Karanteneperiode</h4>

        <p>Kontoen din er nå i en karanteneperiode på 30 dager. Hvis du ombestemmer deg i løpet av denne perioden, kan du logge inn igjen og trykke avbryt for å kansellere prosessen. Etter karanteneperioden vil all informasjon knyttet til kontoen din bli permanent slettet.</p>

        <button class="kpas-button" type="submit">Avbryt</button>

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
      return new Intl.DateTimeFormat('en-GB', {
        timeZone: 'Europe/Oslo',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      }).format(new Date(dateString + 'Z'));
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
  max-width: 380px;
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

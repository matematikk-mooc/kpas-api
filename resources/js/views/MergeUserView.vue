<template>
  <div>
    <h2>Slå sammen brukere</h2>
    <div v-if="error" class="alert alert-danger">{{ error }}</div>
    <button
      class="btn"
      :class="{
        'btn-primary': true,
      }"
      @click="fetchToken"
    >
      Lag sammenslåingskode
    </button>
    <div class="show-token">Kode: </div>
    <div>
      <input type="text" id="merge-user-token" name="token" />
    </div>
    <div class="course-intersection">
      Innmeldingskonflikter:
    </div>
    <div>
      <button
        class="btn"
        :class="{
          'btn-primary': true,
        }"
        @click="fetchIntersection"
      >
        Finn innmeldingskonflikter
      </button>
      <button
        class="btn"
        :class="{
          'btn-primary': true,
        }"
        @click="doMerge"
      >
        Slå sammen
      </button>
    </div>
  </div>
</template>

<script>
import api from "../api";
export default {
  name: "MergeUserView",
  data() {
    return {
      error: "",
    };
  },

  methods: {
    clearError(errorType) {
      this.error = "";
      this.iframeresize();
    },
    reportError(errorType, e) {     
      this.error = e + " Prøv igjen senere og ta kontakt med kompetansesupport@udir.no dersom feilen vedvarer.";
      this.iframeresize();
    },
    iframeresize() {
      this.$nextTick(function () {
        var h = $("body").height();
        parent.postMessage(
          JSON.stringify({ subject: "lti.frameResize", height: h }),
          "*"
        );
      });
    },
    async fetchToken() {
      try {
        console.log("Getting token");
        const token = await api.get("/user/merge/token", {
          params: { cookie: window.cookie },
        });
        document.querySelector(".show-token").textContent = "Kode: " + token.data;
        this.clearError("groupError");
      } catch (e) {
        this.reportError("groupError", "Kunne ikke hente sammenslåingskode.");
      }
    },
    async fetchIntersection() {
      try {
        const token = document.getElementById("merge-user-token").value;
        console.log("Getting intersection with token: " + token);

        const intersection = await api.get("/user/merge/intersection", {
          params: { cookie: window.cookie },
          headers: { "X-merge-token": token },
        });

        document.querySelector(".course-intersection").textContent = "Innmeldingskonflikter: " + intersection.data;
        this.clearError("groupError");
      } catch (e) {
        this.reportError(
          "groupError",
          "Kunne ikke hente innmeldingskonflikter."
        );
      }
    },
    async doMerge() {
      try {
        const token = document.getElementById("merge-user-token").value;
        console.log("Merging with " + token + " into user");

        const reponse = await api.get("/user/merge/perform", {
          params: { cookie: window.cookie },
          headers: { "X-merge-token": token },
        });

        if (reponse.status == 400) {
          this.reportError("groupError", "Kan ikke slå sammen bruker med seg selv.");
        }

        console.log("Merged");
        this.clearError("groupError");
      } catch (e) {
        this.reportError("groupError", "Kunne ikke slå sammen bruker");
      }
    },
  },
};
</script>
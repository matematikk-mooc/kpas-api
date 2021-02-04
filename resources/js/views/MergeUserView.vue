<template>
  <div>
    <h1>Slå sammen brukere</h1>
    <h2>1. Generer kode</h2>
    <p>
    For å flytte innholdet fra denne brukeren over til en annen, f.eks. fordi du har fått ny FeideId, 
    må du generere en sammenslåingskode ved å trykke på knappen nedenfor.
    </p>

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
    <span v-if="isGeneratingToken" class="ml-3">Genererer kode<div class="spinner-border text-danger"></div></span>

    <div v-show="tokenGenerated" class="show-token-instructions"></div>
    <pre v-show="tokenGenerated" class="show-token"></pre>

    <h2>2. Sjekk innmeldingskonflikter</h2>
    Dersom du har en kode fra en bruker du ønsker å slå sammen med brukeren du er logget inn med nå, skriver
    du inn koden nedenfor.
    <div>
      <input type="text" id="merge-user-token" name="token" />
    </div>
    <div class="course-intersection">
      Innmeldingskonflikter:
    </div>
    <merge-user-conflicts
      :conflicts="conflicts"
      :conflictsLoaded="conflictsLoaded"
    ></merge-user-conflicts>    
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
    <span v-if="isFetchingIntersection" class="ml-3">Sjekker konflikter<div class="spinner-border text-danger"></div></span>
    </div>
    <h2>3. Hent innhold</h2>
    Når du har sjekket at det ikke er noen innmeldingskonflikter kan du slå sammen brukerne.
    <div>
      <button
        class="btn"
        :disabled="!canMergeUsers"
        :class="{
          'btn-primary': true,
        }"
        @click="doMerge"
      >
        Slå sammen
      </button>
    <span v-if="isMergingUsers" class="ml-3">Sjekker konflikter<div class="spinner-border text-danger"></div></span>
    </div>
  </div>
</template>

<script>
import api from "../api";
import MergeUserConflicts from "../components/MergeUserConflicts";
export default {
  name: "MergeUserView",
  components: {
      MergeUserConflicts,
    },
  data() {
    return {
      isGeneratingToken: false,
      isFetchingIntersection: false,
      isMergingUsers: false,
      canMergeUsers: false,
      tokenGenerated: false,
      conflicts: null,
      conflictsLoaded: false,
      error: "",
    };
  },

  methods: {
    clearError(errorType) {
      this.error = "";
      this.iframeresize();
    },
    reportError(errorType, e) {     
      this.error = e;
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
        this.isGeneratingToken = true;
        console.log("Getting token");
        const token = await api.get("/user/merge/token", {
          params: { cookie: window.cookie },
        });
        this.isGeneratingToken = false;
        this.tokenGenerated = true;
        
        document.querySelector(".show-token-instructions").textContent = "Kopier denne koden og start sammenslåingsverktøyet for brukeren du vil slå denne brukeren sammen med:";
        document.querySelector(".show-token").textContent = token.data;
        this.clearError("groupError");
      } catch (e) {
        this.isGeneratingToken = false;
        this.reportError("groupError", "Kunne ikke hente sammenslåingskode.");
      }
    },
    async fetchIntersection() {
      try {
        this.isFetchingIntersection = true;
        const token = document.getElementById("merge-user-token").value;
        console.log("Getting intersection with token: " + token);

        const intersection = await api.get("/user/merge/intersection", {
          params: { cookie: window.cookie },
          headers: { "X-merge-token": token },
        });

        this.isFetchingIntersection = false;
        this.conflictsLoaded = true;
        this.canMergeUsers = !intersection.data.length;
        this.conflicts = intersection.data;
        //document.querySelector(".course-intersection").textContent = "Innmeldingskonflikter: " + intersection.data;
        this.clearError("groupError");
      } catch (e) {
        this.isFetchingIntersection = false;
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

        console.log("Merged");
        this.clearError("groupError");
      } catch (e) {
        this.reportError("groupError", e.response.data);
      }
    },
  },
};
</script>
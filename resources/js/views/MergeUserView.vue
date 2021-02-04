<template>
  <div>
    <br>
    <div v-if="mergeError" class="alert alert-danger">{{ mergeError }}</div>
    <div v-if="usersMerged" class='alert alert-success'>Sammenslåingen av brukerne var vellykket.</div>

    <div v-if="!moveContentState && !fetchContentState">
      <h3>Hva ønsker du å gjøre?</h3>
      <button class="kpas-button"
            @click="moveContent"
          >
        Flytt innhold fra denne brukeren over til en annen
      </button>
      <br><br>
      <button
            class="kpas-button"
            @click="fetchContent"
          >
        Hent innhold fra en annen bruker over til denne
      </button>
    </div>
    <div v-if="moveContentState">
      <h3>Flytt innhold til en annen bruker</h3>
For å flytte innhold fra denne brukeren over til en annen generer du først en
sammenslåingkode ved å trykke på knappen nedenfor.
      <br><br>
      <div>
        <button
          class="kpas-button"
          :disabled="isGeneratingToken || tokenGenerated"
          @click="fetchToken"
        >
          Generer sammenslåingskode
        </button>
      <span v-if="isGeneratingToken" class="ml-3">Genererer kode <div class="spinner-border text-danger"></div></span>
      &nbsp;&nbsp;&nbsp;<span v-show="tokenGenerated" class="show-token">{{ tokenData }} </span>
      </div>
      <br>
      <div v-show="tokenGenerated" class="show-token-instructions">
        Kopier koden og start sammenslåingsverktøyet for brukeren du vil slå denne brukeren sammen med.
        Koden er gyldig i en halvtime.
      </div>
    </div>
    <div v-if="fetchContentState">
      <h3>Hent innhold fra en annen bruker over til denne</h3>
Skriv inn koden du har laget for brukeren du ønsker å hente innhold fra. Dersom du ikke har en slik kode
må du først logge inn som brukeren du vil hente innhold fra og velge "Flytt innhold fra denne brukeren over til en annen"      <div>
<br>
        Kode: <input 
        :disabled="canMergeUsers" 
        type="text" 
        size="30" 
        id="merge-user-token" 
        name="token" />
<br><br>        
      </div>
Når du har skrevet inn koden trykker du på "Finn innmeldingskonflikter" for å undersøke om
de to brukerne er medlem av en eller flere like kompetansepakker. I så fall må konfliktene løses.      
        <br><br>
        <button
          class="kpas-button"
          :disabled="isFetchingIntersection || canMergeUsers"
          @click="fetchIntersection"
        >
          Finn innmeldingskonflikter
        </button>
      <span v-if="isFetchingIntersection" class="ml-3">Sjekker konflikter <div class="spinner-border text-danger"></div></span>
      </div>
      <br>
      <div v-if="conflictsLoaded && !canMergeUsers">
Følgende innmeldingskonflikter ble funnet. Du må melde deg av kompetansepakkene for en av brukerne før du kan slå de sammen.
        <merge-user-conflicts
          :conflicts="conflicts"
          :conflictsLoaded="conflictsLoaded"
        ></merge-user-conflicts>    
      </div>
      <div v-if="canMergeUsers">
      Ingen innmeldingskonflikter ble funnet. Trykk på "Slå sammen" for å flytte innholdet til denne brukeren.
      <br><br>
        <div>
          <button
            class="kpas-button"
            :disabled="isMergingUsers || usersMerged"
            @click="doMerge"
          >
            Slå sammen
          </button>
          <span v-if="isMergingUsers" class="ml-3">Slår sammen brukerne <div class="spinner-border text-danger"></div></span>
      </div>
    </div>
    <br>
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
      tokenData: "",
      conflicts: null,
      conflictsLoaded: false,
      fetchContentState: false,
      moveContentState: false,
      usersMerged: false,
      mergeError: "",
    };
  },
  computed: {
  	disableGenerateTokenButton: function() {
    	return this.isGeneratingToken;
    }
  },
  methods: {
    clearError(errorType) {
      this.mergeError = "";
      this.iframeresize();
    },
    reportError(errorType, e) {     
      this.mergeError = e;
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
    moveContent() {
      this.moveContentState = true;
    },
    fetchContent() {
      this.fetchContentState = true;
    },
    async fetchToken() {
      try {
        this.clearError("mergeError");
        this.isGeneratingToken = true;
        this.tokenData = "";
        console.log("Getting token");
        const token = await api.get("/user/merge/token", {
          params: { cookie: window.cookie },
        });
        this.isGeneratingToken = false;
        this.tokenGenerated = true;
        
        this.tokenData = token.data;
        this.clearError("mergeError");
      } catch (e) {
        this.isGeneratingToken = false;
        this.reportError("mergeError", "Kunne ikke hente sammenslåingskode. " + e.response.data);
      }
    },
    async fetchIntersection() {
      try {
        this.clearError("mergeError");
        this.conflictsLoaded = false;
        this.canMergeUsers = false;
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
        this.clearError("mergeError");
      } catch (e) {
        this.isFetchingIntersection = false;
        this.reportError(
          "mergeError",
          "Kunne ikke hente innmeldingskonflikter. " + e.response.data
        );
      }
    },
    async doMerge() {
      try {
        this.clearError("mergeError");
        this.isMergingUsers = true;
        const token = document.getElementById("merge-user-token").value;
        console.log("Merging with " + token + " into user");

        const reponse = await api.get("/user/merge/perform", {
          params: { cookie: window.cookie },
          headers: { "X-merge-token": token },
        });
        this.isMergingUsers = false;
        this.usersMerged = true;

        console.log("Merged");
        this.clearError("mergeError");
      } catch (e) {
        this.isMergingUsers = false;
        this.reportError("mergeError", e.response.data);
      }
    },
  },
};
</script>
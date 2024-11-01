<template>
  <div>
    <div class="selectors-groups">
      <label class="select-county">
        <p>Velg fylke</p>

        <v-select
          label="Navn"
          v-model="selectedCounty"
          placeholder="Fylke"
          :options="countiesState?.payload ?? []"
          :loading="countiesState.isLoading == true"
          v-if="countiesState.payload != null"
          @update:modelValue="handleCountyChange($event)"
        />

        <Message type="error" v-if="countiesState.error != null">{{ countiesState.error }}</Message>
      </label>

      <label class="select-community" v-if="selectedCounty != null">
        <p>Velg kommune</p>

        <v-select
          label="Navn"
          v-model="selectedCommunity"
          placeholder="Kommune"
          :options="communitiesState?.payload ?? []"
          :loading="communitiesState.isLoading == true"
          v-if="communitiesState.payload != null"
          @update:modelValue="handleCommunityChange($event)"
        />

        <Message type="error" v-if="communitiesState.error != null">{{ communitiesState.error }}</Message>
      </label>

      <label class="select-school" v-if="selectedCounty != null && selectedCommunity != null && institutionType === 'school'">
        <p>Velg skole</p>

        <v-select
          label="Navn"
          v-model="selectedSchool"
          placeholder="Skole"
          :options="schoolsState?.payload ?? []"
          :loading="schoolsState.isLoading == true"
          v-if="schoolsState.payload != null"
          @update:modelValue="handleSchoolChange($event)"
        />

        <Message type="error" v-if="schoolsState.error != null">{{ schoolsState.error }}</Message>
      </label>

      <label class="select-kindergarten" v-if="selectedCounty != null && selectedCommunity != null && institutionType === 'kindergarten'">
        <p>Velg barnehage</p>

        <v-select
          label="Navn"
          v-model="selectedKindergarten"
          placeholder="Barnehage"
          :single-line="true"
          density="compact"
          :options="kindergartensState?.payload ?? []"
          :loading="kindergartensState.isLoading == true"
          v-if="kindergartensState.payload != null"
          @update:modelValue="handleKindergartenChange($event)"
        />

        <Message type="error" v-if="kindergartensState.error != null">{{ kindergartensState.error }}</Message>
      </label>
    </div>
  </div>
</template>

<script>
  import api from '../api';
  import "vue-select/dist/vue-select.css";
  import Message from './Message.vue';

  export default {
  name: "GroupSelector",
    components: {
      Message
    },

    props: {
      courseId: Number,
      institutionType: String,
      selectedGroups: Object,
    },

    data() {
      return {
        countiesState: {
          payload: null,
          isLoading: true,
          error: null
        },
        communitiesState: {
          countyNumber: null,
          payload: null,
          isLoading: true,
          error: null
        },
        schoolsState: {
          communityNumber: null,
          payload: null,
          isLoading: true,
          error: null
        },
        kindergartensState: {
          communityNumber: null,
          payload: null,
          isLoading: true,
          error: null
        },
      }
    },

    async created() {
      await this.getCounties();
      if (this.selectedCounty != null) await this.getCommunities(this.selectedCounty.Fylkesnr);
      if (this.institutionType == 'school' && this.selectedCommunity != null) await this.getSchools(this.selectedCommunity.Kommunenr);
      else if (this.institutionType == 'kindergarten' && this.selectedCommunity != null) await this.getKindergartens(this.selectedCommunity.Kommunenr);
    },

    watch: {
      selectedGroups: {
        deep: true,
        handler: async function () {
          const newCountyFylkesnr = this.selectedCounty?.Fylkesnr;
          if (newCountyFylkesnr != null && newCountyFylkesnr != this.communitiesState?.countyNumber) await this.getCommunities(newCountyFylkesnr);
          const newCommunityKommunenr = this.selectedCommunity?.Kommunenr;
          
          const fetchSchools = this.institutionType == 'school' && newCommunityKommunenr != null && newCommunityKommunenr != this.schoolsState?.communityNumber;
          if (fetchSchools) await this.getSchools(newCommunityKommunenr);

          const fetchKindergartens = this.institutionType == 'kindergarten' && newCommunityKommunenr != null && newCommunityKommunenr != this.kindergartensState?.communityNumber;
          if (fetchKindergartens) await this.getKindergartens(newCommunityKommunenr);
        }
      }
    },

    computed: {
      selectedCounty() {
        if (this.selectedGroups?.Fylke?.name == null) return null;
        return this.countiesState.payload?.find(county => 
          county.Navn?.replace(" ", "")?.toLowerCase() == this.selectedGroups.Fylke.name?.replace(" ", "")?.toLowerCase()
        );
      },
      selectedCommunity() {
        if (this.selectedGroups?.Kommune?.name == null) return null;
        return this.communitiesState.payload?.find(community => 
          community.Navn?.replace(" ", "")?.toLowerCase() == this.selectedGroups.Kommune.name?.replace(" ", "")?.toLowerCase()
        );
      },
      selectedSchool() {
        if (this.selectedGroups?.Skole?.name == null) return null;
        return this.schoolsState.payload?.find(school =>
          school.FulltNavn?.replace(" ", "")?.toLowerCase() == this.selectedGroups.Skole.name?.replace(" ", "")?.toLowerCase()
        );
      },
      selectedKindergarten() {
        if (this.selectedGroups?.Barnehage?.name == null) return null;
        return this.kindergartensState.payload?.find(kindergarten => 
          kindergarten.FulltNavn?.replace(" ", "")?.toLowerCase() == this.selectedGroups.Barnehage.name?.replace(" ", "")?.toLowerCase()
        );
      }
    },

    methods: {
      async getCounties() {
        this.countiesState.isLoading = true;
        this.countiesState.payload = null;
        this.countiesState.error = null;

        await api.get('/nsr/counties')
          .then((response) => this.countiesState.payload = response.data.result)
          .catch(() => this.countiesState.error = "Kunne ikke hente fylker fra nasjonalt skoleregister.");

        this.countiesState.isLoading = false;
      },
      handleCountyChange(newCounty){
        const newSelectedGroups = this.selectedGroups;
        delete newSelectedGroups.Fylke;
        delete newSelectedGroups.Kommune;
        delete newSelectedGroups.Skole;
        delete newSelectedGroups.Barnehage;

        if (newCounty != null) {
          newSelectedGroups.Fylke = {
            name: newCounty.Navn,
            description: `courseId:${this.courseId}:county:${newCounty.Fylkesnr}:${newCounty.OrgNr}`,
            courseId: `${this.courseId}`,
            countyId: `${newCounty.Fylkesnr}`,
            orgNr: `${newCounty.OrgNr}`,
          };
        }

        this.$emit('updateSelectedGroups', newSelectedGroups);
      },

      async getCommunities(countyNumber) {
        this.communitiesState.isLoading = true;
        this.communitiesState.payload = null;
        this.communitiesState.error = null;

        await api.get(`/nsr/counties/${countyNumber}/communities`)
          .then((response) => this.communitiesState.payload = response.data.result)
          .catch(() => this.communitiesState.error = "Kunne ikke hente kommuner fra nasjonalt skoleregister.");

        this.communitiesState.countyNumber = this.selectedCounty?.Fylkesnr;
        this.communitiesState.isLoading = false;
        
      },
      handleCommunityChange(newCommunity){
        const newSelectedGroups = { ...this.selectedGroups };
        delete newSelectedGroups.Kommune;
        delete newSelectedGroups.Skole;
        delete newSelectedGroups.Barnehage;

        if (newCommunity != null) {
          const isOther = newCommunity.Navn == "Annen";
          newSelectedGroups.Kommune = {
            name: `${newCommunity.Navn}`,
            description: `courseId:${this.courseId}:community:${newCommunity.Kommunenr}:${newCommunity.OrgNr}`,
            courseId: `${this.courseId}`,
            countyId: isOther ? 99 : `${this.selectedCounty.Fylkesnr}`,
            communityId: isOther ? 99 : `${newCommunity.Kommunenr}`,
            orgNr: `${newCommunity.OrgNr}`,
          };

          if (this.institutionType == 'school') this.getSchools(newCommunity.Kommunenr);
          else if (this.institutionType == 'kindergarten') this.getKindergartens(newCommunity.Kommunenr);
        }

        this.$emit('updateSelectedGroups', newSelectedGroups);
      },

      async getSchools(communityNumber) {
        this.schoolsState.isLoading = true;
        this.schoolsState.payload = null;
        this.schoolsState.error = null;

        await api.get(`/nsr/communities/${communityNumber}/schools`)
          .then((response) => this.schoolsState.payload = response.data.result)
          .catch(() => this.schoolsState.error = "Kunne ikke hente skoler fra nasjonalt skoleregister.");

        this.schoolsState.communityNumber = this.selectedCommunity?.Kommunenr;
        this.schoolsState.isLoading = false;
      },
      handleSchoolChange(newSchool){
        const newSelectedGroups = { ...this.selectedGroups };
        delete newSelectedGroups.Skole;

        if (newSchool != null) {
          const isOther = newSchool.Navn == "Annen";
          newSelectedGroups.Skole = {
            name: `${newSchool.FulltNavn}`,
            description: `courseId:${this.courseId}:${this.institutionType}:${newSchool.NSRId}:${newSchool.OrgNr}`,
            courseId: `${this.courseId}`,
            countyId: isOther ? 99 : `${this.selectedCounty.Fylkesnr}`,
            communityId: isOther ? 99 : `${this.selectedCommunity.Kommunenr}`,
            orgNr: `${newSchool.OrgNr}`,
          };
        }

        this.$emit('updateSelectedGroups', newSelectedGroups);
      },

      async getKindergartens(communityNumber) {
        this.kindergartensState.isLoading = true;
        this.kindergartensState.payload = null;
        this.kindergartensState.error = null;

        await api.get(`/kindergartens/${communityNumber}`)
          .then((response) => this.kindergartensState.payload = response.data.result)
          .catch(() => this.kindergartensState.error = "Kunne ikke hente barnehager fra kpas-api.");

        this.kindergartensState.communityNumber = this.selectedCommunity?.Kommunenr;
        this.kindergartensState.isLoading = false;
      },
      handleKindergartenChange(newKindergarten){
        const newSelectedGroups = { ...this.selectedGroups };
        delete newSelectedGroups.Barnehage;

        if (newKindergarten != null) {
          const isOther = newKindergarten.Navn == "Annen";
          newSelectedGroups.Barnehage = {
            name: `${newKindergarten.FulltNavn}`,
            description: `courseId:${this.courseId}:${this.institutionType}:${newKindergarten.NSRId}:${newKindergarten.OrgNr}`,
            courseId: `${this.courseId}`,
            countyId: isOther ? 99 : `${this.selectedCounty.Fylkesnr}`,
            communityId: isOther ? 99 : `${this.selectedCommunity.Kommunenr}`,
            orgNr: `${newKindergarten.OrgNr}`,
          };
        }

        this.$emit('updateSelectedGroups', newSelectedGroups);
      }
    }
  }
</script>

<style scoped>
  .selectors-groups {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    gap: 20px;
  }

  .selectors-groups label {
    display: flex;
    flex-direction: column;
    width: auto;
    min-width: 250px;
    max-width: 300px;
    padding: 0px;
  }

  .selectors-groups label p {
    margin-bottom: 5px;
  }
</style>

<template>
  <div>
    <div v-bind:style=" chosenCounty && chosenCommunity && (chosenInstitution || !institutionType)? 'border: none;' : 'padding: 10px; border: 1px solid red;' " >

      <span v-if="institutionType === 'school'">&#9432;</span>
      <span v-else-if="institutionType === 'kindergarten'">&#9432;</span>

      <label class="select-county col-sm">
        Fylke:<br/>
        <select
          v-model="chosenCounty"
          :disabled="!counties.length"
        >
          <option value="" selected disabled>--- Fylke ---</option>
          <option
            v-for="county in counties"
            :value="county"
            v-text="county.Navn"
          ></option>
        </select>
      </label>
      <label class="select-community col-sm">
        Kommune:<br/>
        <select
          v-model="chosenCommunity"
          :disabled="!communities.length"
        >
          <option value="" selected disabled>--- Kommune ---</option>
          <option
            v-for="communities in communities"
            :value="communities"
            v-text="communities.Navn"
          ></option>
        </select>
      </label>
      <label class="select-school col-sm" v-if="institutionType === 'school'">
        Skole:<br/>
        <select
          v-model="chosenInstitution"
          :disabled="!schools.length"
        >
          <option value="" selected disabled>--- Skole ---</option>
          <option
            v-for="school in schools"
            :value="school"
            v-text="school.FulltNavn"
          ></option>
        </select>
      </label>
      <label class="select-school col-sm" v-if="institutionType === 'kindergarten'">
        Barnehage:<br/>
        <select
          v-model="chosenInstitution"
          :disabled="!kindergartens.length"
        >
          <option value="" selected disabled>--- Barnehage ---</option>
          <option
            v-for="kindergarten in kindergartens"
            :value="kindergarten"
            v-text="kindergarten.FulltNavn"
          ></option>
        </select>
      </label>
    </div>
    <div v-if="error"
         class="alert alert-danger">{{error}}
    </div>
  </div>
</template>

<script>
  import api from '../api';

  export default {
    name: "GroupSelector",
    props: {
      courseId: Number,
      institutionType: String
    },

    data() {
      return {
        isLoading: false,
        selectedgroups: {},
        counties: [],
        communities: [],
        schools: [],
        kindergartens: [],
        chosenCounty: null,
        chosenCommunity: null,
        chosenInstitution: null,
        error: '',
      }
    },

    methods: {
      clearError() {
        this.error = "";
      },
      reportError(e) {
        this.error = e + " Prøv igjen senere og ta kontakt med kompetansesupport@udir.no dersom feilen vedvarer.";
        this.$parent.iframeresize();
      },
      async getCounties() {
        try {
          const result = await api.get('/nsr/counties');
          this.counties = result.data.result;
          this.clearError();
        } catch (e) {
          this.reportError("Kunne ikke hente fylker fra nasjonalt skoleregister.");
        }
      },
      async getCommunities(countyNo) {
        try {
          const result = await api.get(`/nsr/counties/${countyNo}/communities`);
          this.communities = result.data.result;
          this.clearError();
        } catch (e) {
          this.reportError("Kunne ikke hente kommuner fra nasjonalt skoleregister.");
        }
      },

      async getSchools(communityNo) {
        try {
          const result = await api.get(`/nsr/communities/${communityNo}/schools`);
          this.schools = result.data.result;
          this.clearError();
        } catch (e) {
          this.reportError("Kunne ikke hente skoler fra kpas-api.");
        }
      },
      async getKindergartens(communityNo) {
        try {
          const result = await api.get(`/kindergartens/${communityNo}`);
          this.kindergartens = result.data.result;
          this.clearError();
        } catch (e) {
          this.reportError("Kunne ikke hente barnehager fra kpas-api.");
        }
      },

      getCountyGroup() {
        return {
          name: this.chosenCounty.Navn,
          description: `courseId:${this.courseId}:county:${this.chosenCounty.Fylkesnr}:${this.chosenCounty.OrgNr}`
        };
      },
      getCommunityGroup() {
        return {
          name: `${this.chosenCommunity.Navn}`,
          description: `courseId:${this.courseId}:community:${this.chosenCommunity.Kommunenr}:${this.chosenCommunity.OrgNr}`,
        };
      },
      getInstitutionGroup() {
        return {
          name: `${this.chosenInstitution.FulltNavn}`,
          description: `courseId:${this.courseId}:${this.institutionType}:${this.chosenInstitution.NSRId}:${this.chosenInstitution.OrgNr}`,
        };
      },
      async assignToGroups() {
        this.$emit('updateGroups', this.selectedgroups);
      },
    },

    async created() {
      await this.getCounties();
    },
    updated() {
      this.$emit('update');
    },

    watch: {
      async chosenCounty(county) {
        delete this.selectedgroups.community;
        delete this.selectedgroups.institution;
        this.selectedgroups.county = this.getCountyGroup();

        this.communities = [];
        this.schools = [];
        this.kindergartens = [];

        this.assignToGroups();

        await this.getCommunities(county.Fylkesnr);
      },
      async chosenCommunity(community) {
        delete this.selectedgroups.institution;
        this.selectedgroups.community = this.getCommunityGroup();
        this.assignToGroups();
        
        if (this.institutionType === "school") {
          this.schools = [];
          await this.getSchools(community.Kommunenr);
        } else if (this.institutionType === "kindergarten") {
          this.kindergartens = [];
          await this.getKindergartens(community.Kommunenr);
        } 
      },
      async chosenInstitution(institution) {
        this.selectedgroups.institution = this.getInstitutionGroup();
        this.assignToGroups();
      }
    }
  }
</script>

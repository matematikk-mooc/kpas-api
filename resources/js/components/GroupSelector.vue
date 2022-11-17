<template>
  <div>
    <div v-bind:style=" chosenCounty && chosenCommunity && (chosenInstitution || !institutionType)? 'border: none;' : 'padding: 10px; border: 1px solid red;' " >

      <span v-if="institutionType === 'school'" v-tooltip.top-center="`
      Listene viser alle fylker, kommuner og organisasjoner i Nasjonalt skoleregister.`">&#9432;</span>
      <span v-else-if="institutionType === 'kindergarten'" v-tooltip.top-center="`
      Listene viser alle fylker, kommuner og organisasjoner i Nasjonalt barnehageregister.
      `">&#9432;</span>
      
      
      <label class="select-county col-sm">Fylke:<br/>
        <v-select
          v-model="chosenCounty"
          :options="counties"  placeholder="--- Fylke ---" >
        </v-select>
      </label>
      <label class="select-community col-sm">
        Kommune:<br/>
        <v-select
          v-model="chosenCommunity"
          :options="communities"  placeholder="--- Kommune ---" >
        </v-select>
      </label>
      <label class="select-school col-sm" v-if="institutionType === 'school'">
        Skole:<br/>
        <v-select
          v-model="chosenInstitution"
          :options="schools"  placeholder="--- Skole ---" >
        </v-select>
      </label>
      <label class="select-school col-sm" v-if="institutionType === 'kindergarten'">
        Barnehage:<br/>
        <v-select
          v-model="chosenInstitution"
          :options="kindergartens"  placeholder="--- Barnehage ---" >
        </v-select>
      </label>
    </div>
    <div v-if="error"
         class="alert alert-danger">{{error}}
    </div>
  </div>
</template>

<script>
  import api from '../api';
  import 'floating-vue/dist/style.css';
  import "vue-select/dist/vue-select.css";

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
        counties: ['test1', 'test2', 'test3'],
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
        console.log(this.chosenCounty);

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

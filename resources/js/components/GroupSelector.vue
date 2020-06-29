<template>
  <div>
    <div class="row pt-3 pb-3 mt-3">
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
      <label class="select-school col-sm" v-if="institution === 'school'">
        Skole:<br/>
        <select
          v-model="chosenInstitution"
          :disabled="!schools.length"
        >
          <option value="" selected disabled>--- Skole ---</option>
          <option
            v-for="school in schools"
            :value="school"
            v-text="school.Navn"
          ></option>
        </select>
      </label>
      <label class="select-school col-sm" v-if="institution === 'kindergarten'">
        Barnehage:<br/>
        <select
          v-model="chosenInstitution"
          :disabled="!kindergartens.length"
        >
          <option value="" selected disabled>--- Barnehage ---</option>
          <option
            v-for="kindergarten in kindergartens"
            :value="kindergarten"
            v-text="kindergarten.Navn"
          ></option>
        </select>
      </label>
      <span v-tooltip.top-center="`
      Listene viser alle fylker, kommuner og organisasjoner i Nasjonalt skoleregister.
      `">&#9432;</span>
    </div>
    <div v-if="error"
         class="alert alert-danger">{{error}}
    </div>
  </div>
</template>

<script>
  import api from '../api';
  import { VTooltip} from 'v-tooltip';

  export default {
    name: "GroupSelector",
    props: {
      courseId: Number
    },

    data() {
      return {
        isLoading: false,
        counties: [],
        communities: [],
        schools: [],
        kindergartens: [],
        institution: null,
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
      async getInstitution() {
        try {
          const result = await api.post('/institution', {
            cookie: window.cookie,
          });
          this.institution = result.data;
          this.clearError();
        } catch (e) {
          this.reportError("Kunne ikke hente institution type.");
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

      async assignToGroups() {
        const county = {
          name: this.chosenCounty.Navn,
          description: `courseId:${this.courseId}:county:${this.chosenCounty.Fylkesnr}:${this.chosenCounty.OrgNr}`
        };
        const community = {
          name: `${this.chosenCommunity.Navn}`,
          description: `courseId:${this.courseId}:community:${this.chosenCommunity.Kommunenr}:${this.chosenCommunity.OrgNr}`,
        };
        const institution = {
          name: `${this.chosenInstitution.Navn}`,
          description: `courseId:${this.courseId}:${this.institution}:${this.chosenInstitution.NSRId}:${this.chosenInstitution.OrgNr}`,
        };

        this.$emit('input', {
          county,
          community,
          institution,
        })
      },
    },

    async created() {
      await this.getInstitution();
      await this.getCounties();


    },

    watch: {
      async chosenCounty(county) {
        this.communities = [];
        this.schools = [];
        await this.getCommunities(county.Fylkesnr);
      },
      async chosenCommunity(community) {
        if (this.institution === "school") {
          this.schools = [];
          await this.getSchools(community.Kommunenr);
        } else if (this.institution === "kindergarten") {
          this.kindergartens = [];
          await this.getKindergartens(community.Kommunenr);
        }
      },
      chosenInstitution() {
        this.assignToGroups();
      }
    }
  }
</script>

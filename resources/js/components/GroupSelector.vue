<template>
  <div>
    <div class="row pt-3 pb-3 border-top mt-3">
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
        <label class="select-school col-sm">
            Skole:<br/>
            <select
              v-model="chosenSchool"
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
      <span v-tooltip.top-center="`
      Alle firmaer som er registrert med næringskode 85 (undervisning) i Brønnøysundregisteret vises i listen over skoler.
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
        chosenCounty: null,
        chosenCommunity: null,
        chosenSchool: null,
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
        } catch(e)
        {
          this.reportError("Kunne ikke hente fylker fra nasjonalt skoleregister.");
        }
      },

      async getCommunities(countyNo) {
        try {
          const result = await api.get(`/nsr/communities/${countyNo}`);
          this.communities = result.data.result;
          this.clearError();
        } catch(e)
        {
          this.reportError("Kunne ikke hente kommuner fra nasjonalt skoleregister.");
        }
      },

      async getSchools(communityNo) {
        try {
          const result = await api.get(`/nsr/schools/${communityNo}`);
          this.schools = result.data.result;
          this.clearError();
        } catch(e)
        {
          this.reportError("Kunne ikke hente skoler fra nasjonalt skoleregister.");
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
        const school = {
          name: `${this.chosenSchool.Navn}`,
          description: `courseId:${this.courseId}:school:${this.chosenSchool.NSRId}:${this.chosenSchool.OrgNr}`,
        };

        this.$emit('input', {
          county,
          community,
          school,
        })
      },
    },

    async created() {
      await this.getCounties();
    },

    watch:{
      async chosenCounty(county) {
        this.communities = [];
        this.schools = [];
        await this.getCommunities(county.Fylkesnr);
      },
      async chosenCommunity(community) {
        this.schools = [];
        await this.getSchools(community.Kommunenr);
      },
      chosenSchool() {
        this.assignToGroups();
      }
    }
  }
</script>

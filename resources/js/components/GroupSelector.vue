<template>
    <div>
        <label>
          Fylke:
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
        <label>
          Kommune:
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
        <label>
            Schole:
            <select
              v-model="chosenSchool"
              :disabled="!schools.length"
            >
                <option value="" selected disabled>--- Schole ---</option>
                <option
                    v-for="school in schools"
                    :value="school"
                    v-text="school.Navn"
                ></option>
            </select>
        </label>
    </div>
</template>

<script>
  import api from '../api';

  export default {
    name: "GroupSelector",

    data() {
      return {
        isLoading: false,
        counties: [],
        communities: [],
        schools: [],
        chosenCounty: null,
        chosenCommunity: null,
        chosenSchool: null,
      }
    },

    methods: {
      async getCounties() {
        const result = await api.get('/nsr/counties');
        this.counties = result.data.result;
      },

      async getCommunities(countyNo) {
        const result = await api.get(`/nsr/communities/${countyNo}`);
        this.communities = result.data.result;
      },

      async getSchools(communityNo) {
        const result = await api.get(`/nsr/schools/${communityNo}`);
        this.schools = result.data.result;
      },

      async assignToGroups() {
        const county = {
          name: this.chosenCounty.Navn,
          description: `county:${this.chosenCounty.Fylkesnr}:${this.chosenCounty.OrgNr}`
        };
        const community = {
          name: `${this.chosenCounty.Navn}:${this.chosenCommunity.Navn}`,
          description: `community:${this.chosenCommunity.Kommunenr}:${this.chosenCommunity.OrgNr}`,
        };
        const school = {
          name: `${this.chosenCounty.Navn}:${this.chosenCommunity.Navn}:${this.chosenSchool.Navn}`,
          description: `school:${this.chosenSchool.NSRId}:${this.chosenSchool.OrgNr}`,
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
        await this.getCommunities(county.Fylkesnr);
      },
      async chosenCommunity(community) {
        await this.getSchools(community.Kommunenr);
      },
      chosenSchool() {
        this.assignToGroups();
      }
    }
  }
</script>

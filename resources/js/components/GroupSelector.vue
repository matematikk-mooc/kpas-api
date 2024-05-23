<template>
  <div>
      <span v-if="institutionType === 'school'">
        Listene viser alle fylker, kommuner og organisasjoner i Nasjonalt skoleregister.
      </span>
      <span v-if="institutionType === 'kindergarten'">
        Listene viser alle fylker, kommuner og organisasjoner i Nasjonalt barnehageregister.
      </span>

      <Message type="error" v-if="hasFormError">
        Feltene er obligatoriske, vennligst velg en på alle feltene.
      </Message>
      <div class="selectors-groups">
      <label class="select-county">Fylke:<br/>
        <v-select
          v-model="chosenCounty"
          :options="counties"
          label="Navn"
          :placeholder="placeholderCounty"
          :close-on-select="true"
          @blur="validateOnBlur(chosenCounty)"
          :clearable="false">
        </v-select>
      </label>
      <label class="select-community">
        Kommune:<br/>
        <v-select
          v-model="chosenCommunity"
          :disabled="!communities.length"
          :options="communities"
          label="Navn"
          :placeholder="placeholderCommunity"
          :close-on-select="true"
          :clearable="false"
           @blur="validateOnBlur(chosenCommunity)"
          :reset-on-options-change="true">
        </v-select>
      </label>
      <label class="select-school" v-if="institutionType === 'school'" >
        Skole:<br/>
        <v-select
          v-model="chosenInstitution"
          :disabled="!schools.length"
          :options="schools"
          label="FulltNavn"
          :placeholder="placeholderSchool"
          :close-on-select="true"
          :clearable="false"
           @blur="validateOnBlur(chosenInstitution)"
          :reset-on-options-change="true">
        </v-select>
      </label>
      <label class="select-school" v-if="institutionType === 'kindergarten'">
        Barnehage:<br/>
        <v-select
          v-model="chosenInstitution"
          :disabled="!kindergartens.length"
          :options="kindergartens"
          label="FulltNavn"
          :placeholder="placeholderKindergarten"
          :close-on-select="true"
          :clearable="false"
           @blur="validateOnBlur(chosenInstitution)"
          :reset-on-options-change="true">
        </v-select>
      </label>
    </div>
    </div>

    <Message type="error" v-if="error">
      {{error}}
    </Message>
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
      currentGroups: Object
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
        hasFormError: false,
        placeholderSchool: this.currentGroups['Skole'] ? this.currentGroups['Skole'].name : 'Skole',
        placeholderKindergarten: this.currentGroups['Barnehage'] ? this.currentGroups['Barnehage'].name : 'Barnehage',
        placeholderCommunity: this.currentGroups['Kommune'] ? this.currentGroups['Kommune'].name : 'Kommune',
        placeholderCounty: this.currentGroups['Fylke'] ? this.currentGroups['Fylke'].name : 'Fylke',
      }
    },

    methods: {
      clearError() {
        this.error = "";
        this.hasFormError = false;
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
          description: `courseId:${this.courseId}:county:${this.chosenCounty.Fylkesnr}:${this.chosenCounty.OrgNr}`,
          courseId: `${this.courseId}`,
          countyId: `${this.chosenCounty.Fylkesnr}`,
          orgNr: `${this.chosenCounty.OrgNr}`,
        };
      },
      validateOnBlur(formValue) {
        if (formValue === null || formValue.length < 1) {
          this.hasFormError=true
        }
      },
      getCommunityGroup() {
        if(this.chosenCommunity.Navn === "Annen"){
          return {
            name: `${this.chosenCommunity.Navn}`,
            description: `courseId:${this.courseId}:community:${this.chosenCommunity.Kommunenr}:${this.chosenCommunity.OrgNr}`,
            courseId: `${this.courseId}`,
            countyId: 99,
            communityId: 99,
            orgNr: `${this.chosenCommunity.OrgNr}`,
          };
        }
        return {
          name: `${this.chosenCommunity.Navn}`,
          description: `courseId:${this.courseId}:community:${this.chosenCommunity.Kommunenr}:${this.chosenCommunity.OrgNr}`,
          courseId: `${this.courseId}`,
          countyId: `${this.chosenCounty.Fylkesnr}`,
          communityId: `${this.chosenCommunity.Kommunenr}`,
          orgNr: `${this.chosenCommunity.OrgNr}`,
        };
      },
      getInstitutionGroup() {
        if (this.chosenInstitution.FulltNavn === "Annen"){
          return {
            name: `${this.chosenInstitution.FulltNavn}`,
            description: `courseId:${this.courseId}:${this.institutionType}:${this.chosenInstitution.NSRId}:${this.chosenInstitution.OrgNr}`,
            courseId: `${this.courseId}`,
            countyId: 99,
            communityId: 99,
            orgNr: `${this.chosenInstitution.OrgNr}`,
          };
        }
        return {
          name: `${this.chosenInstitution.FulltNavn}`,
          description: `courseId:${this.courseId}:${this.institutionType}:${this.chosenInstitution.NSRId}:${this.chosenInstitution.OrgNr}`,
          courseId: `${this.courseId}`,
          countyId: `${this.chosenCounty.Fylkesnr}`,
          communityId: `${this.chosenCommunity.Kommunenr}`,
          orgNr: `${this.chosenInstitution.OrgNr}`,
        };
      },
      async assignToGroups() {
        this.$emit('updateGroups', this.selectedgroups);
      },
    },

    async created() {
      await this.getCounties();
      console.log(this.currentGroups)
    },
    updated() {
      this.$emit('update');
    },

    watch: {
      async chosenCounty(county) {
        delete this.selectedgroups.community;
        delete this.selectedgroups.institution;

        this.communities = [];
        this.schools = [];
        this.kindergartens = [];

        this.placeholderCommunity = 'Kommune';
        this.placeholderSchool = 'Skole';
        this.placeholderKindergarten = 'Barnehage';

        if (county == null) {
          this.selectedgroups = {};
          this.placeholderCounty = 'Fylke';
        } else {
          this.selectedgroups.county = this.getCountyGroup();
          this.assignToGroups();
          await this.getCommunities(county.Fylkesnr);
        }
      },
      async chosenCommunity(community) {
        delete this.selectedgroups.institution;

        this.schools = [];
        this.kindergartens = [];
        this.placeholderSchool = 'Skole';
        this.placeholderKindergarten = 'Barnehage';


        if (community == null) {
          delete this.selectedgroups.community;
          this.placeholderCommunity = 'Kommune';
        } else {
          this.selectedgroups.community = this.getCommunityGroup();
          this.assignToGroups();

          if (this.institutionType === "school") {
            await this.getSchools(community.Kommunenr);
          } else if (this.institutionType === "kindergarten") {
            await this.getKindergartens(community.Kommunenr);
          }
        }
      },
      async chosenInstitution(institution) {
        if (institution == null) {
          delete this.selectedgroups.institution;

          this.placeholderSchool = 'Skole';
          this.placeholderKindergarten = 'Barnehage';
        } else {
          this.selectedgroups.institution = this.getInstitutionGroup();
          this.assignToGroups();
        }
      }
    }
  }
</script>

<style scoped>
  .selectors-groups {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .selectors-groups label {
    width: 100%;
  }
</style>
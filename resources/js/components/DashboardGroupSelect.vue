<template>
	<section class="dropdown-section">
		<label v-if="settings.custom_county_category_id" id="select-county">
			{{ settings.custom_county_category_name }}:<br />
			<v-select class="gselect"
				v-model="chosenCounty"
				:clearable="true"
				:disabled="!counties.length"
				:options="counties"
				label="name"
				placeholder="--- Fylke ---"
				:close-on-select="true"
				:reset-on-options-change="true">
			</v-select>
		</label>
		<label v-if="settings.custom_community_category_id" id="select-community">
			{{ settings.custom_community_category_name }}:<br />
			<v-select class="gselect"
				v-model="chosenCommunity"
				:clearable="true"
				:disabled="!communities.length"
				:options="communities"
				label="name"
				placeholder="--- Kommune ---"
				:close-on-select="true"
				:reset-on-options-change="true">
			</v-select>
		</label>
		<label v-if="settings.custom_institution_category_id" id="select-institution">
			{{ categories.find(e=> e.id == settings.custom_institution_category_id).name }}:<br />
			<v-select class="gselect"
				v-model="chosenInstitution"
				:clearable="true"
				:disabled="!institutions.length"
				:options="institutions"
				label="name"
				placeholder="--- Institusjon ---"
				:close-on-select="true"
				:reset-on-options-change="true">
			</v-select>
		</label>
	</section>
	<section class="dropdown-section">
		<label v-if="settings.custom_national_faculty_category_id" id="select-facultynational">
			Faggruppe nasjonal:<br />
			<v-select class="gselect"
				v-model="chosenFacultyNational"
				:clearable="true"
				:disabled="!facultiesNational.length"
				:options="facultiesNational"
				label="name"
				placeholder="--- Faggruppe (nasjonalt) ---"
				:close-on-select="true"
				:reset-on-options-change="true">
			</v-select>
		</label>
		<label v-if="settings.custom_county_faculty_category_id" id="select-facultycounty">
			{{ settings.custom_county_faculty_category_name }}<br />
			<v-select class="gselect"
				v-model="chosenFacultyCounty"
				:clearable="true"
				:disabled="!facultiesCounty.length"
				:options="facultiesCounty"
				label="name"
				placeholder="--- Faggruppe (fylke) ---"
				:close-on-select="true"
				:reset-on-options-change="true">
		</v-select>
		</label>
		<label v-if="settings.custom_community_faculty_category_id" id="select-facultycommunity">
			{{ settings.custom_community_faculty_category_name }}:<br />
			<v-select class="gselect"
				v-model="chosenFacultyCommunity"
				:clearable="true"
				:disabled="!facultiesCommunity.length"
				:options="facultiesCommunity"
				label="name"
				placeholder="--- Faggruppe (kommune) ---"
				:close-on-select="true"
				:reset-on-options-change="true">
			</v-select>
		</label>
	</section>
	<section class="dropdown-section">
		<label v-if="settings.custom_county_principals_category_id" id="select-countyleader">
			{{ settings.custom_county_principals_category_name }}:<br />
			<v-select class="gselect"
				v-model="chosenCountyLeader"
				:clearable="true"
				:disabled="!leaderCountyGroups.length"
				:options="leaderCountyGroups"
				label="name"
				placeholder="--- Leder/Eier (fylke) ---"
				:close-on-select="true"
				:reset-on-options-change="true">
			</v-select>
		</label>
		<label v-if="settings.custom_community_principals_category_id" id="select-leadercommunity">
			{{ settings.custom_community_principals_category_name }}:<br />
			<v-select class="gselect"
				v-model="chosenCommunityLeader"
				:clearable="true"
				:disabled="!leaderCommunityGroups.length"
				:options="leaderCommunityGroups"
				label="name"
				placeholder="--- Leder/Eier (kommune) ---"
				:close-on-select="true"
				:reset-on-options-change="true">
			</v-select>
		</label>
	</section>
<Message type="error" v-if="isError">{{error}}</Message>

</template>

<script>
import api from '../api';
import "vue-select/dist/vue-select.css";
import Message from './Message.vue';

export default {
	name: "DashboardGroupSelect",
	components: {
      Message
    },
	props: {
		settings: Object,
		categories: Array,
	},

	data() {
		return {
			isError: false,
			isLoading: false,
			selectedgroups: {},
			counties: [],
			communities: [],
			institutions: [],
			kindergartens: [],
			leaderCountyGroups: [],
			leaderCommunityGroups: [],
			facultiesNational: [],
			facultiesCounty: [],
			facultiesCommunity: [],
			chosenCounty: null,
			chosenCommunity: null,
			chosenInstitution: null,
			chosenFacultyNational: null,
			chosenFacultyCounty: null,
			chosenFacultyCommunity:null,
			chosenCountyLeader: null,
			chosenCommunityLeader: null,
			error: '',
			courseId: null,
		}
	},

	methods: {
		async getCounties() {
			try {
				const result = await api.get(`/course/${this.courseId}/category/${this.settings.custom_county_category_id}/groups`,
				{
          params: { cookie: window.cookie }
        });
				this.counties = result.data;
				this.isError = false;
			} catch (e) {
				this.error = "Kunne ikke hente fylkesgrupper fra kpas.";
				this.isError = true;
			}
		},
		async getCommunities(countyId) {
			try {
				const result = await api.get(`/course/${this.courseId}/category/${this.settings.custom_community_category_id}/groups?county_id=${countyId}`,
				{
          params: { cookie: window.cookie }
        });
				this.communities = result.data;
				this.isError = false;
			} catch (e) {
				this.error = "Kunne ikke hente kommunegrupper fra kpas.";
				this.isError = true;
			}
		},
		async getInstitutions(communityId) {
			try {
				const result = await api.get(`/course/${this.courseId}/category/${this.settings.custom_institution_category_id}/groups?community_id=${communityId}`,
				{
          params: { cookie: window.cookie }
        });
				this.institutions = result.data;
				this.isError = false;
			} catch (e) {
				this.error = "Kunne ikke hente institusjonsgrupper kpas.";
				this.isError = true;
			}
		},

		async getCountyLeaderGroups(){
			try {
				const result = await api.get(`/course/${this.courseId}/category/${this.settings.custom_county_principals_category_id}/groups`,
				{
          params: { cookie: window.cookie }
        });
				this.leaderCountyGroups = result.data;
				this.isError = false;
			}
			catch {
				this.error = "Kunne ikke hente fylkesgrupper for ledere fra kpas.";
				this.isError = true;
			}

		},
		async getCommunityLeaderGroups(countyId){
			try {
				const result = await api.get(`/course/${this.courseId}/category/${this.settings.custom_community_principals_category_id}/groups?county_id=${countyId}`,
				{
          params: { cookie: window.cookie }
        });
				this.leaderCommunityGroups = result.data;
				this.isError = false;
			}
			catch {
				this.error = "Kunne ikke hente kommunegrupper for ledere fra kpas.";
				this.isError = true;
			}
		},

		async getFacultiesNational(){
			try {
				const result = await api.get(`/course/${this.courseId}/category/${this.settings.custom_national_faculty_category_id}/groups`,
				{
          params: { cookie: window.cookie }
        });
				this.facultiesNational = result.data;
				this.isError = false;
			}
			catch {
				this.error = "Kunne ikke hente faggrupper på nasjonalt nivå fra kpas.";
				this.isError = true;
			}
		},
		async getFacultiesCounty(){
			try {
				const result = await api.get(`/course/${this.courseId}/category/${this.settings.custom_county_faculty_category_id}/groups`,
				{
          params: { cookie: window.cookie }
        });
				this.facultiesCounty = result.data;
				this.isError = false;
			}
			catch {
				this.error = "Kunne ikke hente faggrupper på fylkesnivå fra kpas.";
				this.isError = true;
			}
		},
		async getFacultiesCommunity(countyId){
			try {
				const result = await api.get(`/course/${this.courseId}/category/${this.settings.custom_community_faculty_category_id}/groups?county_id=${countyId}`,
				{
          params: { cookie: window.cookie }
        });
				this.facultiesCommunity = result.data;
				this.isError = false;
			}
			catch {
				this.error = "Kunne ikke hente faggrupper på kommunenivå fra kpas.";
				this.isError = true;
			}
		},
	},
	async created() {
		this.courseId = this.settings.custom_canvas_course_id;

		if(this.settings.custom_county_category_id) {
			await this.getCounties();
		}
		if(this.settings.custom_county_principals_category_id){
			await this.getCountyLeaderGroups();
		}
		if(this.settings.custom_national_faculty_category_id) {
			await this.getFacultiesNational();
		}
		if(this.settings.custom_county_faculty_category_id){
			await this.getFacultiesCounty();
		}
	},

	updated() {
		let groupId = null;
		if (this.selectedgroups.institution) { groupId = this.selectedgroups.institution.canvas_id }
		else if (this.selectedgroups.communityLeader) { groupId = this.selectedgroups.communityLeader.canvas_id }
		else if (this.selectedgroups.community) { groupId = this.selectedgroups.community.canvas_id }
		else if (this.selectedgroups.countyLeader) { groupId = this.selectedgroups.countyLeader.canvas_id }
		else if (this.selectedgroups.county) { groupId = this.selectedgroups.county.canvas_id }
		else if (this.selectedgroups.facultyCommunity) { groupId = this.selectedgroups.facultyCommunity.canvas_id }
		else if (this.selectedgroups.facultyCounty) { groupId = this.selectedgroups.facultyCounty.canvas_id }
		else if (this.selectedgroups.facultyNational) { groupId = this.selectedgroups.facultyNational.canvas_id }
		this.$emit('update', groupId);
	},

	watch: {
		async chosenCounty(county) {
			if(county == null){
				delete this.selectedgroups.county;
				delete this.selectedgroups.community;
				delete this.selectedgroups.institution;
				return;
			}
			delete this.selectedgroups.community;
			delete this.selectedgroups.institution;
			this.selectedgroups.county = county;

			this.communities = [];
			this.institutions = [];

			await this.getCommunities(county.county_id);
		},
		async chosenCommunity(community) {
			if(community == null){
				delete this.selectedgroups.community;
				delete this.selectedgroups.institution;
				return;
			}
			delete this.selectedgroups.institution;
			this.selectedgroups.community = community;

			this.institutions = [];

			await this.getInstitutions(community.community_id)
		},
		async chosenInstitution(institution) {
			if(institution == null){
				delete this.selectedgroups.institution;
				return;
			}
			this.selectedgroups.institution = institution;
		},

		async chosenFacultyNational(facultyNational) {
			if(facultyNational == null){
				delete this.selectedgroups.facultyNational;
				return;
			}
			this.selectedgroups.facultyNational = facultyNational;
		},
		async chosenFacultyCounty(facultyCounty) {
			if(facultyCounty == null){
				delete this.selectedgroups.facultyCounty;
				delete this.selectedgroups.facultyCommunity;
				return;
			}
			delete this.selectedgroups.facultyCommunity;
			this.selectedgroups.facultyCounty = facultyCounty;

			this.facultiesCommunity = [];

			await this.getFacultiesCommunity(facultyCounty.county_id);
		},
		async chosenFacultyCommunity(facultyCommunity) {
			if(facultyCommunity == null){
				delete this.selectedgroups.facultyCommunity;
				return;
			}
			this.selectedgroups.facultyCommunity = facultyCommunity;
		},

		async chosenCountyLeader(countyLeader) {
			if(countyLeader == null){
				delete this.selectedgroups.countyLeader;
				delete this.selectedgroups.communityLeader;
				return
			}
			delete this.selectedgroups.communityLeader;
			this.selectedgroups.countyLeader = countyLeader;

			await this.getCommunityLeaderGroups(countyLeader.county_id);
		},
		async chosenCommunityLeader(communityLeader) {
			if(communityLeader == null){
				delete this.selectedgroups.communityLeader;
				return
			}
			this.selectedgroups.communityLeader = communityLeader;
		}
	}
}
</script>

<style>
.dropdown-section{
	display: inline-flex;
	align-content: center;
	width:100%;
}
label {
	display: inline-block;
	align-content: center;
	padding: .5em;
	width: 34%;
}

</style>

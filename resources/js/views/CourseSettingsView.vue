<template>
    <div>

        <h1> KPAS kompetansepakke innstillinger</h1>
        <p>Lisens: <input type="checkbox" true-value="1" false-value="0" v-model="currentcourseSettings.licence"/></p>
        <p>Rolle support: <input type="checkbox" true-value="1" false-value="0" v-model="currentcourseSettings.role_support"/></p>
        <p>Vedlikehold avsluttet: <input type="date" clearable v-model="currentcourseSettings.unmaintained_since"></p>

        <section>
            Banner:
            <v-select
            :options="bannerTypes"
            :close-on-select="true"
            v-model="currentcourseSettings.banner_type"
            :clearable="false"

            ></v-select>

            <p v-if="currentcourseSettings.banner_type != 'NONE'">Banner tekst:
                <input type="text" v-model="currentcourseSettings.banner_text"/>
            </p>
        </section>

        <section>
            Multilang:
            <v-select
            :options="multilangTypes"
            :close-on-select="true"
            :clearable="false"
            v-model="currentcourseSettings.multilang"
            ></v-select>
        </section>

        <section>
            Filtre:
            <v-select multiple
            :options="allFilters"
            label="filter_name"
            placeholder="--- Filter ---"
            :close-on-select="true"
            :clearable="true"
            v-model="selectedFilters"
            ></v-select>
        </section>
        <section>
            Kategori:
            <v-select
            :options="allCategories"
            label="name"
            placeholder="--- Category ---"
            :close-on-select="true"
            :clearable="true"
            v-model="selectedCategory"
            ></v-select>
            Plassering i kategori:
            <input type="number" default=0 v-model="currentcourseSettings.course_category.position"/>
            <p>Ny pakke: <input type="checkbox" true-value="1" false-value="0" v-model="currentcourseSettings.course_category.new"/></p>

        </section>
        <button @click="updateCourseSettings">Lagre</button>

        <span class="ml-3" v-if="isSubmitting">Sender <div class="spinner-border text-success"></div></span>
        <div v-if="responseCode == 200" class='alert alert-success kpasAlert'>Oppdateringen var vellykket!</div>
        <div v-if="error" class='alert alert-danger kpasAlert'>{{ error }}</div>

        <div v-if="isadmin">
            <course-settings-category-create @update="newCategoryUpdate"></course-settings-category-create>
            <course-settings-filter-create :filterTypes="filterTypes" @update="newFilterUpdate"></course-settings-filter-create>
        </div>
    </div>

</template>


<script>
import 'floating-vue/dist/style.css';
import "vue-select/dist/vue-select.css";
import api from '../api';
import CourseSettingsCategoryCreate from '../components/CourseSettingsCategoryCreate.vue';
import CourseSettingsFilterCreate from '../components/CourseSettingsFilterCreate.vue';

export default{
    name: "CourseSettingsView",
    props: {
        courseid: Number,
        coursesettings : {},
        filters : [],
        categories : [],
        multilangtypes: [],
        bannertypes: [],
        filtertypes: [],
        isadmin: Boolean
    },
    components:{
        CourseSettingsCategoryCreate,
        CourseSettingsFilterCreate
    },
    data () {
        return {
            currentcourseSettings : this.coursesettings? this.coursesettings : {},
            allFilters : this.filters,
            allCategories : this.categories,
            filterTypes : this.filtertypes,
            bannerTypes : this.bannertypes,
            multilangTypes : this.multilangtypes,
            selectedFilters : [],
            selectedCategory : {},
            error:  undefined,
            isSubmitting: false,
            responseCode: undefined,
        }
    },
    created() {
        console.log(this.isadmin)
        if(this.coursesettings && this.coursesettings.course_category){
            this.setSelectedCategory();
        }
        if(this.coursesettings && this.coursesettings.course_filter){
            this.setSelectedFilters();
        }
        if(!this.coursesettings){
            this.setEmptyCourseSettings();
        }

    },
    methods: {
        setSelectedFilters() {
            this.coursesettings.course_filter.forEach(filter => {
                this.selectedFilters.push(this.allFilters.find(f => f.id == filter.filter_id))
            });
        },
        setSelectedCategory() {
            this.selectedCategory = this.allCategories.find(c => c.id == this.coursesettings.course_category.category_id)
        },
        setEmptyCourseSettings() {
            this.currentcourseSettings.unmaintained_since = null;
            this.currentcourseSettings.licence = 0;
            this.currentcourseSettings.role_support = 0;
            this.currentcourseSettings.banner_type = 'NONE';
            this.currentcourseSettings.banner_text = null;
            this.currentcourseSettings.multilang = 'NONE';
            this.currentcourseSettings.course_category = {
                position: 0,
                new: 0
            }
            this.currentcourseSettings.course_filter = [];
        },
        newCategoryUpdate(category) {
            this.allCategories.push(category)
        },
        newFilterUpdate(filter) {
            this.allFilters.push(filter)
        },
        async updateCourseSettings() {
            this.responseCode = undefined;
            this.isSubmitting = true;
            let response = undefined;
            try {
                response = await api.put('course/' + this.courseid + '/settings', {
                    cookie: window.cookie,
                    unmaintained_since: this.currentcourseSettings.unmaintained_since,
                    licence: this.currentcourseSettings.licence,
                    role_support: this.currentcourseSettings.role_support,
                    banner_type: this.currentcourseSettings.banner_type,
                    banner_text: this.currentcourseSettings.banner_text,
                    multilang: this.currentcourseSettings.multilang,
                    courseCategory: [{
                        course_id: this.courseid,
                        category_id: this.selectedCategory.id,
                        position: this.currentcourseSettings.course_category.position,
                        new: this.currentcourseSettings.course_category.new
                    }],
                    courseFilters: this.selectedFilters.map(f => {
                        return {
                            course_id: this.courseid,
                            filter_id: f.id
                        }
                    })
                })
            } catch (e) {
                if(response.data.status != 200){
                    this.isSubmitting = false;
                    this.error = "Noe gikk galt, prøv igjen senere"
                }
            }
            if (response.data.status == 200) {
                this.isSubmitting = false;
                this.responseCode = response.data.status;
                this.error = undefined;
            }
        }

    }


}

</script>

<style scoped>
section {
    padding-bottom: 2em;
}

p {
    margin-top: .5em;
    margin-bottom: .5em;
}
input {
    margin-top: .5em;
    margin-bottom: .5em;
}
button {
    margin-bottom: 1em;
}
</style>
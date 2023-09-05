<template>
    <div>

        <h1> KPAS kompetansepakke innstillinger</h1>
        <p>Lisens: <input type="checkbox" true-value="1" false-value="0" v-model="currentcourseSettings.licence"/></p>
        <p>Rolle support: <input type="checkbox" true-value="1" false-value="0" v-model="currentcourseSettings.role_support"/></p>
        <p>Vedlikehold avsluttet: <input type="checkbox" true-value="1" false-value="0" v-model="unmaintained"/></p>
        <p v-if="unmaintained == '1'">Dato for vedlikehold avsluttet: <input type="date" clearable v-model="currentcourseSettings.unmaintained_since"></p>

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
            <p>Ny pakke: <input type="checkbox" default="0" true-value="1" false-value="0" v-model="currentcourseSettings.course_category.new"/></p>

        </section>

        <section>
            <div v-if="imageSelected">
                <p>Valgt bilde: </p>
                <img :src="selectedImage.path" />
                <br/>
                <button @click="openPopup">Velg bilde</button>
            </div>
        </section>
        <section class="image-selector" v-if="open">
                <v-layout row wrap primary-title v-for="image in courseImages" :key="image.id">
                    <v-flex xs6>
                        <img @click="selectImage(image)" v-bind:src="`${image.path}`" alt="illustration">
                    </v-flex>
                </v-layout>
        </section>

        <section>
            <br/>
            <button @click="updateCourseSettings">Lagre</button>
            <span class="ml-3" v-if="isSubmitting">Sender <div class="spinner-border text-success"></div></span>
            <div v-if="responseCode == 200" class='alert alert-success kpasAlert'>Oppdateringen var vellykket!</div>
            <div v-if="error" class='alert alert-danger kpasAlert'>{{ error }}</div>
        </section>

        <div v-if="isadmin">
            <h1>Admin funksjoner</h1>
            <course-settings-filter-create :filterTypes="filterTypes" @update="newFilterUpdate"></course-settings-filter-create>
            <course-settings-category-create @update="newCategoryUpdate"></course-settings-category-create>
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
        isadmin: Boolean,
        courseimages: []
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
            courseImages: this.courseimages,
            open: false,
            selectedImage: {},
            imageSelected: false,
            unmaintained: "0"
        }
    },
    created() {
        if(this.coursesettings && this.coursesettings.course_category){
            this.setSelectedCategory();
        }
        if(this.coursesettings && this.coursesettings.course_filter){
            this.setSelectedFilters();
        }
        if(this.coursesettings && this.coursesettings.image){
            this.setSelectedImage();
        }
        if(!this.coursesettings){
            this.setEmptyCourseSettings();
        }
        if(this.currentcourseSettings.unmaintained_since != null){
            this.unmaintained = "1";
        }


    },
    updated() {

    },
    methods: {
        selectImage(image){
            this.selectedImage = image;
            this.open = false;
            this.imageSelected = true;
        },
        openPopup(){
            this.open = true;
        },
        toggleDialog(){
            this.dialog = !this.dialog;
        },
        setSelectedFilters() {
            this.coursesettings.course_filter.forEach(filter => {
                this.selectedFilters.push(this.allFilters.find(f => f.id == filter.filter_id))
            });
        },
        setSelectedCategory() {
            this.selectedCategory = this.allCategories.find(c => c.id == this.coursesettings.course_category.category_id)
        },
        setSelectedImage() {
            this.selectedImage = this.courseImages.find(i => i.id == this.coursesettings.image.id)
            this.imageSelected = true;
        },
        setEmptyCourseSettings() {
            this.currentcourseSettings.unmaintained_since = null;
            this.currentcourseSettings.licence = 0;
            this.currentcourseSettings.role_support = 0;
            this.currentcourseSettings.banner_type = 'NONE';
            this.currentcourseSettings.banner_text = null;
            this.currentcourseSettings.multilang = 'NONE';
            this.currentcourseSettings.course_filter = [];
            this.currentcourseSettings.image_id = null;
            this.currentcourseSettings.course_category.position = 0;
            this.currentcourseSettings.course_category.new = false;
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
            if(this.unmaintained == "0"){
                this.currentcourseSettings.unmaintained_since = null;
            }
            try {
                response = await api.put('course/' + this.courseid + '/settings', {
                    cookie: window.cookie,
                    unmaintained_since: this.currentcourseSettings.unmaintained_since,
                    licence: this.currentcourseSettings.licence,
                    role_support: this.currentcourseSettings.role_support,
                    banner_type: this.currentcourseSettings.banner_type,
                    banner_text: this.currentcourseSettings.banner_text,
                    multilang: this.currentcourseSettings.multilang,
                    courseCategory: this.selectedCategory.id ? [{
                        course_id: this.courseid,
                        category_id: this.selectedCategory.id,
                        position: this.currentcourseSettings.course_category.position,
                        new: this.currentcourseSettings.course_category.new
                    }] : null,
                    courseFilters: this.selectedFilters? this.selectedFilters.map(f => {
                        return {
                            course_id: this.courseid,
                            filter_id: f.id
                        }
                    }) : [],
                    image_id: this.selectedImage? this.selectedImage.id : null
                })
            } catch (e) {
                    this.isSubmitting = false;
                    this.error = "Noe gikk galt, prøv igjen senere"
                    console.log(e)
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
    margin-top: 1em;
}

img{
    height: 8em;
    &:hover{
        cursor: pointer;
    }
}
.image-selector {
    height: 30em;
    overflow-y: scroll;
    border: 1px solid gray;
}
</style>

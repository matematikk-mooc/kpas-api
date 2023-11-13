<template>
    <h2>Velg fremhevet kompetansepakke </h2>
    <section>kompetansepakke:
        <v-select
        :options="courses"
        :getOptionLabel="course => course.name"
        :close-on-select="true"
        v-model="highlighted"
        :clearable="false"
        ></v-select>
    </section>
    <button @click="setHighlightedCourse">Lagre fremhevet kompetansepakke</button>
    <div v-if="responseCode == 200" class='alert alert-success kpasAlert'>Oppdateringen var vellykket!</div>
    <div v-if="error" class='alert alert-danger kpasAlert'>{{ error }}</div>

</template>

<script>
import api from '../api';

export default {
    name: "CourseSettingsHighlightedCourse",
    props: {
        courses: [],
        current: {}
    },
    data() {
        return {
            highlighted: {},
            responseCode: undefined,
            error: undefined
        }
    },
    created() {
        if(this.current != null || this.current != undefined || this.current != {}){
            console.log(this.current)
            this.highlighted = this.courses.find(course => course.id == this.current.course_id);
            console.log(this.highlighted)
        }
    },
    methods: {
        async setHighlightedCourse() {
            this.responseCode = undefined;
            let response = undefined;
            try {
                response = await api.put('settings/highlighted', {
                    cookie: window.cookie,
                    courseId: this.highlighted.id
                })
            } catch (e) {
                if(response.data.status != 200){
                    this.error = "Noe gikk galt, prøv igjen senere"
                }
            }
            if(response.data.status == 200){
                this.responseCode = response.data.status;
            }

        }
    }
}
</script>

<style scoped>
</style>
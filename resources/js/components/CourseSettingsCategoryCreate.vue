<template>
    <h2>Opprett ny kategori </h2>

    <p>Kategorinavn: <input type="text" v-model="category_name"/></p>
    <p>Kategoriposisjon: <input type="number" default=0 v-model="position"/></p>
    <p>Kategoritema: <input type="text" v-model="category_color"/> (Kategoritema theme_*number* må finnes som tema i frontend)</p>

    <button @click="createCategory">Opprett kategori</button>
    <div v-if="responseCode == 200" class='alert alert-success kpasAlert'>Oppdateringen var vellykket!</div>
    <div v-if="error" class='alert alert-danger kpasAlert'>{{ error }}</div>
</template>

<script>
import api from '../api';

export default{
    name: "CourseSettingsCategoryCreate",
    data() {
        return {
            category_name: '',
            category_color: '',
            position: 0,
            error: undefined,
            responseCode: undefined
        }
    },
    methods: {
        async createCategory() {
            let response = undefined;
            this.error = undefined;
            if(this.category_name == '' || this.category_name == null){
                this.error = "Kategorinavn kan ikke være tomt"
                return;
            }
            if(this.category_color == '' || this.category_color == null){
                this.error = "Kategoritema kan ikke være tomt"
                return;
            }
            try {
                response = await api.post('categories', {
                    cookie: window.cookie,
                    name: this.category_name,
                    position: this.position,
                    color_code: this.category_color
                })
            } catch (e) {
                if(response.data.status != 200){
                    this.error = "Noe gikk galt, prøv igjen senere"
                }
            }
            if(response.data.status == 200){
                this.category_name = '';
                this.category_color = '';
                this.position = 0;
                this.responseCode = response.data.status;
            }
            this.$emit('update', response.data.result);
        }
    }
}
</script>


<style scoped>
h2 {
    margin-top: .5em;
    margin-bottom: .5em;
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

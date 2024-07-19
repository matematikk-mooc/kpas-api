<template>
    <p>Navn: <input type="text" v-model="filter_name"/></p>
    <section>Type:
        <v-select
        :options="filterTypes"
        :close-on-select="true"
        v-model="filter_type"
        :clearable="false"
        ></v-select>
    </section>
    <button class="kpas-button" @click="createFilter">Opprett kategori</button>
    <Message type="success" v-if="responseCode == 200" class='alert alert-success kpasAlert'>Oppdateringen var vellykket!</Message>
    <Message type="error" v-if="error">{{ error }}</Message>
</template>

<script>
import api from '../api';
import Message from './Message.vue';

export default{
    name: "CourseSettingsFilterCreate",
    components: {
      Message
    },
    props: {
        filterTypes: []
    },
    data() {
        return {
            filter_name: '',
            filter_type: '',
            responseCode: undefined,
            error: undefined
        }
    },
    methods: {
        async createFilter() {
            this.responseCode = undefined;
            this.error = undefined;
            let response = undefined;
            try {
                response = await api.post('filters', {
                    cookie: window.cookie,
                    filter_name: this.filter_name,
                    type: this.filter_type
                })
            } catch (e) {
                if(response.data.status != 200){
                    this.error = "Noe gikk galt, prøv igjen senere"
                }
            }
            if(response.data.status == 200){
                this.filter_name = '';
                this.responseCode = response.data.status;
            }
            console.log(response);
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
section {
    padding-bottom: 2em;
}

</style>

<template>
    <div>
        <button @click="miniUUCheck()">Kjør mini-UUCheck</button>
        <div v-if="didRun && content">
            <h3>UUCheck resultater</h3>
            <p>Antall feil: {{ errorCount }}</p>
            <p>Antall advarsler: {{ warningCount }}</p>
            <div v-for="item in this.content">
                <h3>{{ item.itemName }}</h3>
                <div v-for="error in item.errors">
                    <p>{{ error.message }}: {{ error.description }} </p>
                </div>
                <div v-for="warning in item.warnings">
                    <p>{{ warning.message }}: {{ warning.description }}</p>
                </div>
            </div>
        </div>
        <div v-else-if="didRun && !content">
            <p>Fant ingen problemer under kjøring av UUCheck. Husk at denne testen ikke sjekker filer, og bare gjør en begrenset sjekk av sider.</p>
        </div>
    </div>
</template>

<script>
import UUCheck from '../uucheck.js';
export default {
    name: "HealthMonitorView",
    props: {
        moduleitems: Array,
        courseid: Number
    },
    data() {
        return {
            courseID: 637,
            content: null,
            errorCount: 0,
            warningCount: 0,
            didRun: false
        };
    },
    methods: {
        async miniUUCheck() {
            let result = await UUCheck(this.moduleitems, this.courseid);
            if(result.length > 1) {
                this.content = result.slice(0, -1);
            }
            if(result[result.length - 1].type == "summary") {
                this.warningCount = result[result.length - 1].warnings
                this.warningCount = result[result.length - 1].warnings
                this.errorCount = result[result.length - 1].errors;
            }
            this.didRun = true;
        },
    }

    }
</script>

<style>
</style>

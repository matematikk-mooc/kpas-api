<template>
    <div>
        <h2 id="activity" class="forvaltning_title">Aktivitetstid</h2>

        <div class="activity_view">
            <table>
                <tr>
                    <th>Type</th>
                    <th>Tid</th>
                </tr>
                <tr>
                    <td>Gjennomsnitt</td>
                    <td>{{ mean }}</td>
                </tr>
                <tr>
                    <td>Standardavvik</td>
                    <td>{{ standardDeviation }}</td>
                </tr>
                <tr>
                    <td>Median</td>
                    <td>{{ median }}</td>
                </tr>
                <tr>
                    <td>Nedre kvartil</td>
                    <td>{{ lowerQuartile }}</td>
                </tr>
                <tr>
                    <td>Øvre kvartil</td>
                    <td>{{ upperQuartile }}</td>
                </tr>
                <tr>
                    <td>Min</td>
                    <td>{{ min }}</td>
                </tr>
                <tr>
                    <td>Maks</td>
                    <td>{{ max }}</td>
                </tr>
            </table>

            <h3>Antall student brukere med aktivitet: {{ usersCount }}</h3>

            <div class="activity_view_fetch">
                <button type="button" class="btn btn-primary --fetch" @click="fetchActivity" :disabled="isLoading">Hent data</button>

                <span v-if="isLoading">Det kan ta en stund før resultatet vises om det er mange påmeldte brukere, laster<span class="udir-link-dots"></span></span>
            </div>
        </div>
    </div>
</template>

<script>
import { utils } from "../healthUtils";

export default {
    name: "ActivityView",
    props: {
        courseid: Number
    },
    components: {},
    data() {
        return {
            isLoading: false,
            payload: null,
        };
    },
    computed: {
        mean() {
            if (!this.isValidNumber(this.payload?.statistics?.mean)) return '---';
            return this.secondsToText(this.payload.statistics.mean);
        },
        standardDeviation() {
            if (!this.isValidNumber(this.payload?.statistics?.stdDev)) return '---';
            return this.secondsToText(this.payload.statistics.stdDev);
        },
        median() {
            if (!this.isValidNumber(this.payload?.statistics?.median)) return '---';
            return this.secondsToText(this.payload.statistics.median);
        },
        lowerQuartile() {
            if (!this.isValidNumber(this.payload?.statistics?.lowerQuartile)) return '---';
            return this.secondsToText(this.payload.statistics.lowerQuartile);
        },
        upperQuartile() {
            if (!this.isValidNumber(this.payload?.statistics?.upperQuartile)) return '---';
            return this.secondsToText(this.payload.statistics.upperQuartile);
        },
        min() {
            if (!this.isValidNumber(this.payload?.statistics?.min)) return '---';
            return this.secondsToText(this.payload.statistics.min);
        },
        max() {
            if (!this.isValidNumber(this.payload?.statistics?.max)) return '---';
            return this.secondsToText(this.payload.statistics.max);
        },
        usersCount() {
            if (!this.isValidNumber(this.payload?.participantsCount)) return '---';
            return this.formatNumber(this.payload.participantsCount);
        },
    },
    methods: {
        async fetchActivity() {
            console.log("FETCH_ACTIVITY_RUN:", this.courseid);
            this.isLoading = true;

            const courseActivity = await utils.apiGet(`/statistics/activity/courses/${this.courseid}`);            
            const courseActivityData = courseActivity?.data?.result?.results ?? null;

            console.log("FETCH_ACTIVITY", courseActivityData);
            this.payload = courseActivityData;
            this.isLoading = false;
        },
        isValidNumber(value) {
            return typeof value === 'number' && !isNaN(value);
        },
        formatNumber(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        },
        secondsToText(seconds) {
            const days = Math.floor(seconds / 86400);
            const hours = Math.floor((seconds % 86400) / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const remainingSeconds = Math.ceil(seconds % 60);

            const daysText = days > 0 ? `${days} dag${days !== 1 ? 'er' : ''}` : '';
            const hoursText = hours > 0 ? `${hours} time${hours !== 1 ? 'r' : ''}` : '';
            const minutesText = minutes > 0 ? `${minutes} minutt${minutes !== 1 ? 'er' : ''}` : '';
            const secondsText = remainingSeconds > 0 ? `${remainingSeconds} sekund${remainingSeconds !== 1 ? 'er' : ''}` : '';

            return [daysText, hoursText, minutesText, secondsText].filter(Boolean).join(' ');
        }
    },
}
</script>

<template>
    <div>
        <div class="health-cards">
            <div class="health-cards-overview health-cards-item health-cards-box">
                <HealthCard
                    className="--overview"
                    title="Helsesjekk"
                    :notSupported="true"
                />
            </div>

            <div class="health-cards-uu health-cards-item health-cards-box">
                <HealthCard
                    className="--uu"
                    title="UU-sjekk"
                    :lastExecuted="uuState.lastRun"
                    :isLoading="uuState.isLoading"
                    :payload="uuState.payload"
                    :handleRefresh="runUUCheck"
                />
            </div>

            <div class="health-cards-other health-cards-box">
                <HealthCard
                    className="--links"
                    title="Lenkesjekk"
                    :notSupported="true"
                />

                <HealthCard
                    className="--transcripts"
                    title="Videotekstsjekk"
                    :notSupported="true"
                />
            </div>
        </div>
    </div>
</template>

<script>
import UUCheck from '../uucheck.js';
import HealthCard from "../components/HealthCard"

export default {
    name: "HealthMonitorView",
    props: {
        moduleitems: Array,
        courseid: Number
    },
    components: {
        HealthCard
    },
    data() {
        return {
            uuState: {
                lastRun: "",
                payload: null,
                isLoading: false
            }
        };
    },
    methods: {
        async runUUCheck() {
            console.log("UU_CHECK_RUN");
            const now = new Date();
            const nowFormatted = `${String(now.getDate()).padStart(2, '0')}.${String(now.getMonth() + 1).padStart(2, '0')}.${now.getFullYear()} kl. ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
            this.uuState = { ...this.uuState, isLoading: true };

            let payload = await UUCheck(this.moduleitems, this.courseid);
            this.uuState = { ...this.uuState, isLoading: false, payload: payload, lastRun: nowFormatted };
            console.log("UU_CHECK", this.uuState);
        }
    }

}
</script>

<style>
.health-cards {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    width: 100%;
    height: 100%;
    padding-top: 40px;
    padding-bottom: 80px;
    gap: 20px;
}

.health-cards-box {
    display: flex;
    flex: 1 1 300px;
}

.health-cards-other {
    flex-direction: column;
    row-gap: 20px;
}
</style>

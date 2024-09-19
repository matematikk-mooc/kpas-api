<template>
    <div>
        <div class="health-cards">
            <div class="health-cards-overview health-cards-item health-cards-box">
                <HealthCard
                    className="--overview"
                    title="Sanitysjekk"
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
                    :lastExecuted="linksState.lastRun"
                    :isLoading="linksState.isLoading"
                    :payload="linksState.payload"
                    :handleRefresh="runLinksCheck"
                    :hidePagesChecked="true"
                />

                <HealthCard
                    className="--transcripts"
                    title="Videotekstsjekk"
                    :lastExecuted="captionState.lastRun"
                    :isLoading="captionState.isLoading"
                    :payload="captionState.payload"
                    :handleRefresh="runCaptionCheck"
                />
            </div>
        </div>

        <div id="render-iframes-hidden" style="display: none;"></div>

    </div>
</template>

<script>
// import SanityCheck from '../sanitycheck.js';
import UUCheck from '../uucheck.js';
import LinksCheck from '../linkscheck.js';
import CaptionCheck from '../captioncheck.js';
import HealthCard from "../components/HealthCard"

export default {
    name: "HealthMonitorView",
    props: {
        courseid: Number
    },
    components: {
        HealthCard
    },
    data() {
        return {
            sanityState: {
                lastRun: "",
                payload: null,
                isLoading: false
            },
            uuState: {
                lastRun: "",
                payload: null,
                isLoading: false
            },
            linksState: {
                lastRun: "",
                payload: null,
                isLoading: false
            },
            captionState: {
                lastRun: "",
                payload: null,
                isLoading: false
            }
        };
    },
    methods: {
        async runSanityCheck() {
            console.log("SANITY_CHECK_RUN");
            const now = new Date();
            const nowFormatted = `${String(now.getDate()).padStart(2, '0')}.${String(now.getMonth() + 1).padStart(2, '0')}.${now.getFullYear()} kl. ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
            this.sanityState = { ...this.sanityState, isLoading: true };

            // let payload = await SanityCheck(this.courseid);
            // this.sanityState = { ...this.sanityState, isLoading: false, payload: payload, lastRun: nowFormatted };
            console.log("SANITY_CHECK", this.sanityState);
        },
        async runUUCheck() {
            const now = new Date();
            const nowFormatted = `${String(now.getDate()).padStart(2, '0')}.${String(now.getMonth() + 1).padStart(2, '0')}.${now.getFullYear()} kl. ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
            this.uuState = { ...this.uuState, isLoading: true };

            let payload = await UUCheck(this.courseid);
            this.uuState = { ...this.uuState, isLoading: false, payload: payload, lastRun: nowFormatted };
        },
        async runLinksCheck() {
            const now = new Date();
            const nowFormatted = `${String(now.getDate()).padStart(2, '0')}.${String(now.getMonth() + 1).padStart(2, '0')}.${now.getFullYear()} kl. ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
            this.linksState = { ...this.linksState, isLoading: true };

            let payload = await LinksCheck(this.courseid);
            this.linksState = { ...this.linksState, isLoading: false, payload: payload, lastRun: nowFormatted };
        },
        async runCaptionCheck() {
            const now = new Date();
            const nowFormatted = `${String(now.getDate()).padStart(2, '0')}.${String(now.getMonth() + 1).padStart(2, '0')}.${now.getFullYear()} kl. ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
            this.captionState = { ...this.captionState, isLoading: true };

            let payload = await CaptionCheck(this.courseid);
            this.captionState = { ...this.captionState, isLoading: false, payload: payload, lastRun: nowFormatted };
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
    max-width: 100%;
}

.health-cards-other {
    flex-direction: column;
    row-gap: 20px;
}
</style>

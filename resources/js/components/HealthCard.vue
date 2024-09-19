<template>
    <div :class="`health-card ${className}`">
        <div class="health-card-header">
            <div class="health-card-header-title">
                <h2>{{ title }}</h2>
                <p v-if="hasData && !showDetails">Sist kjørt {{ lastExecuted }}</p>
            </div>

            <div class="health-card-header-button" v-if="hasData && !showDetails">
                <button :class="['health-card-header-button-refresh', isRefreshing ? '--loading' : '']"
                    :aria-label="`Oppdater ${title}`" :disabled="isRefreshing" @click="refreshData">
                    <svg width="20" height="26" viewBox="0 0 20 26" fill="none" xmlns="http://www.w3.org/2000/svg"
                        aria-hidden="true">
                        <path fill="#007DFF"
                            d="M9.99996 5.99984V9.49984L14.6666 4.83317L9.99996 0.166504V3.6665C4.84329 3.6665 0.666626 7.84317 0.666626 12.9998C0.666626 14.8315 1.20329 16.5348 2.11329 17.9698L3.81663 16.2665C3.29163 15.2982 2.99996 14.1782 2.99996 12.9998C2.99996 9.13817 6.13829 5.99984 9.99996 5.99984ZM17.8866 8.02984L16.1833 9.73317C16.6966 10.7132 17 11.8215 17 12.9998C17 16.8615 13.8616 19.9998 9.99996 19.9998V16.4998L5.33329 21.1665L9.99996 25.8332V22.3332C15.1566 22.3332 19.3333 18.1565 19.3333 12.9998C19.3333 11.1682 18.7966 9.46484 17.8866 8.02984Z" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="health-card-body --init" v-if="!hasData">
            <p class="health-card-body-support" v-if="notSupported">Denne funksjonen er ikke støttet ennå.</p>

            <Button class="udir-link" v-if="!notSupported" :disabled="isLoading" @click="handleRefresh">
                <span v-if="isLoading">Laster<span class="udir-link-dots"></span></span>
                <span v-else>Kjør {{ title }}</span>
            </Button>
        </div>

        <div class="health-card-body" v-if="hasData && !showDetails">
            <div class="health-card-body-header">
                <p>
                    <b :class="hasErrors ? '--error' : ''">{{ hasErrors ? `${this.payload.messageTypes.error} feil` : "OK" }}</b>

                    <span v-if="!hidePagesChecked">{{ totalPages }} sider sjekket.{{ hasData ? "" : " Ingen feil funnet." }}</span>
                </p>
            </div>

            <div class="health-card-body-categories">
                <div class="health-card-body-categories-bar" v-if="hasMessagesCount">
                    <span v-for="category in categories" :key="category.title"
                        :style="{ background: category.color, width: calculateWidth(category.count) + '%' }"></span>
                </div>

                <ul>
                    <li v-for="category in categories" :key="category.title">
                        <div class="list-item-title">
                            <span aria-hidden="true" :style="{ background: category.color }"></span>

                            <p>{{ category.title }}</p>
                        </div>


                        <p class="list-item-count">{{ category.count }}</p>
                    </li>
                </ul>
            </div>
        </div>

        <div class="health-card-body --details" v-if="hasData && showDetails">
            <div class="health-card-detail" v-for="messageItem in payload.messages" :key="messageItem.title">
                <h3>{{ messageItem.title }}</h3>

                <ul>
                    <li v-for="messageNestedItem in messageItem.messages">
                        <span :style="{ background: messageNestedItem.type._color }"></span>

                        <p>{{ messageNestedItem.element.nb }}: {{ messageNestedItem.message.nb }}</p>
                    </li>
                </ul>
            </div>
        </div>

        <div class="health-card-footer" v-if="hasData && hasMessagesCount && totalMessagesCount != this.payload.messageTypes.success">
            <Button class="udir-link" @click="toggleDetails">
                {{ showDetails ? "Skjul detaljer" : "Vis detaljer" }}
            </Button>
        </div>
    </div>
</template>

<script>
import { messageTypes } from "../healthUtils";

export default {
    props: {
        className: String,
        title: String,
        lastExecuted: String,
        isLoading: Boolean,
        payload: Object,
        handleRefresh: Function,
        notSupported: Boolean,
        hidePagesChecked: {
            type: Boolean,
            default: false
        }
    },
    components: {},
    data() {
        return {
            isRefreshing: false,
            showDetails: false,
            messageTypesMeta: messageTypes
        }
    },
    computed: {
        hasData() {
            return typeof this.lastExecuted == "string" && this.lastExecuted != "";
        },
        hasErrors() {
            return this.payload?.messageTypes?.error > 0;
        },
        totalMessagesCount() {
            let returnCount = 0;

            const messageTypeKeys = this.payload?.messageTypes != null ? Object.keys(this.payload?.messageTypes) : [];
            for (const messageTypeKeysItem of messageTypeKeys) {
                returnCount += this.payload?.messageTypes[messageTypeKeysItem];
            }

            return returnCount;
        },
        hasMessagesCount() {
            return this.totalMessagesCount > 0;
        },
        categories() {
            const returnMessageTypes = [];

            const messageTypeKeys = this.payload?.messageTypes != null ? Object.keys(this.payload?.messageTypes) : [];
            for (const messageTypeKeysItem of messageTypeKeys) {
                if (this.payload.messageTypes[messageTypeKeysItem] > 0) {
                    const title = messageTypes[messageTypeKeysItem].nb;
                    const color = messageTypes[messageTypeKeysItem]._color;
                    const count = this.payload?.messageTypes[messageTypeKeysItem];

                    returnMessageTypes.push({
                        title: title,
                        color: color,
                        count: count
                    });
                }
            }

            return returnMessageTypes;
        },
        totalPages() {
            let returnCount = 0;

            const contentTypeKeys = this.payload?.contentTypes != null ? Object.keys(this.payload?.contentTypes) : [];
            for (const contentTypeKeysItem of contentTypeKeys) {
                returnCount += this.payload?.contentTypes[contentTypeKeysItem];
            }

            return returnCount;
        },
    },
    methods: {
        calculateWidth(count) {
            const totalCount = this.totalMessagesCount;
            return totalCount ? (count / totalCount) * 100 : 0;
        },
        async refreshData() {
            this.isRefreshing = true;
            await this.handleRefresh();
            this.isRefreshing = false;
        },
        toggleDetails() {
            this.showDetails = !this.showDetails;
        }
    }
};
</script>

<style>
@keyframes dots {
  0%, 20% {
    content: '';
  }
  40% {
    content: '.';
  }
  60% {
    content: '..';
  }
  80%, 100% {
    content: '...';
  }
}

.udir-link {
    border: 0px;
    border-bottom: 2px solid transparent;
    font-weight: 600;
    background: transparent;
    color: #0057B2;
    padding: 0px;
}

.udir-link:hover,
.udir-link:focus {
    border-color: #0057B2;
}

.udir-link:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    border-color: transparent;
    color: #212529;
}

.udir-link-dots::after {
  content: '.';
  animation: dots 1s steps(5, end) infinite;
}

@keyframes rotate-animation {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.health-card {
    width: 100%;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    border-radius: 5px;
    padding: 30px 20px;
    padding-bottom: 18px;
    min-height: 200px;
    border: 2px solid #ebebeb;
}

.health-card-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.health-card-header-title {
    margin-right: 20px;
}

.health-card-header-title h2 {
    font-size: 24px;
}

.health-card-header-title p {
    opacity: 60%;
}

.health-card-header-button-refresh {
    border: none;
    padding: 0px;
    transition: transform 1s;
    background: transparent;
}

.health-card-header-button-refresh.--loading {
    animation: rotate-animation 2s linear infinite;
}

.health-card-body {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    margin-bottom: 28px;
}

.health-card-body.--init {
    justify-content: flex-end;
    align-items: flex-start;
    margin-bottom: 0px;
}

.health-card-body-support {
    opacity: 60%;
    margin-bottom: 0px;
}

.health-card-body-header p {
    display: flex;
    justify-content: flex-start;
    align-items: flex-end;
    line-height: 16px;
    font-size: 16px;
}

.health-card-body-header b {
    font-size: 48px;
    font-weight: 500;
    line-height: 37px;
    margin: 0px;
    margin-right: 16px;
    color: #3b873e;
}

.health-card-body-header b.--error {
    color: #e31b0c;
}

.health-card-body-categories ul {
    margin-bottom: 0px;
    padding-left: 0px;
    list-style: none;
}

.health-card-body-categories li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.health-card-body-categories li p {
    margin-bottom: 0px;
}

.health-card-body-categories .list-item-title {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-right: 40px;
}

.health-card-body-categories .list-item-title span {
    border-radius: 50px;
    display: flex;
    width: 12px;
    height: 12px;
    margin-right: 8px;
}

.health-card-body-categories-bar {
    display: flex;
    height: 40px;
    margin-bottom: 20px;
}

.health-card-body-categories-bar span {
    display: flex;
    height: 100%;
}

.health-card-body.--details {
    max-height: 300px;
    overflow-x: auto;
}

.health-card-body.--details .health-card-detail {
    padding-bottom: 30px;
}

.health-card-body.--details .health-card-detail:last-child {
    padding-bottom: 0px;
}

.health-card-detail h3 {
    font-weight: 600;
    font-size: 14px;
    padding: 0;
    margin-bottom: 15px;
}

.health-card-detail ul {
    padding-left: 0px;
    list-style: none;
}

.health-card-detail ul li {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    margin-bottom: 8px;
}

.health-card-detail li span {
    width: 8px;
    height: 8px;
    display: flex;
    border-radius: 50px;
    padding-right: 8px;
}

.health-card-detail li p {
    margin-bottom: 0px;
    padding-left: 8px;
}
</style>

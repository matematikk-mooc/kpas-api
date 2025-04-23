<template>
  <div>
    <p>
    </p>
    <p>
      <button
      class="kpas-button"
      :disabled="isLoadingDiploma"
      :class="{
        'btn-primary': !isLoadingDiploma,
        'btn-secondary disabled': isLoadingDiploma,
      }"
      @click="fetchDiploma"
      >
      Last ned diplomet
    </button>
  </p>
  <div v-if="isLoadingDiploma" class="alert alert-warning kpasAlert">Laster ned diplomet <div class="spinner-border text-danger"></div></div>
  <div v-if="diplomaLoaded" class="alert alert-success kpasAlert">Diplomet er lastet ned til din nedlastingsmappe.</div>
</div>
</template>

<script>
import api from "../api";
export default {
  name: "Diploma",
  data() {
    return {
      everythingIsReady: false,
      isLoadingDiploma: false,
      diplomaLoaded: false,
    };
  },
  methods: {
    iframeresize() {
      this.$nextTick(function () {
        var h = document.body.offsetHeight + 120;
        parent.postMessage(
        JSON.stringify({ subject: "lti.frameResize", height: h }),
        "*"
        );
      });
    },
    postMessageToParent(subject) {
      const message = {
        subject: subject
      };
      window.parent.postMessage(JSON.stringify(message), "*");
    },
    getBgColor() {
      this.postMessageToParent('kpas-lti.getBgColor');
    },
    connectToParent() {
      if(this.connectedToParent === true) {
        return;
      }
      this.postMessageToParent('kpas-lti.connect');
      window.setTimeout(this.connectToParent, 500);
    },
    async fetchDiploma() {
      this.diplomaLoaded = false;
      this.isLoadingDiploma = true;
      const response = await api.get("/diploma/pdf", {
        params: {
          cookie: window.cookie
        },
        responseType: 'blob'
      });
      this.isLoadingDiploma = false;
      this.diplomaLoaded = true;
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', 'diplom.pdf');
      document.body.appendChild(link);
      link.click();
    },
  },
  async created() {
    this.iframeresize();
    const mql = window.matchMedia('(max-width: 400px)');
    var self = this;
    mql.onchange = (e) => {
      self.iframeresize();
    }

    window.addEventListener('message', async function(evt) {
      try {
        let msg = JSON.parse(evt.data);
        if(msg.subject == "kpas-lti.ltibgcolor" && msg.bgColor) {
          document.body.style.backgroundColor = msg.bgColor;
        } else if(msg.subject == "kpas-lti.ltiparentready") {
          self.connectedToParent = true;
          self.getBgColor();
        }
      } catch(e) {
        //This message is not for us.
      }
    }, false);
    self.connectToParent();
  },
};
</script>

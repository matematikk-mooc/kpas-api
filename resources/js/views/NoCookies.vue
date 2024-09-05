<template>
  <div>
    <div v-if="stateNotFound">
      <p>Kunne ikke autentisere. Vennligst prøv igjen.</p>
      <p> {{ errorMessage }} </p>
    </div>
  </div>
</template>

<script>
export default {
  name: "NoCookiesView",
  props: {
    state: "",
    nonce: "",
    targeturl: "",
    storagetarget: "",
    platformhost: ""

  },
  data() {
    return {
      stateNotFound : false,
      errorMessage : ""
    }
  },
  methods: {
    postMessageToParent(subject) {
      const message = {
        subject: subject
      };
      window.parent.postMessage(JSON.stringify(message), "*");
    },
    getRoles() {
      this.postMessageToParent("kpas-lti.getcurrentuserroles");
    },
  },
  created() {
    let OIDCHost = "https://sso.canvaslms.com";
    if(this.platformhost.includes(".test.")) {
      OIDCHost = "https://sso.test.canvaslms.com";
    }
    else if(this.platformhost.includes(".beta.")) {
      OIDCHost = "https://sso.beta.canvaslms.com";
    }
    let platformOIDCLoginURL = OIDCHost + "/api/lti/authorize_redirect?";
    let frameName = this.storagetarget;
    let parentWindow = window.parent || window.opener;
    let targetFrame = parentWindow.frames[frameName];

    const self = this;

    const redirect = () => {
      window.location.href = platformOIDCLoginURL + this.targeturl;
    };

    window.addEventListener('message', function(event) {
      console.log("API_LTI_MESSAGE_RECEIVED", event);

      if (event.data.subject == "kpas-lti.rolesofuser"){
        if((event.data.roles.includes("admin") || event.data.roles.includes("teacher")) && event.data.path.endsWith("/edit") ) {
          redirect();
        } else {
          self.errorMessage = "Du har ikke tilgang til denne ressursen";
          self.stateNotFound = true;
        }
        return;
      }

      if (typeof event.data !== "object") {
        self.errorMessage = "Ugyldig datatype";
        self.stateNotFound = true;
        return;
      }

      if (event.data.subject !== "lti.put_data.response" && event.data.subject !== "lti.get_data.response") {
        self.errorMessage = "Ugyldig emnefelt: " + event.data.subject;
        self.stateNotFound = true;
        return;
      }

      let wantedState = "kpas_state_" + self.state;
      if (event.data.message_id !== wantedState) {
        self.errorMessage = "Ugyldig message_id: " + event.data.message_id;
        self.stateNotFound = true;
        return;
      }

      if (event.origin !== OIDCHost) {
        self.errorMessage = "Ugyldig avsender: " + event.origin;
        self.stateNotFound = true;
        return;
      }

      if (event.data.error) {
        self.errorMessage = "Feilmelding: " + event.data.error.code + " " + event.data.error.message;
        self.stateNotFound = true;
        return;
      }
      redirect();
    });

    targetFrame.postMessage({
      "subject": "lti.put_data",
      "message_id": "kpas_state_" + this.state,
      "key": "lti1p3_" + this.state,
      "value": this.state
    }, OIDCHost);

    setTimeout(function() {
      self.getRoles();
    }, 3000);
  }

}

</script>

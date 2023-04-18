<template>
  <div v-if="stateNotFound">
    <p>Kunne ikke autentisere. Vennligst prøv igjen.</p>
    <p> {{ errorMessage }} </p>
  </div>
</template>

<script>
export default {
  name: "NoCookiesView",
  props: {
    state: "",
    nonce: "",
    targeturl: "",
    storagetarget: ""
    
  },
  data() {
    return {
      stateNotFound : false,
      errorMessage : ""
    }
  },
  created() {
    document.body.style.backgroundColor = "#eaeaea"
    let platformOIDCLoginURL = "https://bibsys.test.instructure.com/api/lti/authorize_redirect?"
    let platformOrigin = new URL(platformOIDCLoginURL).origin;
    let frameName = this.storagetarget;
    let parent = window.parent || window.opener;
    let targetFrame = frameName === "_parent" ? parent : parent.frames[frameName];
    const redirect = () => {
      window.location.href = platformOIDCLoginURL + this.targeturl
    }
    
    //this is not recognized inside eventlistener
    var self = this
    window.addEventListener('message', function (event) {
      // This isn't a message we're expecting
      
      if (typeof event.data !== "object"){
        this.errorMessage = "Ugyldig datatype";
        this.stateNotFound = true;
        return;
      }
      
      // Validate it's the response type you expect
      if (event.data.subject !== "lti.put_data.response" && event.data.subject !== "lti.get_data.response") {
        this.errorMessage = "Ugyldig emnefelt: " + event.data.subject;
        return;
      }
      
      // Validate the message id matches the id you sent
      let wantedState = "kpas_state_"+ self.state
      if (event.data.message_id !== wantedState) {
        this.errorMessage = "Ugyldig message_id: " + event.data.message_id;
        this.stateNotFound = true;
        // this is not the response you're looking for
        return;
      }
      
      // Validate that the event's origin is the same as the derived platform origin
      if (event.origin !== platformOrigin) {
        this.errorMessage = "Ugyldig avsender: " + event.origin;
        this.stateNotFound = true;
        return;
      }
      
      // handle errors
      if (event.data.error){
        // handle errors
        this.errorMessage = "Feilmelding: " + event.data.error.code + " " + event.data.error.message;
        this.stateNotFound = true;
        return;
      }
      console.log(event.data)
      redirect()
    })
    
    targetFrame.postMessage({
      "subject": "lti.put_data",
      "message_id": "kpas_state_" + this.state,
      "key": "lti1p3_" + this.state,
      "value": this.state
    } , platformOrigin )
    
  }
}            

</script>

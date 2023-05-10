<template>
    <div class="alert alert-warning kpasAlert">Du må fullføre alle kravene før du kan motta diplom.</div>
</template>

<script>
export default {
  name: "NoDiploma",
  data() {
    return {
    };
  },
  methods: {
    iframeresize() {
      this.$nextTick(function () {
        var h = $("body").height() + 120;
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
          console.log("Received background color.");
          console.log(msg.bgColor)
          document.body.style.backgroundColor = msg.bgColor;
        } else if(msg.subject == "kpas-lti.ltiparentready") {
          console.log("parent ready")
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

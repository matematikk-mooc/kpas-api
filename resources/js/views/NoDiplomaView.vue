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
  },
  async created() {
    this.iframeresize();
    const mql = window.matchMedia('(max-width: 400px)');
    var self = this;
    mql.onchange = (e) => { 
      self.iframeresize();
    }
  },
};
</script>
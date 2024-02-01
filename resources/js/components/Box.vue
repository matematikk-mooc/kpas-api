<template>
  <div :class="['box', `box--${type}`]">
    <div class="box-content">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Box',
  props: {
    type: {
      type: String,
      default: 'grey',
      validator: (value) => ['grey', 'green', 'red'].includes(value),
    },
  },
};
</script>

<style lang="scss">
.box {
  width: 100%;
  display: flex;
  justify-content: center;

  &--grey {
    background-color: #E3F2EB;
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
  }

  &--green {
    background-color: #E3F2EB;
  }

  &--red {
    background-color: #ffebee;
  }

  .box-content {
    width: 100%;
    max-width: 960px; // Adjust the maximum width according to your design
    padding: 1.25rem; // Adjust the padding according to your design
  }
}
</style>

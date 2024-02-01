<template>
  <div :class="['message', `message--${type}`]">
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: 'default',
      validator: (value) => ['default', 'error', 'success', 'info', 'warn'].includes(value),
    },
  },
};
</script>

<style lang="scss">
.message {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  padding: 15px;
  // margin-bottom: 15px;
  margin: 15px 0 15px 0;
  border-radius: 0.5rem;

  &--default {
    background-color: #f1f1f1;
    // border: 2px solid #999999;
  }

  &--error {
    background-color: #ffebee;
    // border: 2px solid #c62828;
  }

  &--success {
    background-color: #E3F2EB;
    // border: 2px solid #254b38;
  }
  &--info{
    background-color: #f5f7f9;
    // border: 2px solid #2e3c51;
  }
  &--warn{
    background-color:#fff3e0;
    // border:2px solid #EF6C00;
  }
}
</style>

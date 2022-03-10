<template>
    <div id="bind-attribute">
  <span v-bind:title="message">
    Наведи на меня курсор на пару секунд, чтобы
    увидеть динамически связанное значение title!
  </span>
    </div>

</template>

<script>
export default {
    data() {
        return {
            message: 'Страница загружена ' + new Date().toLocaleString()
        }
    }
}
</script>

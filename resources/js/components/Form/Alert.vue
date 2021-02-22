<template>
    <div class="alert alert-warning" role="alert" v-if="alertMessage.length >0">
        <p v-for="message in alertMessage"></p>
    </div>
</template>

<script>
    export default {
        name: "Alert",
        data:function () {
            return {alertMessage:[]}
        },
    }
</script>

<style scoped>

</style>
<template>
    <div >
        <input :value="value" type="text" ref="picker" class="dpk form-control"/>
    </div>
</template>

<script>
    export default {
        name: "datepicker",
        props:['value'],
        created() {
            var me = this;
            var options = {"singleDatePicker": true,
                "autoApply": true,
                "timePicker": true,
                "timePicker24Hour": true,
                locale: {
                    format: 'YYYY-MM-DD HH:mm:ss'
                }};
            this.$nextTick(function(){

                $(me.$refs.picker).daterangepicker(options, function(start, end, label) {
                    me.$emit('input',start.format('YYYY-MM-DD HH:mm:ss'));
                })
            });
        }
    }
</script>

<style scoped>

</style>
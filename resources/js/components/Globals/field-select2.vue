<template>
    <v-select v-model="selected" :options="options" @search="onSearch"></v-select>
</template>
<script>
    import vSelect from 'vue-select'
    import axios from "axios";
    export default {
        props:{
            url:{
                type:String,
                required:true
            },
            value:{
                type:[String,Number]
            }
        },
        components: {
            vSelect
        },
        data(){
            return {
                options:[],
                selected:'',
            }
        },
        watch: {
            selected(val) {
                if(val){
                    this.$emit('input',val.label)
                }else{
                    this.$emit('input',null)
                }
            },
            value(val) {
                if(val!=''){
                    this.getData(val);
                }
            }
        },
        methods:{
            onSearch(search, loading) {
                loading(true);
                this.search(loading, search, this);
            },
            search: _.debounce((loading, search, vm) => {
                if(search!=''){
                    axios({
                        method: 'post',
                        url: vm.url,
                        data: {search:search}
                    }).then(response => {
                        var res = response.data;
                        if (res.status == 1) {
                            vm.options = res.data
                        }
                        loading(false);
                    });
                }else{
                    loading(false);
                }

            }, 350),
            getData(value){
                if(value != ''){
                    var me = this;
                    axios({
                        method: 'post',
                        url: me.url,
                        data: {search:value,first:true}
                    }).then(response => {
                        var res = response.data;
                        if (res.status == 1) {
                            me.$emit('updateContract',res.data);
                            me.selected = res.data
                        }else{
                            me.row={};
                        }
                    });
                }

            }
        }

    };
</script>

<template>
    <div>
        <vue-bootstrap4-table :rows="rows" :columns="columns" :config="config" @on-change-query="onChangeQuery" :total-rows="total_rows" :show-loader="showLoader"  :actions="actions" @on-multi-delete="onMultiDelete" @on-export="onExport" @on-import="onImport" @on-add-new="onAddNew">
            <template slot="status_html" slot-scope="props"  >
                <span v-html="props.cell_value"></span>
            </template>
            <template slot="role_html" slot-scope="props"  >
                <span v-html="props.cell_value"></span>
            </template> <template slot="permission_html" slot-scope="props"  >
                <span v-html="props.cell_value"></span>
            </template>
            <template slot="auto_renewed_html" slot-scope="props" >
                <span v-html="props.cell_value"></span>
            </template>
            <template slot="auto_renewed_contract_html" slot-scope="props" >
                <span v-html="props.cell_value"></span>
            </template>
            <template slot="action" slot-scope="props">
                <div>
                    <router-link class="btn btn-sm  text-info" :to="{name:propRoute.edit,params:{'id':props.row.id}}" :form="props.row"><i class="fas fa-edit"></i></router-link>
                    <button class="btn btn-sm text-danger" @click="$emit('removePost',props.row)"><i class="fas fa-times"></i></button>
                </div>
            </template>
            <template slot="paginataion-previous-button">
                Previous
            </template>
            <template slot="paginataion-next-button">
                Next
            </template>
        </vue-bootstrap4-table>
        <input type="file" @change="import_file($event)"  id="importFileButton">
    </div>
</template>

<script>
    import VueBootstrap4Table from 'vue-bootstrap4-table';
    import axios from "axios";
    import router from "../../route";

    export default {
        name: "datatable",
        components: {
            VueBootstrap4Table
        },
        props:['contract_type','rows','columns','total_rows','queryParams','propRoute','showLoader','listActions'],
        data() {
            return {
                actions_default: [
                    {
                        btn_text: "<i class=\"fas fa-plus\"></i> Tạo mới",
                        event_name: "on-add-new",
                        class: "btn-sm btn-primary text-white mr-2",
                        event_payload: {
                        }
                    },
                    {
                        btn_text: "<i class=\"fas fa-file-import\"></i> Import Excel",
                        event_name: "on-import",
                        class: "btn-sm btn-primary text-white",
                        event_payload: {
                        }
                    },
                    {
                        btn_text: "<i class=\"fas fa-file-export\"></i> Export Excel",
                        event_name: "on-export",
                        class: "btn-sm btn-primary text-white  mx-2",
                        event_payload: {
                        }
                    },
                    {
                        btn_text: "<i class=\"fas fa-trash-alt\"></i> Delete Selected",
                        event_name: "on-multi-delete",
                        class: "btn-sm btn-danger",
                        event_payload: {
                        }
                    },
                ],
                config: {
                    rows_selectable: true,
                    checkbox_rows:true,
                    card_mode: false,
                    show_refresh_button:false,
                    show_reset_button:false,
                    server_mode:true,
                    loaderText:'Loading...',
                    global_search:{
                        visibility: false,
                    },
                    per_page: 15,
                    per_page_options: [15,30,60, 90, 120],
                },
            }
        },
        computed:{
            actions:function () {
                var me = this;
                 var array = this.actions_default.filter(function (item) {
                     if(me.listActions.indexOf(item.event_name)!='-1'){
                         return item;
                     }
                })
                return array;
            }
        },
        methods: {

            onChangeQuery(queryParams) {
                this.$emit('update-query-params',queryParams);
            },
            onAddNew(payload){
                var me = this;
                me.isLoading = true;
                if(me.$parent.contract_type){
                    router.push({'name':me.propRoute.add, params:{'type':me.$parent.contract_type,'id':0}})
                    return true;
                }
                if(me.$parent.content_type){
                    router.push({'name':me.propRoute.add, params:{'type':me.$parent.content_type,'id':0}})
                    return true;
                }
                router.push({'name':me.propRoute.add})
                return true;
            },

            onMultiDelete(payload) {
                payload.event_payload.queryParams = this.queryParams;
                this.$emit('removePosts',payload);
            },
            onImport(payload){
                $('#importFileButton').trigger('click')
            },
            onExport(payload){
                var me = this;
                me.isLoading = true;
                payload.queryParams = me.queryParams;
                if(me.$parent.contract_type){
                    payload.contract_type = me.$parent.contract_type ;
                }
                if(me.$parent.content_type){
                    payload.content_type = me.$parent.content_type ;
                }
                if(me.$parent.invoice_type){
                    payload.invoice_type = me.$parent.invoice_type ;
                }
                if(payload.selectedItems.length > 0) {
                    var ids = [];
                    $.each(payload.selectedItems, function (index, value) {
                        ids.push(value.id);
                    });
                    payload.selectedItems = ids;
                }
                $.ajax({
                    url:me.propRoute.module.export,
                    type:'POST',
                    data:payload,
                    dataType:'json',
                    success:function (data) {
                        if(data.status==1){
                            window.open(data.url)
                        }else{
                            cmsHattApp.showError(data)
                        }
                        me.isLoading = false;
                    },
                    error:function (e) {
                        me.isLoading = false;
                        cmsHattApp.showAjaxError(e);
                    }
                });
            },
            import_file(event){
                var me = this;
                var data = new FormData();
                me.isLoading = true;
                if(me.$parent.contract_type){
                    data.append('contract_type',me.$parent.contract_type)
                }
                if(me.$parent.content_type){
                    data.append('content_type',me.$parent.content_type)
                }
                if(me.$parent.invoice_type){
                    data.append('invoice_type',me.$parent.invoice_type)
                }
                data.append('file', event.target.files[0]);
                $.ajax({
                    url:me.propRoute.module.import,
                    type:'POST',
                    data:data,
                    dataType:'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success:function (data) {
                        me.isLoading = false;
                        if(data.status==1){
                            cmsHattApp.showSuccess(data);
                            setTimeout(function () {
                                location.reload();
                            },2000)
                        }else{
                            cmsHattApp.showError(data)
                        }

                    },
                    error:function (e) {
                        me.isLoading = false;
                        cmsHattApp.showAjaxError(e);
                    }
                });
            },

        },
    }
</script>

<style scoped>

</style>

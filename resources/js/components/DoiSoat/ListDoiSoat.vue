<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mb-5">
                    <div class="card-header bg-info text-white">
                        <h3 class="text-center">Quản lý Đối soát –Thanh Toán {{name}}</h3>

                    </div>
                    <hatt-datatable  :rows="rows" :columns="columns" :total_rows="total_rows" :queryParams="queryParams" :propRoute="propRoute"  :listActions="listActions" @update-query-params="updateQueryParams" @removePost="removePost" @removePosts="removePosts">

                    </hatt-datatable>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from "axios";
    import route from "../../route";

    export default {
        name: "ListDoiSoatThanhToan",
        data() {
            return {
                name:'',
                rows:[],
                columns:[],
                queryParams: {
                    sort: [],
                    filters: [],
                    global_search: "",
                    per_page: 30,
                    page: 1,
                },
                showLoader: true,
                total_rows: 0,
                invoice_type:'',
                propRoute:{
                    add:"invoice_add",
                    edit:"invoice_edit",
                    module:cmsHatt.route.invoice
                },
                listActions:['on-add-new','on-import','on-export','on-multi-delete']

            }
        },
        watch: {
            $route(to,from){
                if(to.params.type!=from.params.type){
                    this.invoice_type = to.params.type;
                }
            },
            invoice_type: function (val) {
                this.getName();
                this.fetchData();
            },
        },
        created() {
            this.invoice_type = this.$route.params.type;
        },
        methods: {
            getName(){
                var  invoiceType = JSON.parse(cmsHatt.invoice_type);
                if(typeof invoiceType[this.invoice_type] !='undefined'){
                    this.name=invoiceType[this.invoice_type];
                }
            },
            updateQueryParams(event){
                this.queryParams= event;
                this.fetchData();
            },
            fetchData() {
                let self = this;
                var page_url = page_url || cmsHatt.route.invoice.search;
                axios({
                    method: 'post',
                    url: page_url,
                    data: {
                        invoice_type: this.invoice_type,
                        queryParams: this.queryParams,
                        page: this.queryParams.page
                    }
                }).then((response) => {
                    var res = response.data;
                    if (res.status == 1) {
                        self.rows = res.data.rows;
                        self.columns = res.data.columns;
                        self.total_rows = res.data.total_rows;
                    }
                });
            },
            removePost:function (row) {
                var me = this;
                cmsHattApp.showConfirm({
                    message: 'Bạn có chắc chắn muốn xóa '+row.name,
                    callback: function(result){
                        if(result){
                            me.isLoading = true;
                            $.ajax({
                                url:cmsHatt.route.invoice.delete,
                                type:'POST',
                                data:{
                                    id:row.id
                                },
                                dataType:'json',
                                success:function (data) {
                                    if(data.status === 1){
                                        cmsHattApp.showSuccess(data.message);
                                    }
                                    if(data.status === 0){
                                        cmsHattApp.showError(data);
                                    }
                                    me.isLoading = false;
                                    me.fetchData();
                                },
                                error:function (e) {
                                    me.isLoading = false;
                                    cmsHattApp.showAjaxError(e);
                                    me.fetchData();
                                }
                            });
                        }
                    }
                })
            },
            removePosts:function (payload) {
                var me = this;
                if(payload.selectedItems.length > 0){
                    var ids = [];
                    $.each(payload.selectedItems, function(index, value) {
                        ids.push(value.id);
                    });
                    cmsHattApp.showConfirm({
                        message: 'Bạn có chắc chắn muốn xóa?',
                        callback: function(result){
                            if(result){
                                me.isLoading = true;
                                $.ajax({
                                    url:cmsHatt.route.invoice.delete,
                                    type:'POST',
                                    data:{
                                        ids:ids,
                                    },
                                    dataType:'json',
                                    success:function (data) {
                                        if(data.status === 1){
                                            cmsHattApp.showSuccess(data.message);
                                        }
                                        if(data.status === 0){
                                            cmsHattApp.showError(data);
                                        }
                                        me.isLoading = false;
                                        me.fetchData();
                                    },
                                    error:function (e) {
                                        me.isLoading = false;
                                        cmsHattApp.showAjaxError(e);
                                        me.fetchData();
                                    }
                                });
                            }
                        }
                    })
                }else{
                    cmsHattApp.showError({message:'Bạn cần chọn để xóa'})
                }
            },

        }
    }
</script>

<style scoped>

</style>

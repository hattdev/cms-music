<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card  mb-5">
                    <div class="card-header bg-info text-white">
                        <h3 class="text-center">Quản lý hợp đồng {{name}}</h3>
                    </div>
                    <div class="card-body pt-0">
                        <hatt-datatable :contract_type="contract_type" :rows="rows" :columns="columns" :total_rows="total_rows" :queryParams="queryParams" :propRoute="propRoute" :showLoader="showLoader" :listActions="listActions" @update-query-params="updateQueryParams" @removePost="removePost" @removePosts="removePosts"></hatt-datatable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from "axios";

    export default {
        name: "QuanLyHopDongCaSi",
        data() {
            return {
                name: '',
                contract_type: '',
                rows: [],
                columns: [],
                queryParams: {
                    sort: [],
                    filters: [],
                    global_search: "",
                    per_page: 30,
                    page: 1,
                },
                showLoader: true,
                total_rows: 0,
                propRoute: {
                    add: 'contract_add',
                    edit: 'contract_edit',
                    module: cmsHatt.route.contract
                },
                listActions:['on-add-new','on-import','on-export','on-multi-delete'],

            }
        },
        watch: {
            $route(to, from) {
                if (to.params.type != from.params.type) {
                    this.contract_type = to.params.type;
                }
            },
            contract_type: function (val) {
                this.getName();
                this.fetchData();

            },
        },
        created() {
            this.contract_type = this.$route.params.type;
        },
        methods: {
            getName() {
                var ContractType = JSON.parse(cmsHatt.contract_type);
                if (typeof ContractType[this.contract_type] != 'undefined') {
                    this.name = ContractType[this.contract_type];
                }
            },
            updateQueryParams(event) {
                this.queryParams = event;
                this.fetchData();
            },
            fetchData() {
                let self = this;
                self.showLoader = true;
                var page_url = page_url || cmsHatt.route.contract.search;
                axios({
                    method: 'post',
                    url: page_url,
                    data: {
                        contract_type: this.contract_type,
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
                self.showLoader = false;
            },
            removePost: function (row) {
                var me = this;
                cmsHattApp.showConfirm({
                    message: 'Bạn có chắc chắn muốn xóa ' + row.contract_number,
                    callback: function (result) {
                        if (result) {
                            me.isLoading = true;
                            $.ajax({
                                url: cmsHatt.route.contract.delete,
                                type: 'POST',
                                data: {
                                    ids: [row.id],
                                },
                                dataType: 'json',
                                success: function (data) {
                                    if (data.status === 1) {
                                        cmsHattApp.showSuccess(data.message);
                                    }
                                    if (data.status === 0) {
                                        cmsHattApp.showError(data);
                                    }
                                    me.isLoading = false;
                                    me.fetchData();
                                },
                                error: function (e) {
                                    me.isLoading = false;
                                    cmsHattApp.showAjaxError(e);
                                    me.fetchData();
                                }
                            });
                        }
                    }
                })
            },
            removePosts: function (payload) {
                var me = this;
                if (payload.selectedItems.length > 0) {
                    var ids = [];
                    $.each(payload.selectedItems, function (index, value) {
                        ids.push(value.id);
                    });
                    cmsHattApp.showConfirm({
                        message: 'Bạn có chắc chắn muốn xóa?',
                        callback: function (result) {
                            if (result) {
                                me.isLoading = true;
                                $.ajax({
                                    url: cmsHatt.route.contract.delete,
                                    type: 'POST',
                                    data: {
                                        ids: ids,
                                    },
                                    dataType: 'json',
                                    success: function (data) {
                                        if (data.status === 1) {
                                            cmsHattApp.showSuccess(data.message);
                                        }
                                        if (data.status === 0) {
                                            cmsHattApp.showError(data);
                                        }
                                        me.isLoading = false;
                                        me.fetchData();
                                    },
                                    error: function (e) {
                                        me.isLoading = false;
                                        cmsHattApp.showAjaxError(e);
                                        me.fetchData();
                                    }
                                });
                            }
                        }
                    })
                } else {
                    cmsHattApp.showError({message: 'Bạn cần chọn để xóa'})
                }
            },


        }
    }
</script>

<style scoped>

</style>

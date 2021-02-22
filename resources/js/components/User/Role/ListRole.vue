<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mb-5">
                    <div class="card-header bg-info text-white">
                        <h3 class="text-center">Quản lý quyền user</h3>

                    </div>
                    <div class="card-body pt-0">
                        <hatt-datatable :rows="rows" :columns="columns" :total_rows="total_rows" :queryParams="queryParams" :propRoute="propRoute"
                                        :showLoader="showLoader" :listActions="listActions" @update-query-params="updateQueryParams" @removePost="removePost" @removePosts="removePosts">

                        </hatt-datatable>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
    import axios from "axios";
    export default {
        name: "QuanLyRole",
        data() {
            return {
                name: '',
                rows: [],
                columns: [],
                queryParams: {
                    sort: [],
                    filters: [],
                    global_search: "",
                    per_page: 30,
                    page: 1,
                },
                total_rows: 0,
                showLoader: true,
                propRoute: {
                    add: 'user_role_add',
                    edit: 'user_role_edit',
                    module: cmsHatt.route.user.role
                },
                listActions:['on-add-new']
            }
        },
        watch: {},
        created() {
        },
        methods: {
            updateQueryParams(event) {
                this.queryParams = event;
                this.fetchData();
            },
            fetchData() {
                let self = this;
                self.showLoader = true;
                var page_url = page_url || cmsHatt.route.user.role.search;
                axios({
                    method: 'post',
                    url: page_url,
                    data: {
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
                    message: 'Bạn có chắc chắn muốn xóa ' + row.name,
                    callback: function (result) {
                        if (result) {
                            me.isLoading = true;
                            $.ajax({
                                url: cmsHatt.route.user.delete,
                                type: 'POST',
                                data: {
                                    id: [row.id]
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
                                    url: cmsHatt.route.user.delete,
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

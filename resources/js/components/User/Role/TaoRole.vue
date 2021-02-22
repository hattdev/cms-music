<template>
    <div class="container">

        <form v-on:submit="saveForm($event)" >
            <div class="card w-100 mb-5">
                <div class="card-header bg-info text-white">
                    <h3>{{name}}</h3>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" v-show="Object.keys(errors).length>0">
                            <div v-for="message in errors" class="font-weight-bold">{{message.join(',')}}</div>
                            <!--                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">-->
                            <!--                        <span aria-hidden="true">&times;</span>-->
                            <!--                    </button>-->
                        </div>
                    </div>

                    <div class="col-12">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-pills">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#infoTab">Thông tin chính</a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane  active" id="infoTab">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label>Tên</label>
                                                <input type="text" v-model="form.name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3 col-lg-4" v-for="permission in permissions" >
                                                <div class="form-check mb-2 pt-4">
                                                    <input type="checkbox" v-model="checked_permissions" :value="permission" class="form-check-input" :id="permission">
                                                    <label class="form-check-label  badge-info text-white" :for="permission"><b>{{permission}}</b></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-12 ">
                        <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Lưu</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import axios from "axios";
    import router from '../../../route';

    export default {
        name: "TaoRole",
        data: function () {
            return {
                //
                name: 'Thêm mới quyền',
                form: [],
                form_default: {
                    name: '',
                    id: null
                },
                checked_permissions: [],
                permissions:{},
                errors: [],
                onSubmit: false,
        }
        },
        watch: {
            $route(to, from) {

            },
        },
        created() {
            this.getPost();
            this.getPermissions();
        },
        methods: {
            getName() {
                this.name = 'Thêm mới Quyền';
                if (this.form.id) {
                    this.name = 'Quyền: ' + this.form.name + ' #' + this.form.id
                }
            },
            getPermissions() {
                var page_url = page_url || cmsHatt.route.user.permission.all;
                axios({
                    method: 'post',
                    url: page_url,
                }).then((response) => {
                    var res = response.data;
                    if (res.status == 1) {
                        this.permissions = res.data;
                        this.getName();
                    }
                }).catch((err) => {
                    cmsHattApp.showError({
                        message: err.message,
                        callback: function (result) {
                            router.push({'name': "user_role_manager"})
                        }
                    });
                });
            },
            getPost() {
                var id = this.$route.params.id;
                if (id) {
                    var page_url = page_url || cmsHatt.route.user.role.edit + '/' + id;
                    axios({
                        method: 'post',
                        url: page_url,
                        data: {id: id}
                    }).then((response) => {
                        var res = response.data;
                        if (res.status == 1) {
                            this.form = res.data;
                            this.checked_permissions = res.permissions;
                            this.getName();
                        }
                    }).catch((err) => {
                        cmsHattApp.showError({
                            message: err.message,
                            callback: function (result) {
                                router.push({'name': "user_manager"})
                            }
                        });
                    });
                } else {
                    this.form = this.form_default;
                    this.getName();
                }
            },
            validationForm() {
                this.errors = {};
                if (!this.form.name) {
                    this.errors['name'] = ['Tên không thể bỏ trống'];
                }

                if (Object.keys(this.errors).length > 0) {
                    return false;
                } else {
                    return true;
                }
            },
            saveForm(e) {
                this.errors = [];
                e.preventDefault();
                if (this.onSubmit) {
                    return false;
                }

                if (!this.validationForm()) {
                    return false;
                }
                this.onSubmit = true;
                var page_url = page_url || cmsHatt.route.user.role.store;
                var id = this.$route.params.id;
                if (id) {
                    page_url = cmsHatt.route.user.role.store + '/' + id;
                }
                axios({
                    method: 'post',
                    url: page_url,
                    data: {name:this.form.name,permissions:this.checked_permissions}
                }).then((response) => {
                    var res = response.data;
                    if (res.status == 1) {
                        cmsHattApp.showSuccess({
                            message: res.message,
                            callback: function (result) {
                                router.push({'name': "user_role_manager"})
                            }
                        });
                    } else {
                        cmsHattApp.showError({
                            message: res.message
                        });
                    }
                }).catch((err) => {
                    if (err.response.status === 422) {
                        this.errors = err.response.data.errors || {};
                    }
                });
                this.onSubmit = false;

            },
        }
    }

</script>
<style scoped>

</style>

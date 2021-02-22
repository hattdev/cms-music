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
                                            <div class="form-group col-lg-6">
                                                <label>Email</label>
                                                <input type="email" v-model="form.email" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Password</label>
                                                <input type="password" v-model="form.password" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Số Điện thoại</label>
                                                <input type="text" v-model="form.phone" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Phân Quyền</label>
                                                <hatt-select2 v-model="form.role" :url="role_select_2"></hatt-select2>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Trạng thái</label>
                                                <select v-model="form.status" class="form-control">
                                                    <option disabled value="">Chọn</option>
                                                    <option v-for="option in options" v-bind:value="option.value">
                                                        {{ option.text }}
                                                    </option>
                                                </select>
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
    import router from '../../route';

    export default {
        name: "TaoUser",
        data: function () {
            return {
                //
                name: 'Thêm mới user',
                form: [],
                form_default: {
                    status: '',
                    name: '',
                    email: '',
                    phone: '',
                    password: '',
                    role: '',
                    id: null
                },
                contract_default: {},
                options: [
                    {text: 'Ẩn', value: 'nhap'},
                    {text: 'Hiện', value: 'public'},
                    {text: 'Khóa', value: 'block'},
                ],
                errors: [],
                onSubmit: false,
                role_select_2: cmsHatt.route.user.role.select2
        }
        },
        watch: {
            $route(to, from) {

            },
        },
        created() {
            this.getPost();

        },
        methods: {
            getName() {
                this.name = 'Thêm mới user';
                if (this.form.id) {
                    this.name = 'User: ' + this.form.name + ' #' + this.form.id
                }
            },
            getPost() {
                var id = this.$route.params.id;
                if (id) {
                    var page_url = page_url || cmsHatt.route.user.edit + '/' + id;
                    axios({
                        method: 'post',
                        url: page_url,
                        data: {id: id}
                    }).then((response) => {
                        var res = response.data;
                        if (res.status == 1) {
                            this.form = res.data;
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
                    this.errors['name'] = ['Tên user không thể bỏ trống'];
                }
                if (!this.form.email) {
                    this.errors['email'] = ['Email không thể bỏ trống'];
                }
                if (!this.form.status) {
                    this.errors['status'] = ['Trạng thái không thể bỏ trống'];
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
                var page_url = page_url || cmsHatt.route.user.store;
                var id = this.$route.params.id;
                if (id) {
                    page_url = cmsHatt.route.user.store + '/' + id;
                }
                axios({
                    method: 'post',
                    url: page_url,
                    data: this.form
                }).then((response) => {
                    var res = response.data;
                    if (res.status == 1) {
                        cmsHattApp.showSuccess({
                            message: res.message,
                            callback: function (result) {
                                router.push({'name': "user_manager"})
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

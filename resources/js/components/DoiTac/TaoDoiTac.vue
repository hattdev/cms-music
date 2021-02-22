<template>
    <div class="container-fluid">

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
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#infoOtherTab">Thông tin khác</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#uploadsTab">Uploads</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane  active" id="infoTab">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label>Tên đối tác</label>
                                                <input type="text" v-model="form.name" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Số Hợp Đồng</label>
                                                <hatt-select2 v-model="form.contract_number" :url="contract_url_select2" @updateContract="updateContract"></hatt-select2>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Mã số thuế</label>
                                                <input type="text" v-model="contract_default.tax_code" class="form-control">
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-check mb-2 pt-4">
                                                    <input type="checkbox" v-model="contract_default.auto_renewed" value="1" class="form-check-input" id="autoRenew">
                                                    <label class="form-check-label" for="autoRenew"><b>Tự động gia hạn</b></label>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-6">
                                                <label>Ngày ký HĐ</label>
                                                <hatt-datepicker v-model="contract_default.sign_date"></hatt-datepicker>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Ngày hết hạn HĐ</label>
                                                <hatt-datepicker v-model="contract_default.exp_date"></hatt-datepicker>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Hạng mục cung cấp</label>
                                                <input type="text" v-model="contract_default.items_provided" class="form-control">

                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Phạm vi cung cấp</label>
                                                <input type="text" v-model="contract_default.scope_of_supply" class="form-control">
                                            </div>

                                            <div class="form-group col-lg-6">
                                                <label>Tỷ lệ chia sẻ</label>
                                                <div class="input-group ">
                                                    <input type="number" v-model="contract_default.share_rate" class="form-control ">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Chi Phí MG</label>
                                                <div class="input-group ">
                                                    <input type="number" v-model="form.advance" class="form-control ">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">VND</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
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
                            <div class="tab-pane  fade" id="infoOtherTab">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label>Email</label>
                                                <input type="email" v-model="contract_default.email" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Số Điện thoại</label>
                                                <input type="text" v-model="contract_default.phone" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Địa chỉ</label>
                                                <input type="text" v-model="contract_default.address" class="form-control">
                                            </div>
                                            <div class="form-group col-12">
                                                <label>Chú ý</label>
                                                <hatt-editor v-model="form.note"></hatt-editor>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane  fade" id="uploadsTab">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <div class="font-weight-bold">Danh sách files (HD)</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <hatt-uploads v-model="contract_default.files" :canUpload="false"></hatt-uploads>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <div class="font-weight-bold">Danh sách files</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <hatt-uploads v-model="form.files"></hatt-uploads>
                                                </div>
                                            </div>
                                        </div>

                                        <

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
        name: "TaoDoiTac",
        data: function () {
            return {
                //
                name: 'Thêm mới đối tác',
                form: [],
                form_default: {
                    status: '',
                    contract_name: '',
                    contract_number: '',
                    name: '',
                    note: '',
                    files: '',
                    id: null
                },
                contract_default: {},
                options: [
                    {text: 'Nháp', value: 'nhap'},
                    {text: 'Còn hạn', value: 'con_han'},
                    {text: 'Hết hạn', value: 'het_han'},
                    {text: 'Đã thanh lý', value: 'da_thanh_ly'}
                ],
                errors: [],
                onSubmit: false,
                contract_url_select2: cmsHatt.route.contract.select2

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
                this.name = 'Thêm mới đối tác';
                if (this.form.id) {
                    this.name = 'Đối tác: ' + this.form.name + ' #' + this.form.id
                }
            },
            updateContract(val) {
                this.contract_default = val;
            },
            getPost() {
                var id = this.$route.params.id;
                if (id) {
                    var page_url = page_url || cmsHatt.route.partner.edit + '/' + id;
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
                                router.push({'name': "partner_manager"})
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
                    this.errors['name'] = ['Tên đối tác không thể bỏ trống'];
                }
                if (!this.form.contract_number) {
                    this.errors['contract_number'] = ['Số Hợp Đồng không thể bỏ trống'];
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
                var page_url = page_url || cmsHatt.route.partner.store;
                var id = this.$route.params.id;
                if (id) {
                    page_url = cmsHatt.route.partner.store + '/' + id;
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
                                router.push({'name': "partner_manager"})
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

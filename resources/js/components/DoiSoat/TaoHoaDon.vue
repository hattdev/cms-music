<template>
    <div class="container-fluid">
        <form v-on:submit="saveForm($event)">
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
                                                <label>Tên {{label_name}}</label>
                                                <input type="text" v-model="form.name" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6" v-if="invoice_type == 'personal'">
                                                <label>Tên thật</label>
                                                <input type="text" v-model="form.real_name" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Số Hợp Đồng</label>
                                                <hatt-select2 v-model="form.contract_number" :url="contract_url_select2" @updateContract="updateContract"></hatt-select2>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Trạng thái HĐ</label>
                                                <div v-html="contract_default.status_html"></div>
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
                                                <label>Mã số thuế</label>
                                                <input type="text" v-model="contract_default.tax_code" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Số tài khoản</label>
                                                <input type="text" v-model="form.bank_account_number" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Ngân hàng</label>
                                                <input type="text" v-model="form.bank_name" class="form-control">
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
                                                <label>Doanh thu PN</label>
                                                <div class="input-group ">
                                                    <input type="number" v-model="form.revenue_to_phuong_nam" class="form-control ">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">VND</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Chi Phí MG</label>
                                                <div class="input-group ">
                                                    <input type="number" v-model="form.amount_payment_for_mg" class="form-control ">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">VND</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Số tiền TT cho TG/CS (VAT)</label>
                                                <div class="input-group ">
                                                    <input type="number" v-model="form.amount_payment_for_partner" class="form-control ">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">VND</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Tạm ứng</label>
                                                <div class="input-group ">
                                                    <input type="number" v-model="form.advance" class="form-control ">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">VND</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-6">
                                                <label>Đối soát từ ngày</label>
                                                <hatt-datepicker v-model="form.invoice_start_date"></hatt-datepicker>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Đối soát đến ngày</label>
                                                <hatt-datepicker v-model="form.invoice_end_date"></hatt-datepicker>
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
                                            <div class="form-group col-lg-6" v-if="invoice_type=='personal'">
                                                <label>Số CMND</label>
                                                <input type="text" v-model="contract_default.cmnd" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6" v-if="invoice_type=='personal'">
                                                <label>Ngày sinh</label>
                                                <hatt-datepicker v-model="form.date_of_birth"></hatt-datepicker>
                                            </div>
                                            <div class="form-group col-lg-12">
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
                name: 'Thêm mới biên bản đối soát',
                label_name: 'đối tác',
                form: [],
                form_default: {
                    status: '',
                    invoice_type: '',
                    contract_name: '',
                    contract_number: '',
                    tax_code: '',
                    name: '',
                    real_name: '',
                    date_of_birth: '',
                    bank_name: '',
                    bank_account_number: '',
                    revenue_to_phuong_nam: '',
                    amount_payment_for_mg: '',
                    amount_payment_for_partner: '',
                    invoice_start_date: '',
                    invoice_end_date: '',
                    note: '',
                    files: '',
                    id: null
                },
                contract_default: {},
                options: [
                    {text: 'Nháp', value: 'nhap'},
                    {text: 'Chưa thanh toán', value: 'chua_thanh_toan'},
                    {text: 'Đã thanh toán', value: 'da_thanh_toan'},
                ],
                errors: [],
                onSubmit: false,
                invoice_type: '',
                contract_url_select2: cmsHatt.route.contract.select2

            }
        },
        watch: {
            $route(to, from) {
                if (to.params.type != from.params.type) {
                    this.form_default.invoice_type = this.invoice_type = to.params.type;
                }
            },
            invoice_type: function (val) {
                this.getName();
                this.getPost();
            },
        },
        created() {
            this.form_default.invoice_type = this.invoice_type = this.$route.params.type;
        },
        methods: {
            getName() {
                if (this.invoice_type == "personal") {
                    this.label_name = 'ca sỹ/ Tác giả'
                }
                if (this.form.id) {
                    this.name = this.form.name + ' #' + this.form.id
                }
            },
            updateContract(val) {
                this.contract_default = val;
            },
            getPost() {
                var id = this.$route.params.id;
                if (id) {
                    var page_url = page_url || cmsHatt.route.invoice.edit + '/' + id;
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
                                router.push({'name': "invoice_manager", 'param': {'type': this.invoice_type}})

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
                if (!this.form.contract_number) {
                    this.errors['contract_number'] = ['Số HD không thể bỏ trống'];
                }
                if (!this.form.status) {
                    this.errors['status'] = ['Trạng thái không thể bỏ trống'];
                }
                if (!this.form.invoice_start_date) {
                    this.errors['invoice_start_date'] = ['Ngày bắt đầu ĐS không thể bỏ trống'];
                }
                if (!this.form.invoice_end_date) {
                    this.errors['invoice_end_date'] = ['Ngày kết thúc ĐS không thể bỏ trống'];
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
                var page_url = page_url || cmsHatt.route.invoice.store;
                var id = this.$route.params.id;
                if (id) {
                    page_url = cmsHatt.route.invoice.store + '/' + id;
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
                                router.push({'name': "invoice_manager", 'param': {'type': this.invoice_type}})
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

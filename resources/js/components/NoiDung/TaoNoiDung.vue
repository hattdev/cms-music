<template>
    <div class="container-fluid">
        <form v-on:submit="saveForm($event)" >
            <div class="card w-100 mb-5">
                <div class="card-header bg-info text-white">
                    <h3 >{{name}}</h3>
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
                                <a class="nav-link" data-toggle="tab" href="#infoContractTab">Thông tin HD</a>
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
                                                <label>Tên</label>
                                                <input type="text" v-model="form.name" class="form-control">
                                            </div>

                                            <div class="form-group col-lg-6" v-if="content_type =='video'">
                                                <label>Tên Kênh</label>
                                                <input type="text" v-model="form.channel_name" class="form-control">
                                            </div>

                                            <div class="form-group col-lg-6" v-if="content_type =='video'">
                                                <label>Chủ đề</label>
                                                <input type="text" v-model="form.topic" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6" v-if="content_type =='video'">
                                                <label>Link video</label>
                                                <input type="text" v-model="form.video_url" class="form-control">
                                                <div class="mt-2"><a :href="form.video_url" target="_blank"><i>{{form.video_url}}</i></a></div>
                                            </div>

                                            <div class="form-group col-lg-6" v-if="content_type!='video'">
                                                <label>Tên Ca sĩ</label>
                                                <input type="text" v-model="form.singer_name" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6" v-if="content_type!='video'">
                                                <label>Tên Tác Giả</label>
                                                <input type="text" v-model="form.author_name" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6" v-if="content_type!='video'">
                                                <label>Tên Tác Giả Lời</label>
                                                <input type="text" v-model="form.author_lyric_name" class="form-control">
                                            </div>


                                            <div class="form-group col-lg-4">
                                                <label>1 Quyền</label>
                                                <input type="text" v-model="form.one_permission" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label>Đủ Quyền</label>
                                                <input type="text" v-model="form.full_permission" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label>Độc quyền</label>
                                                <input type="text" v-model="form.monopoly_permission" class="form-control">
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label>Hạng mục cung cấp</label>
                                                <input type="text" v-model="contract_default.items_provided" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Phạm vi cung cấp</label>
                                                <input type="text" v-model="contract_default.scope_of_supply" class="form-control">
                                            </div>

                                            <div class="form-group col-lg-6">
                                                <label>Ngày ký Nội dung</label>
                                                <hatt-datepicker v-model="form.sign_date"></hatt-datepicker>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Ngày hết hạn Nội dung</label>
                                                <hatt-datepicker v-model="form.exp_date"></hatt-datepicker>
                                            </div>
                                        </div>
                                        <div class="form-group" v-if="content_type!='video'">
                                            <label>Lời bài hát</label>
                                            <hatt-editor v-model="form.lyric_song"></hatt-editor>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>Trạng thái</label>
                                            <div v-html="contract_default.status_html"></div>
                                        </div>
<!--                                        <div class="form-group">-->
<!--                                            <label>Trạng thái</label>-->
<!--                                            <select v-model="form.status" class="form-control">-->
<!--                                                <option disabled value="">Chọn</option>-->
<!--                                                <option v-for="option in options" v-bind:value="option.value">-->
<!--                                                    {{ option.text }}-->
<!--                                                </option>-->
<!--                                            </select>-->
<!--                                        </div>-->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane  fade" id="infoContractTab">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label>Số Hợp Đồng</label>
                                                <hatt-select2 v-model="form.contract_number" :url="contract_url_select2" @updateContract="updateContract"></hatt-select2>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-check mb-2 pt-4">
                                                    <input type="checkbox" v-model="contract_default.auto_renewed" value="1" class="form-check-input" id="autoRenew">
                                                    <label class="form-check-label" for="autoRenew"><b>Tự động gia hạn</b></label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Số Phụ lục Hợp đồng</label>
                                                <input type="text" v-model="form.sub_contract_number" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Số TT phụ lục</label>
                                                <input type="text" v-model="form.sub_contract_order" class="form-control">
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
                                                <label>Trạng thái HĐ</label>
                                                <div v-html="contract_default.status_html"></div>
                                            </div>
                                            <div class="form-group col-lg-6" v-if="contract_default.id">
                                                <router-link target="_blank" :to="{name:'contract_edit',params: { id: contract_default.id }}">Xem hợp đồng</router-link>
                                            </div>
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
                                                <div class="font-weight-bold">Danh sách files</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <hatt-uploads v-model="form.files" :canUpload="false"></hatt-uploads>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <div class="font-weight-bold">Danh sách file nhạc</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <hatt-uploads v-model="form.music_files"></hatt-uploads>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <div class="font-weight-bold">Danh sách file video</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <hatt-uploads v-model="form.video_files"></hatt-uploads>
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
        name: "TaoNoiDung",
        data: function () {
            return {
                name: 'Thêm mới nội dung ',
                form: {},
                form_default: {
                    name: '',
                    channel_name: '',
                    singer_name: '',
                    author_name: '',
                    author_lyric_name: '',
                    one_permission: '',
                    full_permission: '',
                    monopoly_permission: '',
                    sub_contract_number: '',
                    sub_contract_order: '',
                    lyric_song: '',
                    video_url: '',
                    topic: '',
                    status: '',
                    contract_name: '',
                    content_type: '',
                    contract_number: '',
                    partner_name: '',
                    sign_date: '',
                    exp_date: '',
                    auto_renew: '',
                    share_rate: '',
                    advance: '',
                    items_provided: '',
                    scope_of_supply: '',
                    note: '',
                    files: '',
                    music_files: '',
                    video_files: '',
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
                content_type: false,
                contract_url_select2: cmsHatt.route.contract.select2
            }
        },
        watch: {
            $route(to, from) {
                if (to.params.type != from.params.type) {
                    this.form_default.content_type = this.content_type = to.params.type;
                }
            },
            content_type: function (val) {
                this.getPost();
            },
        },
        created() {
            this.form_default.content_type = this.content_type = this.$route.params.type;
        },
        methods: {
            getName() {
                var ContentType = JSON.parse(cmsHatt.content_type);
                this.name = 'Thêm mới nội dung';
                if (typeof ContentType[this.content_type] != 'undefined') {
                    this.name = 'Thêm mới nội dung ' + ContentType[this.content_type];
                    if (this.form.id) {
                        this.name = 'nội dung: ' + this.form.name + ' #' + this.form.id
                    }
                }
            },
            updateContract(val) {
                this.contract_default = val;
                this.form.files = this.contract_default.files;
            },
            getPost() {
                var id = this.$route.params.id;
                if (id) {
                    var page_url = page_url || cmsHatt.route.content.edit + '/' + id;
                    axios({
                        method: 'post',
                        url: page_url,
                        data: {id: id, content_type: this.content_type}
                    }).then((response) => {
                        var res = response.data;
                        if (res.status == 1) {
                            this.form = res.data;
                            this.getName();
                        } else {
                            cmsHattApp.showError({
                                message: res.message,
                                callback: function (result) {
                                    router.push({'name': "content_manager", 'param': {'type': this.content_type}})
                                }
                            });
                        }
                    }).catch((err) => {
                        cmsHattApp.showError({
                            message: err.message,
                            callback: function (result) {
                                router.push({'name': "content_manager", 'param': {'type': this.content_type}})
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
                // if (!this.form.contract_name) {
                //     this.errors['contract_name'] = ['Tên HD không thể bỏ trống'];
                // }
                if (!this.form.contract_number) {
                    this.errors['contract_number'] = ['Số HD không thể bỏ trống'];
                }
                if (!this.form.status) {
                    this.errors['status'] = ['Trạng thái  không thể bỏ trống'];
                }
                if (!this.form.sign_date) {
                    this.errors['sign_date'] = ['Ngày ký HD không thể bỏ trống'];
                }
                if (!this.form.exp_date) {
                    this.errors['exp_date'] = ['Ngày hết hạn HD không thể bỏ trống'];
                }

                if (Object.keys(this.errors).length > 0) {
                    console.log(Object.keys(this.errors).length);
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
                var page_url = page_url || cmsHatt.route.content.store;

                var id = this.$route.params.id;
                if (id) {
                    page_url = cmsHatt.route.content.store + '/' + id;
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
                                router.push({'name': "content_manager", 'param': {'type': this.content_type}})
                            }
                        });
                    } else {
                        cmsHattApp.showError({
                            message: res.message,
                            callback: function (result) {
                                router.push({'name': "content_manager", 'param': {'type': this.content_type}})
                            }
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

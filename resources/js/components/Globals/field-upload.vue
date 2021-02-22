<template>
    <div class="dungdt-upload-multiple ">
        <div class="attach-demo d-flex">
            <div class="image-item" v-for="file in file_info">
                <div class="inner">
                    <a class="download btn btn-sm btn-primary" target="_blank" :href="file.url" :download="file.file_name"><i class="fa fa-download"></i></a>
                    <span class="delete btn btn-sm btn-danger" @click="removeFile(file.id)"><i class="fa fa-trash"></i></span>
                    <span v-html="getFileThumb(file)"></span>
                    <a target="_blank" :href="file.url" class="inner-title">{{file.file_name}}</a>
                </div>
            </div>
        </div>
        <div class="upload-box" v-if="canUpload">
            <input type="hidden" v-model="ids">
            <div class="text-left">
                <span class="btn btn-primary btn-sm btn-field-upload" @click="openUploader"><i class="fa fa-plus-circle"></i> Select Files</span>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                options: [],
                cmsHatt: cmsHatt,
                ids: '',
                file_info: []
            }
        },
        props: {
            isMultiple:{
                type:Boolean,
                default: true
            },
            typeUpload:{
                type:String,
                default: 'file'
            },
            value:{
                type:String
            },
            canUpload:{
                type:Boolean,
                default: true
            }
        },
        methods: {
            openUploader() {
                var me = this;
                uploaderModal.show({
                    multiple: me.isMultiple,
                    file_type: me.typeUpload,
                    onSelect: function (files) {
                        if (typeof files != 'undefined' && files.length) {
                            var dump_ids = [];
                            console.log(me.ids);

                            if (me.ids) {
                                var dump_ids = me.ids.split(',');
                                console.log(me.ids);
                                console.log(dump_ids);

                            }
                            console.log(dump_ids);

                            for (var i = 0; i < files.length; i++) {
                                dump_ids.push(files[i].id);
                            }
                            me.ids = dump_ids.join(',');
                            me.$emit('input', me.ids);
                        }
                    },
                });
            },
            getFilesInfo(ids) {
                if (ids.length > 0) {
                    var me = this;
                    var dump_ids = ids.split(',');
                    if (dump_ids.length > 0) {
                        $.ajax({
                            url: cmsHatt.route.media.getFilesInfo,
                            type: 'POST',
                            data: {
                                ids: dump_ids,
                            },
                            dataType: 'json',
                            success: function (json) {
                                me.file_info = json.data;
                            }
                        });

                    }
                }
            },
            getFileThumb(file) {
                if (file.file_type.substr(0, 5) == 'image') {
                    return '<img src="' + file.thumb_size + '">';
                }
                if (file.file_type.substr(0, 5) == 'video') {
                    return '<img src="/assets/browser/icon/007-video-file.png">';
                }
                if (file.file_type.indexOf('x-zip-compressed') !== -1 || file.file_type.indexOf('/zip') !== -1) {
                    return '<img src="/assets/browser/icon/005-zip-2.png">';
                }
                if (file.file_type.indexOf('/pdf') !== -1) {
                    return '<img src="/assets/browser/icon/002-pdf-file-format-symbol.png">';
                }

                if (file.file_type.indexOf('/msword') !== -1 || file.file_type.indexOf('wordprocessingml') !== -1) {
                    return '<img src="/assets/browser/icon/010-word.png">';
                }
                if (file.file_type.indexOf('spreadsheetml') !== -1 || file.file_type.indexOf('excel') !== -1) {
                    return '<img src="/assets/browser/icon/011-excel-file.png">';
                }
                if (file.file_type.indexOf('presentation') !== -1) {
                    return '<img src="/assets/browser/icon/powerpoint.png">';
                }
                if (file.file_type.indexOf('audio/') !== -1) {
                    return '<img src="/assets/browser/icon/006-audio-file.png">';
                }

                return '<img src="/assets/browser/icon/008-file.png">';

            },
            removeFile(val) {
                var ids = this.ids.split(',');
                ids.splice(ids.indexOf(val), 1);
                this.ids = ids.join(',');
                var files = this.file_info;
                this.$emit('input', this.ids);
                for (var i = 0; i < files.length; i++) {
                    if (files[i].id == val) {
                        this.file_info.splice(i, 1);
                    }
                }
            },
        },
        watch: {
            value(val) {
                if (val) {
                    this.file_info = this.getFilesInfo(val);
                    this.ids = val;
                }
            }
        },
        computed: {
            file_ids: function () {
                return this.value.split(',');
            },
        }
    };
</script>

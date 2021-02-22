

window.bravo_format_money =  function($money) {

    if (!$money) {
        //return bookingCore.free_text;
    }
    //if (typeof bookingCore.booking_currency_precision && bookingCore.booking_currency_precision) {
    //    $money = Math.round($money).toFixed(bookingCore.booking_currency_precision);
    //}

    $money            = bravo_number_format($money/bookingCore.currency_rate, bookingCore.booking_decimals, bookingCore.decimal_separator, bookingCore.thousand_separator);
    var $symbol       = bookingCore.currency_symbol;
    var $money_string = '';

    switch (bookingCore.currency_position) {
        case "right":
            $money_string = $money + $symbol;
            break;
        case "left_space":
            $money_string = $symbol + " " + $money;
            break;

        case "right_space":
            $money_string = $money + " " + $symbol;
            break;
        case "left":
        default:
            $money_string = $symbol + $money;
            break;
    }

    return $money_string;
}

window.bravo_number_format = function (number, decimals, dec_point, thousands_sep) {


    number         = (number + '')
        .replace(/[^0-9+\-Ee.]/g, '');
    var n          = !isFinite(+number) ? 0 : +number,
        prec       = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep        = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec        = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s          = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k)
                .toFixed(prec);
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s              = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
        .split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '')
        .length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1)
            .join('0');
    }
    return s.join(dec);
}

window.bravo_handle_error_response = function(e){
    switch (e.status) {
        case 401:
            // not logged in
            $('#login').modal('show');
            break;
    }
};

// Form validation
var forms = document.getElementsByClassName('needs-validation');
// Loop over them and prevent submission
var validation = Array.prototype.filter.call(forms, function(form) {
    form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);
});

 window.cmsHattApp={
    showSuccess:function (configs){
        var args = {};
        if(typeof configs == 'object')
        {
            args = configs;
        }else{
            args.message = configs;
        }
        if(!args.title){
            args.title = cmsHatt.i18n.success;
        }
        args.centerVertical = true;
        bootbox.alert(args);
    },
    showError:function (configs) {
        var args = {};
        if(typeof configs == 'object')
        {
            args = configs;
        }else{
            args.message = configs;
        }
        if(!args.title){
            args.title = cmsHatt.i18n.warning;
        }
        args.centerVertical = true;
        bootbox.alert(args);
    },
    showAjaxError:function (e) {
        var json = e.responseJSON;
        if(typeof json !='undefined'){
            if(typeof json.errors !='undefined'){
                var html = '';
                _.forEach(json.errors,function (val) {
                    html+=val+'<br>';
                });

                return this.showError(html);
            }
            if(json.message){
                return this.showError(json.message);
            }
        }
        if(e.responseText){
            return this.showError(e.responseText);
        }
    },
    showAjaxMessage:function (json) {
        if(json.message)
        {
            if(json.status){
                this.showSuccess(json);
            }else{
                this.showError(json);
            }
        }
    },
    showConfirm:function (configs) {
        var args = {};
        if(typeof configs == 'object')
        {
            args = configs;
        }
        args.buttons = {
            confirm: {
                label: '<i class="fa fa-check"></i> '+cmsHatt.i18n.confirm,
            },
            cancel: {
                label: '<i class="fa fa-times"></i> '+cmsHatt.i18n.cancel,
            }
        };
        args.centerVertical = true;
        bootbox.confirm(args);
    }
};
(function ($) {

    window.uploaderModal = new Vue({
        el: '#cdn-browser',
        data:{
            files:[],
            file_info:[],
            viewType:'grid',
            total:0,
            totalPage:0,
            fileTypes:[],
            selected:[],
            selectedLists:[],
            showUploader:false,
            apiFinished:false,
            modalEl:false,
            multiple:false,
            isLoading:false,
            filter:{
                page:1
            },
            onSelect:function () {},
            uploadConfigs:{},
        },
        mounted(){
            let me = this;

            this.modalEl = $('#cdn-browser-modal').modal({
                show:false
            }).on('show.bs.modal',function () {
                me.reloadLists();
            });

            this.$nextTick(function () {
                $(this.$refs.files).change(function () {
                    me.upload(this.files)
                })
            })

        },
        watch:{
            uploadConfigs(val){
                this.multiple = val.multiple;
                this.onSelect = val.onSelect;
            }
        },
        methods:{
            show(configs){
                this.files = [];
                this.resetSelected();
                this.uploadConfigs = configs;
                this.modalEl.modal('show');
            },
            hide(){
                this.modalEl.modal('hide');
            },
            changePage(p,e){
                e.preventDefault();
                this.filter.page = p;
                this.reloadLists();
            },
            selectFile(file){
                var index = this.selected.indexOf(file.id);
                if (index > -1) {
                    this.selected.splice(index, 1);
                    this.selectedLists.splice(index,1);
                }else{
                    if(!this.multiple){
                        this.selected = [];
                        this.selectedLists = [];
                    }
                    this.selected.push(file.id);
                    this.selectedLists.push(file);
                }
            },
            removeFiles() {
                var me = this;
                cmsHattApp.showConfirm({
                    message: cmsHatt.i18n.confirm_delete,
                    callback: function(result){
                        if(result){
                            me.isLoading = true;
                            $.ajax({
                                url:cmsHatt.route.media.removeFiles,
                                type:'POST',
                                data:{
                                    file_ids : me.selected
                                },
                                dataType:'json',
                                success:function (data) {
                                    if(data.status === 1){
                                        // cmsHattApp.showSuccess(data);
                                    }
                                    if(data.status === 0){
                                        cmsHattApp.showError(data);
                                    }
                                    me.isLoading = false;
                                    me.reloadLists();
                                },
                                error:function (e) {
                                    me.isLoading = false;
                                    cmsHattApp.showAjaxError(e);
                                    me.resetSelected();
                                }
                            });
                        }
                    }
                })
            },
            sendFiles(){
                if(typeof this.onSelect == 'function'){
                    let f = this.onSelect;
                    f(this.selectedLists)
                }
                this.hide();
            },
            init(){
                var me = this;
                this.reloadLists();
            },
            reloadLists(){
                var me = this;
                $("#cdn-browser .icon-loading").addClass("active");
                me.isLoading = true;
                $.ajax({
                    url:cmsHatt.route.media.getLists,
                    type:'POST',
                    data:{
                        file_type:this.uploadConfigs.file_type,
                        page:this.filter.page,
                        s:this.filter.s
                    },
                    dataType:'json',
                    success:function (json) {
                        me.resetSelected();
                        me.files = json.data;
                        me.total = json.total;
                        me.totalPage = json.totalPage;
                        me.isLoading = false;
                        me.apiFinished = true;
                    }
                });
            },
            upload(files){
                var me = this;
                if(!files.length) return ;
                for(var i = 0; i < files.length ; i++){
                    var d = new FormData();
                    d.append('file',files[i]);
                    d.append('type',this.uploadConfigs.file_type);
                    me.isLoading = true;
                    $.ajax({
                        url:cmsHatt.route.media.uploads,
                        data:d,
                        dataType:'json',
                        type:'post',
                        contentType: false,
                        processData: false,
                        success:function (res) {
                            me.isLoading = false;
                            if(res.status)
                            {
                                me.reloadLists();
                            }
                            if(res.status === 0){
                                cmsHattApp.showError(res);
                            }
                            $(me.$refs.files).val('');
                        },
                        error:function(e){
                            cmsHattApp.showAjaxError(e);
                            me.isLoading = false;
                            $(me.$refs.files).val('');
                        }
                    })
                }
            },
            initUploader(){

            },
            resetSelected(){
                this.selectedLists = [];
                this.selected = [];
                this.total = 0;
                this.totalPage = 0;
                this.apiFinished = false;
            },

        }
    });

    Vue.component('file-item', {
        template:'#file-item-template',
        data: function () {
            return {
                count: 0
            }
        },
        props:['file',"selected","viewType"],
        methods:{
            selectFile(file){
                this.$emit('select-file',file);
            },
            fileClass(file){
                var s = [];
                s.push(file.file_type);

                if(file.file_type.substr(0,5)=='image'){
                    s.push('is-image');
                }else{
                    s.push('not-image');
                }
                return s;
            },
            getFileThumb(file){
                if(file.file_type.substr(0,5)=='image'){
                    return '<img src="'+file.thumb_size+'">';
                }
                if(file.file_type.substr(0,5)=='video'){
                    return '<img src="/assets/browser/icon/007-video-file.png">';
                }
                if(file.file_type.indexOf('x-zip-compressed')!== -1 || file.file_type.indexOf('/zip')!== -1){
                    return '<img src="/assets/browser/icon/005-zip-2.png">';
                }
                if(file.file_type.indexOf('/pdf')!== -1 ){
                    return '<img src="/assets/browser/icon/002-pdf-file-format-symbol.png">';
                }

                if(file.file_type.indexOf('/msword')!== -1 || file.file_type.indexOf('wordprocessingml')!== -1){
                    return '<img src="/assets/browser/icon/010-word.png">';
                }
                if(file.file_type.indexOf('spreadsheetml')!== -1  || file.file_type.indexOf('excel')!== -1){
                    return '<img src="/assets/browser/icon/011-excel-file.png">';
                }
                if(file.file_type.indexOf('presentation')!== -1 ){
                    return '<img src="/assets/browser/icon/powerpoint.png">';
                }
                if(file.file_type.indexOf('audio/')!== -1 ){
                    return '<img src="/assets/browser/icon/006-audio-file.png">';
                }

                return '<img src="/assets/browser/icon/008-file.png">';

            },
        }
    })
})(jQuery);

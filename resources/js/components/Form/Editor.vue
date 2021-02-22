<template>
    <div class="">
        <tinymce-editor :api="apiKey" :id="id" v-model="value" :init="init" toolbar="formatselect | bold italic strikethrough forecolor backcolor | link image media | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent code " @input="$emit('input',value)"></tinymce-editor>
    </div>
</template>

<script>
	import Editor from '@tinymce/tinymce-vue';

	export default {
		// mixins: [ abstractField ],
        props:{value: {
                type: String,
                default: ''
            }},
        data(){
			return {
				id:this.makeid(10),
                apiKey:'2vqqnkm9wjiz9n14njs4tzxt36nm01j93453pocivd5vrm52',
				init:{
					plugins: 'searchreplace autolink fullscreen image link media codesample table charmap hr toc advlist lists wordcount textpattern help code',
                    toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | pagebreak codesample code | removeformat',
					image_advtab: false,
					image_caption: false,
					height:400,
					file_picker_callback: function (callback, value, meta) {
						/* Provide file and text for the link dialog */
						// if (meta.filetype === 'file') {
						// 	uploaderModal.show({
						// 		multiple:false,
						// 		file_type:'video',
						// 		onSelect:function (files) {
						// 			if(files.length)
						// 				callback(bookingCore.url+'/media/preview/'+files[0].id);
						// 		},
						// 	});
						// }
                        //
						// /* Provide image and alt text for the image dialog */
						// if (meta.filetype === 'image') {
						// 	uploaderModal.show({
						// 		multiple:false,
						// 		file_type:'image',
						// 		onSelect:function (files) {
						// 			if(files.length)
						// 				callback(files[0].thumb_size);
						// 		},
						// 	});
						// }
                        //
						// /* Provide alternative source and posted for the media dialog */
						// if (meta.filetype === 'media') {
						// 	uploaderModal.show({
						// 		multiple:false,
						// 		file_type:'video',
						// 		onSelect:function (files) {
						// 			if(files.length)
						// 				callback(bookingCore.url+'/media/preview/'+files[0].id);
						// 		},
						// 	});
						// }
					},
				},
			}
		},
		components:{
			'tinymce-editor': Editor
		},
        // watch:{
        //     content:function () {
        //         this.value = this.content
        //     }
        // },
        methods:{
			makeid:function(length) {
				var result           = '';
				var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
				var charactersLength = characters.length;
				for ( var i = 0; i < length; i++ ) {
					result += characters.charAt(Math.floor(Math.random() * charactersLength));
				}
				return result;
			},
		},
	};
</script>
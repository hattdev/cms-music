var fullHeight = function() {
    $('.js-fullheight').css('height', $(document).height());
    $(document).resize(function(){
        var height  =$(document).height();
        console.log(height);
        $('.js-fullheight').css('height', height);
    });

};
fullHeight();
$('#sidebarCollapse').on('click', function () {
    $('#sidebar').toggleClass('active');
});
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Authorization' : 'Bearer '+$('meta[name="auth-token"]').attr('content'),
        'Accept' : 'application/json',
    }
});
// $(document).on('click','.dungdt-upload-multiple .image-item .delete',function () {
//     var i = $(this).closest('.image-item').index();
//     let p = $(this).closest('.dungdt-upload-multiple');
//     var ids = p.find('input').val().split(',');
//     ids.splice(i,1);
//     p.find('input').val(ids.join(','));
//     $(this).closest('.image-item').remove();
// });

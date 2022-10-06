// $(document).ready(function (){
//     CKEDITOR.replace('editor_area');
// });

$(document).ready(function() {
    $('#editor_area').summernote({
        lang: 'ru-RU',
        toolbar:[
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['view', ['fullscreen']],
            // ['insert', ['picture']],
        ],
        placeholder: 'Вы можете оставить комментарий',
    });
});

$('.delete').click(function (){
    let res = confirm('Подтвердите действие');
    if (!res) return false;
});

$('.sidebar-menu a').each(function(){
    let location = window.location.protocol + '//' + window.location.host + window.location.pathname;
    let link = this.href;
    if(link == location){
        $(this).parent().addClass('active');
        $(this).closest('.treeview').addClass('active');
    }
});

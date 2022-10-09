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

/*make a radio button out of a checkbox*/
$(document).ready(function () {
    let checkbox = document.querySelectorAll('.filters-checkbox');
    checkbox.forEach((item)=>{
        item.addEventListener('change', () =>{
            if (item.checked){
                checkbox.forEach(elem => {
                    if (elem.name == item.name && elem.value != item.value){
                        elem.checked = false;
                    }
                });
            }
        });
    });
});

/* code for select2 */
$(document).ready(function () {
    $(".select2").select2({
        placeholder: " Начните вводить наименование товара",
        //minimumInputLength: 2,
        cache: true,
        ajax: {
            url: adminpath + "/product/related-product",
            delay: 250,
            dataType: 'json',
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                return {
                    results: data.items,
                };
            },
        },
    });
});

//for uploader img
let buttonSingle = $("#single"),
    buttonMulti = $("#multi"),
    file;
new AjaxUpload(buttonSingle, {
    action: adminpath + buttonSingle.data('url') + "?upload=1",
    data: {name: buttonSingle.data('name')},
    name: buttonSingle.data('name'),
    onSubmit: function(file, ext){
        if (! (ext && /^(jpg|png|jpeg|gif)$/i.test(ext))){
            alert('Ошибка! Разрешены только картинки');
            return false;
        }
        buttonSingle.closest('.file-upload').find('.overlay').css({'display':'block'});

    },
    onComplete: function(file, response){
        setTimeout(function(){
            buttonSingle.closest('.file-upload').find('.overlay').css({'display':'none'});

            response = JSON.parse(response);
            $('.' + buttonSingle.data('name')).html('<img src="/images/' + response.file + '" style="max-height: 150px;">');
        }, 1000);
    }
});

new AjaxUpload(buttonMulti, {
    action: adminpath + buttonMulti.data('url') + "?upload=1",
    data: {name: buttonMulti.data('name')},
    name: buttonMulti.data('name'),
    onSubmit: function(file, ext){
        if (! (ext && /^(jpg|png|jpeg|gif)$/i.test(ext))){
            alert('Ошибка! Разрешены только картинки');
            return false;
        }
        buttonMulti.closest('.file-upload').find('.overlay').css({'display':'block'});

    },
    onComplete: function(file, response){
        setTimeout(function(){
            buttonMulti.closest('.file-upload').find('.overlay').css({'display':'none'});

            response = JSON.parse(response);
            $('.' + buttonMulti.data('name')).append('<img src="/images/' + response.file + '" style="max-height: 150px;">');
        }, 1000);
    }
});
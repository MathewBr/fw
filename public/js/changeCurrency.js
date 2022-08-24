$('#currency').change(function (){
    window.location = 'currency/changecurrency?curr=' + $(this).val();
    console.log($(this).val());
});
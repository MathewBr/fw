    /*typeahead start*/
    let products = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            wildcard: '%QUERY',
            url: path + '/search/typeahead?query=%QUERY'
        }
    });

    products.initialize();

    $("#typeahead").typeahead({
        highlight: true
        },{
        name: 'products',
        display: 'title',
        limit: 9,
        source: products
        });

    $('#typeahead').bind('typeahead:select', function (ev, suggestion){
        window.location = path + '/search/?s=' + encodeURIComponent(suggestion.title);
    });
    /*typeahead end*/

    /*Cart start*/
    $('body').on('click', '.add-to-cart-link', function (e){ //delegation
        e.preventDefault();
        let id = $(this).data('id'),
            quantity = $('.quantity input').val() ? $('.quantity input').val() : 1, //this element 'input' can have specified quantity
            modification = $('.available select').val();

        $.ajax({
            url: '/cart/add',
            data: {id:id, qty: quantity, mod: modification},
            type: 'GET',
            success: function (res){
                showCart(res);
            },
            error: function (err){
                alert("Ошибка. Попробуйте позже.");
                console.log(err.responseText);
            },
        });
    });

    $('#cart .modal-body').on('click', '.del-item', function (){
        let id = $(this).data('id');
        $.ajax({
            url: '/cart/delete',
            data: {id: id},
            type: 'GET',
            success: function (res){
                showCart(res);
            },
            error: function (err){
                alert("Error removal product.");
                console.log(err.responseText);
            }
        });
    });

    function showCart(cart){
        if ($.trim(cart) == '<h3>Корзина пуста</h3>') {
            $('#cart .modal-footer a, #cart .modal-footer .btn-danger').css('display', 'none');
        }else{
            $('#cart .modal-footer a, #cart .modal-footer .btn-danger').css('display', 'inline-block');
        }
        $('#cart .modal-body').html(cart);
        $('#cart').modal(); //show modal window

        //update of inscriptions
        if ($('.cart-sum').text()){
            $('.simpleCart_total').html($('#cart .cart-sum').text());
        }else{
            $('.simpleCart_total').text('Empty Cart');
        }
    }

    function lookInCart(){
        $.ajax({
            url: '/cart/show',
            type: 'GET',
            success: function (res){
                showCart(res);
            },
            error: function (err){
                alert("Ошибка. Попробуйте позже.");
                console.log(err.responseText);
            },
        });
    }

    function clearCart(){
        $.ajax({
            url: '/cart/clear',
            type: 'GET',
            success: function (res){
                showCart(res);
            },
            error: function (err){
                alert("Ошибка. Попробуйте позже.");
                console.log(err.responseText);
            },
        });
    }
    /*Cart end*/

    /*modifications of products start*/
    let elemPrice = document.getElementById('base-price'),
        elemOldPrice = document.getElementById('old-base-price'),
        basePrice = (elemPrice !== null && elemPrice !== undefined) ? elemPrice.innerHTML : '',
        baseOldPrice = (elemOldPrice !== null && elemOldPrice !== undefined) ? elemOldPrice.innerHTML : '';

    let tempElem = document.createElement('small');
    tempElem.id = "tempElemOldPrice";
    tempElem.className = "color-red";

    $('.available select').on('change', function (){
        let modId = $(this).val(),
            color = $(this).find('option').filter(':selected').data('title'),
            price = $(this).find('option').filter(':selected').data('price'),
            oldPrice = $(this).find('option').filter(':selected').data('oldprice');

        if (document.getElementById('tempElemOldPrice')) tempElem.remove();

        if (price){
            if (elemPrice) elemPrice.innerHTML = symbolLeft + price + symbolRight;
            if (oldPrice && Number(oldPrice) > Number(price)){
                if (elemOldPrice){
                    elemOldPrice.innerHTML = '<del>' + symbolLeft + oldPrice + symbolRight + '</del>';
                } else {
                    tempElem.innerHTML = '<del>' + symbolLeft + oldPrice + symbolRight + '</del>';
                    if (elemPrice) elemPrice.after(tempElem);
                }
            } else {
                if (elemOldPrice) elemOldPrice.innerHTML = '';
            }
        } else {
            if (elemPrice) elemPrice.innerHTML = basePrice;
            if (elemOldPrice) elemOldPrice.innerHTML = baseOldPrice;
        }
    });
    /*modifications of products end*/

    /*filters start*/
    $('body').on('change', '.w_sidebar input', function () {
        let checked = $('.w_sidebar input:checked'),
            data = '';
        checked.each(function (){
            data += this.value + ',';
        });
        if (data){
            $.ajax({
                url: location.href,
                data: {filter: data},
                type: 'GET',
                beforeSend: function (){
                    $('.preloader').fadeIn(300, function (){
                        $('.product-one').hide();
                    });
                },
                success: function (res){
                    $('.preloader').delay(500).fadeOut('slow', function (){
                        $('.product-one').html(res).fadeIn();

                        let url = location.search.replace(/filter(.+?)(&|$)/g, '');
                        let newUrl = location.pathname + url + (location.search ? "&" : "?") + "filter=" + data;
                        newUrl = newUrl.replace('&&', '&');
                        newUrl = newUrl.replace('?&', '?');
                        history.pushState({}, '', newUrl);
                    });
                },
                error: function (err){
                    console.log(err);
                }
            });
        }else{
            window.location = location.pathname;
        }
    });
    /*filters end*/



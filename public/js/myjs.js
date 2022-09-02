$(document).ready(function (){
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
});



<!--start-breadcrumbs-->
<div class="breadcrumbs">
    <div class="container">
        <div class="breadcrumbs-main">
            <ol class="breadcrumb">
                <?=$breadcrumbs?>
            </ol>
        </div>
    </div>
</div>
<!--end-breadcrumbs-->

<!--prdt-starts-->
<div class="prdt">
    <div class="container">
        <div class="prdt-top">
            <div class="col-md-9 prdt-left">

                <?php if (!empty($products)) : ?>
                    <?php $curr = \fw\App::$appContainer->getParameter('currency'); ?>

                    <div class="product-one">
                        <?php foreach ($products as $product) : ?>
                            <div class="col-md-4 product-left p-left">
                                <div class="product-main simpleCart_shelfItem">
                                    <a href="product/<?=$product->alias?>" class="mask"><img onerror="this.src = 'images/no_img.png';" class="img-responsive zoom-img" src="images/<?=$product->img?>" alt="" /></a>
                                    <div class="product-bottom">
                                        <h3><a href="product/<?=$product->alias?>"><?=$product->title?></a></h3>
                                        <h4><a class="add-to-cart-link" href="card/add?id=<?=$product->id?>" data-id="<?=$product->id?>"><i></i></a> <span class=" item_price"><?=$curr['symbol_left'];?><?=$product->price * $curr['value']?><?=$curr['symbol_right'];?></span>
                                            <?php if ($product->old_price && $product->old_price > $product->price) : ?>
                                                <small class="color-red"><del><?=$curr['symbol_left'];?><?=$product->old_price * $curr['value']?><?=$curr['symbol_right'];?></del></small>
                                            <?php endif; ?>
                                        </h4>
                                    </div>

                                    <?php if ($product->old_price && $product->old_price > $product->price) : ?>
                                        <div class="srch">
                                            <span><?=round(($product->price - $product->old_price)*100/$product->old_price) . "%"?></span>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="clearfix"></div>

                        <div class="text-center">
                            <p>(<?=count($products)?> из <?=$total?>)</p>
                            <?php if ($pagination->countPages > 1) : ?>
                                <?=$pagination?>
                            <?php endif; ?>
                        </div>

                    </div>
                <?php else: ?>
                    <h2>В этой категории товаров нет.</h2>
                <?php endif; ?>

            </div>
            <div class="col-md-3 prdt-right">
                <div class="w_sidebar">

                    <?php new \app\widgets\filters\Filter(); ?>

                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--product-end-->

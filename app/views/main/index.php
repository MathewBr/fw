<!--banner-starts-->
<div class="bnr" id="home">
    <div  id="top" class="callbacks_container">
        <ul class="rslides" id="slider4">
            <li>
                <img src="images/bnr-1.jpg" alt=""/>
            </li>
            <li>
                <img src="images/bnr-2.jpg" alt=""/>
            </li>
            <li>
                <img src="images/bnr-3.jpg" alt=""/>
            </li>
        </ul>
    </div>
    <div class="clearfix"> </div>
</div>
<!--banner-ends-->
<!--Slider-Starts-Here-->

<!--End-slider-script-->
<!--about-starts-->
<?php if (isset($brands)) : ?>
<div class="about">
    <div class="container">
        <div class="about-top grid-1">
            <?php foreach ($brands as $brand) : ?>
            <div class="col-md-4 about-left">
                <figure class="effect-bubba">
                    <img class="img-responsive" src="images/<?=$brand->img?>" alt=""/>
                    <figcaption>
                        <h2><?=$brand->title?></h2>
                        <p><?=$brand->description?></p>
                    </figcaption>
                </figure>
            </div>
            <?php endforeach; ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<?php endif; ?>
<!--about-end-->
<!--product-starts-->
<?php if (isset($hits)) : ?>
    <div class="product">
        <div class="container">
            <div class="product-top">
                <?php //debug($hits); ?>
                <?php $count = 1; ?>
                <?php foreach ($hits as $hit) : ?>
                    <?php if (($count % 4) == 1) : ?>
                        <div class="product-one">
                    <?php endif; ?>
                        <div class="col-md-3 product-left">
                        <div class="product-main simpleCart_shelfItem">
                            <a href="product/<?=$hit->alias?>" class="mask"><img onerror="this.src = 'images/no_img.png';" class="img-responsive zoom-img" src="images/<?=$hit->img?>" alt="" /></a>
                            <div class="product-bottom">
                                <h3><a href="product/<?=$hit->alias?>"><?=$hit->title?></a></h3>
                                <p><?=$hit->description?></p>
                                <h4><a class="add-to-cart-link" href="card/add?id=<?=$hit->id?>"><i></i></a> <span class=" item_price">$ <?=$hit->price?></span>
                                    <?php if ($hit->old_price && $hit->old_price > $hit->price) : ?>
                                        <small class="color-red"><del><?=$hit->old_price?></del></small>
                                    <?php endif; ?>
                                </h4>
                            </div>

                            <?php if ($hit->old_price && $hit->old_price > $hit->price) : ?>
                                <div class="srch">
                                    <span><?=round(($hit->price - $hit->old_price)*100/$hit->old_price) . "%"?></span>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                    <?php if (($count % 4) == 0 || $count == count($hits)) : ?>
                        <div class="clearfix"></div>
                        </div>
                    <?php endif; ?>
                    <?php $count++; ?>
                <?php endforeach;?>
            </div>
        </div>
    </div>
<?php endif; ?>
<!--product-end-->
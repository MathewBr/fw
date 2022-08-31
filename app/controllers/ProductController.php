<?php

namespace app\controllers;

class ProductController extends AppFeature {

    public function viewAction(){
        $alias = $this->route['alias'];
        $product = \R::findOne('product', "alias = ? AND status = '1'", [$alias]);
        if (!$product){
            throw new \Exception('Страница не найдена', 404);
        }

        //related product
        $related = \R::getAll("SELECT * FROM related_product JOIN product ON product.id = related_product.related_id WHERE related_product.product_id = ?", [$product->id]);

        $this->setMeta($product->title, $product->description, $product->keywords);
        $this->passData(compact('product', 'related'));

    }

    //хлебные крошки
    //связанные товары

    //в куки запрошенные товары
    //из куков просмотренные товары
    //галерея
    //модификации товара

}
<?php

namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\ProductModel;

class ProductController extends AppFeature {

    public function viewAction(){
        $alias = $this->route['alias'];
        $product = \R::findOne('product', "alias = ? AND status = '1'", [$alias]);
        if (!$product){
            throw new \Exception('Страница не найдена', 404);
        }

        //related product
        $related = \R::getAll("SELECT * FROM related_product JOIN product ON product.id = related_product.related_id WHERE related_product.product_id = ?", [$product->id]);

        //gallery
        $gallery = \R::findAll('gallery', 'product_id = ?', [$product->id]);

        //recording and reading in cookies of the viewed product
        $prod_model = new ProductModel();
        $prod_model->setRecentlyViewed($product->id);
        $r_viewed = $prod_model->getRecentlyViewed();
        $recently_viewed = null;
        if ($r_viewed){
            $recently_viewed = \R::find('product', 'id IN (' . \R::genSlots($r_viewed) . ') LIMIT 3', $r_viewed);
        }

        //breadcrumbs
        $breadcrumbs = Breadcrumbs::getBreadcrumbs($product->category_id, $product->title);

        //modification
        $modifications = \R::findAll('modification', 'product_id = ?', [$product->id]);
//        debug($modifications);

        $this->setMeta($product->title, $product->description, $product->keywords);
        $this->passData(compact('product', 'related', 'gallery', 'recently_viewed', 'breadcrumbs', 'modifications'));

    }

}
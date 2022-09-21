<?php

namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\Category;
use app\widgets\filters\Filter;
use fw\App;
use fw\libs\Pagination;

class CategoryController extends AppFeature{

    public function viewAction(){
        $alias = $this->route['alias'];
        $category = \R::findOne('category', 'alias = ?', [$alias]);
        if(!$category){
            throw new \Exception('Страница не найдена', 404);
        }

        $breadcrumbs = Breadcrumbs::getBreadcrumbs($category->id);

        $nestedCategory = new Category();
        $nestedId = $nestedCategory->getNestedCategories($category->id);
        $nestedId = !$nestedId ? $category->id : $nestedId . $category->id;

        $perPage = App::$appContainer->getParameter('pagination');
        $numberPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        //applying filters start
        $sql_filter = '';
        if (!empty($_GET['filter'])){
    /*
     SELECT `product`.*  FROM `product`  WHERE category_id IN (6) AND id IN
    (
    SELECT product_id FROM attribute_product WHERE attr_id IN (1,5) GROUP BY product_id HAVING COUNT(product_id) = 2
    )
    */
            $filter = Filter::getFilter();
            if ($filter){
                $count_group = Filter::getCountGroups($filter);
//              $sql_filter = "AND id IN (SELECT product_id FROM attribute_product WHERE attr_id IN ($filter))";// first variant query
                $sql_filter = "AND id IN (SELECT product_id FROM attribute_product WHERE attr_id IN ($filter) GROUP BY product_id HAVING COUNT(product_id) = $count_group)";
            }
        }

        //applying filters end
        $total = \R::count('product', "category_id IN ($nestedId) $sql_filter");
        $pagination = new Pagination($numberPage, $perPage, $total);
        $startPosition = $pagination->startPosition();

        $products = \R::find('product', "category_id IN ($nestedId) $sql_filter LIMIT $startPosition, $perPage");

        if ($this->isAjax()){
           $this->sendAjaxResponse('filter', compact('products', 'total', 'pagination'));
        }

        $this->setMeta($category->title, $category->description, $category->keywords);
        $this->passData(compact('products', 'breadcrumbs', 'pagination', 'total'));

    }

}
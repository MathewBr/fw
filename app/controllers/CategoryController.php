<?php

namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\Category;
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
        $total = \R::count('product', "category_id IN ($nestedId)");
        $pagination = new Pagination($numberPage, $perPage, $total);
        $startPosition = $pagination->startPosition();

        $products = \R::find('product', "category_id IN ($nestedId) LIMIT $startPosition, $perPage");

        $this->setMeta($category->title, $category->description, $category->keywords);
        $this->passData(compact('products', 'breadcrumbs', 'pagination', 'total'));

    }

}
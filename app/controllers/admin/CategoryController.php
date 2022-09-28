<?php

namespace app\controllers\admin;

class CategoryController extends AppFeature {

    public function indexAction(){
        $this->setMeta('Список категорий');
    }

    public function deleteAction(){
        $id = $this->getRequestID();
        $children = \R::count('category', 'parent_id = ?', [$id]);
        $errors = '';
        if ($children){
            $errors .= '<li>Удаление невозможно, есть вложенные категории</li>';
        }
        $products = \R::count('product', 'category_id = ?', [$id]);
        if ($products){
            $errors .= '<li>Удаление невозможно, в категории есть товары</li>';
        }
        if ($errors){
            $_SESSION['errors'] = "<ul>$errors</ul>";
            redirect();
        }
        $category = \R::load('category', $id);
        \R::trash($category);
        $_SESSION['success'] = 'Категория удалена';
        redirect();
    }

}
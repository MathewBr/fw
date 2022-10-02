<?php

namespace app\controllers\admin;

use app\models\AppModel;
use app\models\Category;
use fw\App;
use fw\Router;

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

    public function addAction(){
        if (!empty($_POST)){
            $category = new Category();
            $formData = $_POST;
            $category->selectiveLoading($formData);
            if (!$category->validate($formData)){
                $category->showValidageErors();
                redirect();
            }
            if ($id = $category->saveInDbase('category')){
                $alias = AppModel::createAlias('category', 'alias', $formData['title'], $id);
                $cat = \R::load('category', $id);
                $cat->alias = $alias;
                \R::store($cat);
                $_SESSION['success'] = 'Категория добавлена';
            }
            redirect();
        }
        $this->setMeta('Новая категория');
    }

    public function editAction(){
        if (!empty($_POST)){
            $id = $this->getRequestID(false);
            $category = new Category();
            $formData = $_POST;
            $category->selectiveLoading($formData);
            if (!$category->validate($formData)) {
                $category->showValidageErors();
                redirect();
            }
            if ($category->update('category', $id)){
                $alias = AppModel::createAlias('category', 'alias', $formData['title'], $id);
                $category = \R::load('category', $id);
                $category->alias = $alias;
                \R::store($category);
                $_SESSION['success'] = 'Категория изменена';
            }
            redirect();
        }
        $id = $this->getRequestID();
        $category = \R::load('category', $id);
        App::$appContainer->writeParameters('parent_id', $category->parent_id);
        $this->setMeta("Редактирование категории ($category->title)");
        $this->passData(compact('category'));
    }

}
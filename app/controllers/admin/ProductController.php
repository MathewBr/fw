<?php

namespace app\controllers\admin;

use app\models\admin\Product;
use app\models\AppModel;
use fw\App;
use fw\libs\Pagination;

class ProductController extends AppFeature{

    public function indexAction(){
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perpage = 10;
        $count = \R::count('product');
        $pagination = new Pagination($page, $perpage, $count);
        $start = $pagination->startPosition();
        $products = \R::getAll("SELECT product.*, category.title AS cat FROM product JOIN category ON category.id = product.category_id ORDER BY product.title LIMIT $start, $perpage");
        $this->setMeta('Список товаров');
        $this->passData(compact('products', 'pagination', 'count'));
    }

    public function addAction(){
        if(!empty($_POST)){
            $product = new Product();
            $form_data = $_POST;
            $product->selectiveLoading($form_data);
            $img = $_FILES['img'];

            $product->attributes['status'] = $product->attributes['status'] ? '1' : '0';
            $product->attributes['hit'] = $product->attributes['hit'] ? '1' : '0';
            $product->getImg();

            if(!$product->validate($form_data)){
                $product->showValidageErors();
                $_SESSION['form_data'] = $form_data;
                redirect();
            }

            if($id = $product->saveInDbase('product')){
                $product->saveGallery($id);
                $alias = AppModel::createAlias('product', 'alias', $form_data['title'], $id);
                $mime = AppModel::getMime($img['type']);
//                $img_name = $alias . $mime;
                $againProduct = \R::load('product', $id);
                $againProduct->alias = $alias;

//                if ($mime && !$img['error'] && move_uploaded_file($_FILES['img']['tmp_name'], IMG . '/' . $img_name)){
//                    $againProduct->img = $img_name;
//                }else{
//                    $_SESSION['errors'] = 'Не удалось загрузить изображение.';
//                }

                $product->editFilter($id, $form_data);
                $product->editRelatedProduct($id, $form_data);

                \R::store($againProduct);
                $_SESSION['success'] = 'Товар добавлен';
            }
            redirect();
        }

        $this->setMeta('Новый товар');
    }

    public function relatedProductAction(){
        //required format of returned data
        /*$data = [
            'items' => [
                'id' => 1,
                'text' => 'Товар 1',
            ],
            [
              'id' => 2,
              'text' => 'Товар 2',
            ],
        ];*/

        $q = isset($_GET['q']) ? $_GET['q'] : '';
        $data['items'] = [];
        $products = \R::getAssoc('SELECT id, title FROM product WHERE title LIKE ? LIMIT 10', ["%{$q}%"]);
        if($products){
            $i = 0;
            foreach($products as $id => $title){
                $data['items'][$i]['id'] = $id;
                $data['items'][$i]['text'] = $title;
                $i++;
            }
        }
        echo json_encode($data);
        die();
    }

    public function addImageAction(){
        if ($_GET['upload']){
            if ($_POST['name'] == 'single'){
                $wmax = App::$appContainer->getParameter('img_width');
                $hmax = App::$appContainer->getParameter('img_height');
            }else{
                $wmax = App::$appContainer->getParameter('gallery_width');
                $hmax = App::$appContainer->getParameter('gallery_height');
            }
            $name = $_POST['name'];
            $product = new Product();
            $product->uploadImg($name, $wmax, $hmax);
        }
    }

}
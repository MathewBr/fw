<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Список категорий
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=ADMIN;?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li class="active"><i class="fa fa-list"></i> Список Категорий</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                   <?php new \app\widgets\menu\Menu([
                       'tpl' => APP . '/widgets/admin/category_admin.php',
                       'container' =>  'div',
                       'cache' => 0,
                       'cacheKey' => 'admin_category',
                       'class' => 'list-group list-group-root well',
                   ])?>
                </div>
            </div>
        </div>
    </div>
</section>
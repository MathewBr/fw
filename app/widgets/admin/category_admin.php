<?php
$parent = isset($item['child']);
if (!$parent){
    $delete = '<a href="' . ADMIN . '/category/delete?id=' . $id . '" class="delete"><i class="fa fa-fw fa-close text-danger"></i></a>';
}else{
    $delete = '<i class="fa fa-fw fa-close"></i>';
}
?>

<p class="item-p">
    <a class="list-group-item" href="<?=ADMIN;?>/category/edit?id=<?=$id?>"><?=$item['title'];?></a><span><?=$delete?></span>
</p>

<?php if($parent) : ?>
    <div class="list-group">
        <?= $this->getMenuHtml($item['child']); ?>
    </div>
<?php endif; ?>

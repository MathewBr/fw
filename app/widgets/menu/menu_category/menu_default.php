<li>
    <a href="?id=<?=$id;?>"><?=$item['title'];?></a>
    <?php if (isset($item['child'])) : ?>
        <ul>
            <?=$this->getMenuHtml($item['child']);?> <!--recursively called for each nested element-->
        </ul>
    <?php endif; ?>
</li>

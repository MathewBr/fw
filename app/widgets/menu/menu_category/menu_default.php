<li>
    <a href="?id=<?=$id;?>"><?=$item['title'];?></a>
    <?php if (isset($item['child'])) : ?>
        <ul>
            <!--recursively called for each nested structure-->
            <?= $this->getMenuHtml($item['child']); ?>
        </ul>
    <?php endif; ?>
</li>

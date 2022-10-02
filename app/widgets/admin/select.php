<?php $parent_id = \fw\App::$appContainer->getParameter('parent_id'); ?>
<option value="<?=$id;?>"<?php if ($id == $parent_id) echo ' selected'; ?><?php if (isset($_GET['id']) && $id == $_GET['id']) echo ' disabled';?>><?=$tab . $item['title'];?></option>
<?php if(isset($item['child'])): ?>
    <?= $this->getMenuHtml($item['child'], '&nbsp;'. '&nbsp;' . $tab. '-' . '&nbsp;') ?>
<?php endif; ?>

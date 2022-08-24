<option value="" class="label"><?=$this->currency['title'];?></option>
<?php foreach ($this->currencies as $k => $v) : ?>
    <?php if ($k != $this->currency['code']) : ?>
        <option value="<?=$k?>"><?=$v['title'];?></option>
    <?php endif; ?>
<?php endforeach; ?>
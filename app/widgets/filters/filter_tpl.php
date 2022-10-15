<?php foreach ($this->groups as $group_id => $group_item) : ?>

<section  class="sky-form">
    <h4><?=$group_item?></h4>
    <div class="row1 scroll-pane">
        <div class="col col-4">
            <?php if (isset($this->attrs[$group_id])) : ?>
            <?php foreach ($this->attrs[$group_id] as $attr_id => $attr_value) : ?>

                <?php //to remember the choice
                    if (!empty($filter) && in_array($attr_id, $filter)){
                        $checked = ' checked';
                    }else{
                        $checked = null;
                    }
                ?>

                <label class="checkbox">
                    <input type="checkbox" name="checkbox" value="<?=$attr_id?>"<?=$checked?>><i></i><?=$attr_value?>
                </label>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php endforeach; ?>
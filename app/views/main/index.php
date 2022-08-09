<h1>Привет из вида index.php</h1>

<?php if (isset($posts)) : ?>
    <?php foreach ($posts as $post) : ?>
        <h3><?=$post->title?></h3>
    <?php endforeach; ?>
<?php endif;?>

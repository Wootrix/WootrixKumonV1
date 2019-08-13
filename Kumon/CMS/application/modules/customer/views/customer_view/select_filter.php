<?php if( $show_default_value === true ): ?>
    <option value="">Todos</option>
<?php endif; ?>

<?php foreach( $result as $r ): ?>
    <?php $text = strlen($r["title"]) > 50 ? substr($r["title"], 0, 50) . "..." : $r["title"]; ?>
    <option value="<?php echo $r["id"]; ?>"><?php echo $text; ?></option>
<?php endforeach; ?>



<div id="magazine_article_<?php echo $counterMagazine; ?>_<?php echo $counterMagazineArticle; ?>">

    <a href="javascript:;" onclick="removeArticle(<?php echo $counterMagazine; ?>, <?php echo $counterMagazineArticle; ?>);">- Remover</a>

    <select name="magazine_article[<?php echo $counterMagazine; ?>][]">
        <?php foreach( $articles as $article ): ?>
            <option value="<?php echo $article["id"]; ?>" <?php echo $article["checked"] ? "selected" : ""; ?>><?php echo $article["title"]; ?></option>
        <?php endforeach; ?>
    </select>

    <br /><br />

</div>
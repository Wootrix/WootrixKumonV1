<div id="magazine_<?php echo $counter; ?>">

    <a href="javascript:;" onclick="removeMagazine(<?php echo $counter; ?>);">- Remover</a>

    <select name="magazine[]" id="magazine_<?php echo $counter; ?>" onchange="addMagazineArticleContent(<?php echo $counter; ?>, true);"
        style="width: 600px;">
        <?php foreach( $magazines as $magazine ): ?>
            <option value="<?php echo $magazine["id"]; ?>" <?php echo $magazine["checked"] ? "selected" : ""; ?>><?php echo $magazine["title"]; ?></option>
        <?php endforeach; ?>
    </select>

    <a href="javascript:;" onclick="addMagazineArticleContent(<?php echo $counter; ?>, false);">+ Artigo</a>

    <br /><br />

    <div id="magazine_article_content_<?php echo $counter; ?>">

        <?php $i = 0;; ?>

        <?php if( isset( $campaignContent ) && count( $campaignContent ) > 0 ): ?>

            <?php

            $customerId = $this->session->userdata('user_id');

            $this->load->model("web/magazine_model");
            $obj = new magazine_model();

            $obj->set_id($idMagazine);
            $obj->set_customer_id($customerId);

            $dataMagazineArticles = $obj->getArticlesByMagazineFilter();

            ?>

            <?php foreach( $campaignContent as $content ): ?>

                <?php

                if( $content["id_magazine"] == $idMagazine ){

                    $dataArticle["counterMagazine"] = $counter;
                    $dataArticle["counterMagazineArticle"] = $i;

                    foreach( $dataMagazineArticles as &$article ){

                        if( $content["id_article"] == $article["id"] ){
                            $article["checked"] = true;
                        } else {
                            $article["checked"] = false;
                        }

                    }

                    $dataArticle["articles"] = $dataMagazineArticles;

                    $this->load->view('select_magazine_article_content', $dataArticle);

                    $i++;

                }

                ?>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

    <input type="hidden" id="magazineArticleCounter_<?php echo $counter; ?>" value="<?php echo $i; ?>" />

</div>
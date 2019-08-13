<script>
    function getSearchKeyword(data, seg) {
        var segValue = $("#segValue").val();
        var magValue = $("#magazineId").val();
        //alert(magValue);
        $("#searchRes").show();
        $(document).ready(function () {
            $.ajax(
                    {
                        url: "<?php echo $this->config->base_url(); ?>index.php/wootrix-search-magzine",
                        type: "POST",
                        data: {keyValue: data, keySeg: segValue, keyMagId: magValue},
                        success: function (data)
                        {
                            //alert(data);
                            $("#searchRes").html(data);
                            //$("#getValue").val(data);
                        }


                    });
        });
    }
    function getResult(title, ID, magID) {


        var getValues = $("#getValue").val(title);


        $("#searchRes").hide();
        window.location.href = "<?php echo $this->config->base_url(); ?>index.php/wootrix-article-list-layout?articleId=" + ID + "&searchId=search&magazineId=" + magID;
    }
    
    <?php
    if($_GET['comment']!=''){
    ?>
    $(document).ready(function(){
    $('html,body').animate({
        scrollTop: $("#commentDiv:last-child").offset().top},
        'slow');
        });
        <?php } ?>
</script>  


</head>
<body>


    <div class="container clearfix">

        <div class="span8">
            <article class="detail-article">
                <figure>
                    <img src="<?php echo $this->config->base_url() . "assets/Article/" . $articleDetail['image']; ?>" alt=""/>
                    <figcaption><?php echo $articleDetail['title']; ?></figcaption>
                </figure>

                <section>
                    <?php
                    if ($articleDetail['created_by'] == '0') {
                        $postedBy = "Open Article";
                    } elseif ($articleDetail['created_by'] == '1') {
                        $postedBy = "Admin";
                    } elseif ($articleDetail['created_by'] == '2') {
                        $postedBy = "Customer";
                    }
                    ?>

                    <h4><?php echo $this->lang->line("posted_by_web"); ?> <?php echo $postedBy; ?> <?php echo $this->lang->line("on_web"); ?> <?php echo date("M d, Y", strtotime($articleDetail['publish_date'])); ?> | <?php echo $comment['commentCount']; ?> <?php echo $this->lang->line("comments_web"); ?></h4>

                    <div class="article-details">
                        <span class="article-date"><?php echo date("M. d, Y", strtotime($articleDetail['publish_date'])); ?></span>
                        <a class="article-website" href="#"><?php echo $articleDetail['website_url']; ?></a>
                    </div>

<!--                        <img class="left" src="<?php echo $this->config->base_url(); ?>images/website_images/image-1.jpg" alt="" />-->
                    <?php echo $articleDetail['description']; ?>



                    <div class="tags clearfix">
                        <strong><?php echo $this->lang->line("Tags"); ?> : </strong>
                        <ol>
                            <?php foreach ($articleTags as $a) { ?>
                                <li><?php echo $a; ?></li>
                            <?php } ?>

                        </ol>
                    </div>
                    <script type="text/javascript">
                        window.twttr = (function (d, s, id) {
                            var t, js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id)) {
                                return
                            }
                            js = d.createElement(s);
                            js.id = id;
                            js.src = "https://platform.twitter.com/widgets.js";
                            fjs.parentNode.insertBefore(js, fjs);
                            return window.twttr || (t = {_e: [], ready: function (f) {
                                    t._e.push(f)
                                }})
                        }(document, "script", "twitter-wjs"));
                    </script>
                    <div id="fb-root"></div>
                    <script>(function (d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id))
                                return;
                            js = d.createElement(s);
                            js.id = id;
                            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
                            fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));</script>



                    <div class="comment-section">
                        <?php 
                        if($articleDetail['allow_share']=='1'){                        
                        ?>
                        <h5><?php echo $this->lang->line("share_on_web"); ?></h5>

                        <div class="social-share">
                            <div class="fb-share-button" data-href="<?php echo $articleDetail['website_url']; ?>" data-layout="button_count"></div>
                        
                        <!--                            <a class="twitter-share-button"
                          href="https://twitter.com/share">
                        Tweet
                        </a>-->
                        <!-- Place this tag in your head or just before your close body tag. -->
                        <script src="https://apis.google.com/js/platform.js" async defer></script>

                        <!-- Place this tag where you want the share button to render. -->
                        <div class="g-plus" data-action="share" data-href="<?php echo $articleDetail['website_url']; ?>"></div>
                        <script src="//platform.linkedin.com/in.js" type="text/javascript">
                        lang: en_US
                        </script>
                        <script type="IN/Share" data-url="<?php echo $articleDetail['website_url']; ?>" data-counter="right"></script>
                        </div>
                        <?php }?><div><?php foreach ($allComments as $comm) { ?>
                            <div class="people-comment" id="commentDiv">
                                <div class="person-detail">
                                    <figure>

                                    </figure>
                                    <strong><?php echo $comm['name']; ?></strong>
                                    <small><?php echo date("M d Y", strtotime($comm['created_date'])); ?> <?php echo $this->lang->line("at_web"); ?> <?php echo date("h:ia", strtotime($comm['created_date'])); ?></small>
                                </div>

                                <p><?php echo $comm['comment']; ?></p>

                            </div>
                        <?php } ?>
                        </div>
                        <?php 
                        if($articleDetail['allow_comment']=='1'){                        
                        ?>
                        <h5><?php echo $this->lang->line("leave_us_reply_web"); ?></h5>
                        
                        <div class="comment-box">
                            <form name="commentPost" id="commentPost" action="<?php echo $this->config->base_url(); ?>index.php/wootrix-mag-article-detail" method="POST">
                                <label><?php echo $this->lang->line("email_address_publish_web"); ?></label>
                                <textarea rows="6" placeholder="<?php echo $this->lang->line("comment_web"); ?>....." name="commentValue"></textarea>
                                <input type="hidden" name="articleIdValue" value="<?php echo $magArtId; ?>" />
                                <input type="hidden" name="articleHidden" value="valueArticle" />
                                <input type="hidden" name="magId" value="<?php echo $magId; ?>" />
                                <input type="submit" value="<?php echo $this->lang->line("post_comment_web"); ?>">
                            </form>

                              <script>
                        // just for the demos, avoids form submit
                        jQuery.validator.setDefaults({
                            debug: false,
                            //success: "valid"
                        });
                        $("#commentPost").validate({
                            rules: {
                                commentValue: {
                                    required: true
                                }
                        }
                                
    });
                    </script>


                        </div>
                        <?php } ?>


                    </div>

                </section>


            </article>
        </div>

        <div class="span4 right">
            <aside class="related-articles">
                <h4><?php echo $this->lang->line("related_articles_web"); ?></h4>
                <?php foreach ($relatedArticle as $article) { ?>
                    <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-mag-article-detail?magArtId=<?php echo $article['id']; ?>&magazineId=<?php echo $magId; ?>">
                        <div class="related-box">
                            <figure><img src="<?php echo $this->config->base_url(); ?>images/website_images/image-2.jpg" alt=""/>
                                <div class="article-topic"><?php echo $article['topicName']; ?></div>
                            </figure>
                            <h5><?php echo $article['title']; ?></h5>

                            <p><?php echo substr($article['description_without_html'], "0", "200"); ?>...</p>


                            <div class="article-details margin-left">
                                <span class="article-date"><?php echo date("M. d, Y", strtotime($article['publish_date'])); ?></span>
                                <a class="article-website" href="#"><?php echo $article['website_url']; ?></a>
                            </div>
                        </div>
                    </a>

                <?php } ?>





            </aside>

        </div>


    </div>






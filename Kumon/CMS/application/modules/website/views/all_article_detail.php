<script>

    function getSearchKeyword(data, seg) {
        var segValue = $("#segValue").val();
        $("#searchRes").show();
        $(document).ready(function () {
            $.ajax(
                    {
                        url: "<?php echo $this->config->base_url(); ?>index.php/wootrix-search-magzine",
                        type: "POST",
                        data: {keyValue: data, keySeg: segValue},
                        success: function (data)
                        {
                            $("#searchRes").html(data);
                        }
                    });
        });
    }
    function getResult(title, ID) {
        $("#getValue").val(title);
        $("#searchRes").hide();
        window.location.href = "<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=" + ID + "&searchId=search";
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
                    <?php

                    $expImg = explode(":", $articleDetail['image']);
                    if ($expImg[0] == "http" || $expImg[0] == "https"){
                        $image=$articleDetail['image'];
                    }else{
                        $image=base_url()."assets/Article/".$articleDetail['image'];
                    }
                    if($articleDetail['embed_video']!=''){
                        preg_match('/src="([^"]+)"/', $articleDetail['embed_video'], $match);
                        $articleDetail['article_link']=$match[1];
                        $image=  base_url().$articleDetail['embed_video_thumb'];
                    }
                    //echo "<pre>1";print_r($article);
                    //echo "<pre>2";print_r($articleDetail);die;
                    //echo $image;die;
                    ?>
                    <a href="<?php echo $articleDetail['article_link']; ?>" target="_blank">



                        <?php
                        if($articleDetail['media_type']='0'){?>
                            <div class="video-overlay_open">
                                <a target="_blank" href="<?php echo $articleDetail['article_link']; ?>"><img src="<?php echo $this->config->base_url(); ?>/images/website_images/pause-icon.png" style="height: 100%;"></a>
                            </div>
                        <?php }else{
                            if($articleDetail['embed_video']==''){

                            }else{
                            ?>
                        <div style="position: absolute;
    z-index: 0;
    background: rgba(0,0,0,0.6);
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    text-align: center;
    padding-top: 25px;">
                                <img src="<?php echo $this->config->base_url(); ?>/images/website_images/pause-icon.png">
                                            </div>
                            <?php }}

                    if ($articleDetail['created_by'] == '0' && $articleDetail['image'] != '') {
                        ?>
                        <img src="<?php echo $image; ?>" alt=""/>
                    <?php } else if ($articleDetail['created_by'] == '1' && $articleDetail['image'] != '') {

                        $validation = "/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
                                    if((bool)preg_match($validation, $articleDetail['image']) === false){
                        ?>
                        <img src="<?php echo $image; ?>" alt=""/>
                                    <?php } else{?>
                        <img src="<?php echo $image; ?>" alt=""/>
                                    <?php } ?>
                    <?php }else if ($articleDetail['created_by'] == '2' && $articleDetail['image'] != '') {

                        $validation = "/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
                                    if((bool)preg_match($validation, $articleDetail['image']) === false){
                        ?>
                        <img src="<?php echo $image; ?>" alt=""/>
                                    <?php } else{?>
                        <img src="<?php echo $image; ?>" alt=""/>
                                    <?php } ?>
                    <?php } else if ($article['image'] == '') { ?>
                        <img src="<?php echo $image; ?>" alt=""/>
                    <?php }
                    ?>
                    </a>
                    <a href="<?php echo $articleDetail['article_link']; ?>" target="_blank">
<!--                    <img src="<?php echo $articleDetail['image']; ?>" alt=""/>-->
                    <figcaption><?php echo $articleDetail['title']; ?></figcaption>
                    </a>
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

                    <!--h4><?php //echo $this->lang->line("posted_by_web"); ?> <?php echo $postedBy; ?> <?php //echo $this->lang->line("on_web"); ?> <?php
                                //if ($this->session->userdata['langSelect'] == 'english') {
                                    //echo date("m/d/y", strtotime($articleDetail['publish_date']));
                                    //} else {
                                       // echo date("d/m/Y", strtotime($articleDetail['publish_date']));
                                        //}
                                        ?> | <?php //echo $comment['commentCount']; ?> <?php //echo $this->lang->line("comments_web"); ?></h4-->

                    <div class="article-details">
                        <?php if ($articleDetail['publish_date'] != '') { ?>
                            <span class="article-date">
                                <?php
                                if ($this->session->userdata['langSelect'] == 'english') {
                                    echo date("m/d/y", strtotime($articleDetail['publish_date']));
                                    } else {
                                        echo date("d/m/Y", strtotime($articleDetail['publish_date']));
                                        }
                                        ?>


                                <?php //echo date("M. d, Y", strtotime($articleDetail['publish_date'])); ?></span>
                        <?php }if ($articleDetail['website_url'] != '') {
                            $link = str_replace(array('http://','https://','www.'), '', $articleDetail['website_url']);
                            $link_name = explode('.', $link);
                            ?>
                        <?php echo "source".": ". substr($link_name[0], 0, 40); ?>

                            <!--a class="article-website" target="_blank" href="<?php echo $articleDetail['website_url']; ?>"><?php echo $link[1]; ?></a-->
                        <?php } ?>
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

                    <script src="https://apis.google.com/js/platform.js" async defer></script>
                    <script src="//platform.linkedin.com/in.js" type="text/javascript">
                        lang: en_US
                    </script>
                    <div class="comment-section">
                        <?php
                        if($articleDetail['allow_share']=='1'){
                        ?>
                        <h5><?php echo $this->lang->line("share_on_web"); ?></h5>

                        <div class="social-share clearfix">
                            <ul>
                                <li>
                                    <div class="fb-share-button" data-href="<?php echo $articleDetail['website_url']; ?>" data-layout="button_count"></div>

                                </li>
                                <li>
                                    <div class="g-plus" data-action="share" data-href="<?php echo $articleDetail['website_url']; ?>"></div>

                                </li>
                                <li>
                                    <script type="IN/Share" data-url="<?php echo $articleDetail['website_url']; ?>" data-counter="right"></script>

                                </li>
                            </ul>

                        </div>
                        <!--                            <a class="twitter-share-button"
                          href="https://twitter.com/share">
                        Tweet
                        </a>-->
                        <!-- Place this tag in your head or just before your close body tag. -->


                        <!-- Place this tag where you want the share button to render. -->



                        <div>
                        <?php }
                        $i=0;
                        foreach ($allComments as $comm) {
                            $i++;
                            $exp = explode(":", $comm['photoUrl']);
                            if ($exp[0] == "http" || $exp[0] == "https") {
                                $url = $comm['photoUrl'];
                            } else {
                                if ($comm['photoUrl'] != '') {
                                    $url = $this->config->base_url() . "assets/user_image/" . $comm['photoUrl'];
                                } else {
                                    $url = $this->config->base_url() . "images/website_images/profile-pic.png";
                                }
                            }
                            ?>
                            <div class="people-comment" id="commentDiv">
                                <div class="person-detail">
                                    <figure>
                                        <?php
                                        if ($url != '') {
                                            ?>
                                            <img src="<?php echo $url; ?>" />
                                        <?php } else { ?>

                                        <?php } ?>
                                    </figure>
                                    <strong><?php echo $comm['name']; ?></strong>
                                    <small><?php
                                if ($this->session->userdata['langSelect'] == 'english') {
                                    echo date("m/d/y", strtotime($comm['created_date']));
                                    } else {
                                        echo date("d/m/Y", strtotime($comm['created_date']));
                                        }
                                        ?>
                                        <?php //echo date("M d Y", strtotime($comm['created_date'])); ?> <?php echo $this->lang->line("at_web"); ?> <?php echo date("h:ia", strtotime($comm['created_date'])); ?></small>
                                </div>

                                <p><?php echo $comm['comment']; ?></p>
                            </div>
                        <?php } ?>
                        </div>

                         <?php
                        if($articleDetail['allow_share']=='1'){
                        ?>
                        <h5><?php echo $this->lang->line("leave_us_reply_web"); ?></h5>

                        <div class="comment-box">
                            <form name="commentPost" id="commentPost" action="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail" method="POST">
                                <label><?php echo $this->lang->line("email_address_publish_web"); ?></label>
                                <textarea rows="6" placeholder="<?php echo $this->lang->line("comment_web"); ?>....." name="commentValue"></textarea>
                                <input type="hidden" name="magazineId" value="<?php echo $_GET['magazineId']; ?>" />
                                <input type="hidden" name="articleIdValue" value="<?php echo $articleDetail['id']; ?>" />
                                <input type="hidden" name="articleHidden" value="valueArticle" />
                                <input type="hidden" name="source" value="<?php echo $_GET['source']; ?>" />
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

    </div>




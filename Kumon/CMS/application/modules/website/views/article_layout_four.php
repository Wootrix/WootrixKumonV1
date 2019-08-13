<?php
$explode = $getMagazineAdv['advertisement']['display_time'];
?>
<meta http-equiv="refresh" content="300">
<script>
    var timeToRefresh =<?php echo $explode; ?>;
    var timeToRefreshPage = timeToRefresh + '000';
    setInterval("my_function();", timeToRefreshPage);

    function my_function() {
        console.log("run");
        <?php
        if($_GET['page'] != ''){
        ?>
        var sendValue =<?php echo $_GET['page'] ?>;
        <?php }else{?>
        sendValue = 1;
        <?php }?>

        var magazineId =<?php echo $_GET['magazineId']; ?>;

        //alert(sendValue)
        $.ajax({
            type: "POST",
            data: {advetiseValue: sendValue, magazineIdSend: magazineId},
            dataType: "html",
            url: "<?php echo $this->config->base_url(); ?>index.php/website/article_detail/AdvertiseOnlyLayout4",
            success: function (data) {
                $('#replaceAddvertise').html(data);
                //location.reload();
                //alert(data);
                //$('.grid8 border-top').load(document.URL +  ' #thisdiv');
            }

        });
    }

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
                    success: function (data) {
                        //alert(data);
                        $("#searchRes").html(data);
                        //$("#getValue").val(data);
                    }


                });
        });
    }

    function getResult(title, ID, magID, source) {


        var getValues = $("#getValue").val(title);


        $("#searchRes").hide();
        window.location.href = "<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=" + ID + "&searchId=search&magazineId=" + magID + "&source=" + source;
    }

    $(document).ready(function () {
        $('iframe', window.parent.document).width('580px');
        $('iframe', window.parent.document).height('190px');
    });

</script>
</head>
<body>


<div class="container clearfix">
    <div class="news-container news-container2 clearfix">
        <?php

        /*article 1*/
        $expImg = explode(":", $getMagazineAdv['articles'][0]['image']);
        $expVid = explode(":", $getMagazineAdv['articles'][0]['video_path']);

        if (($expImg[0] == "http" || $expImg[0] == "https") && $getMagazineAdv['articles'][0]['media_type'] == '0') {
            $image1 = $getMagazineAdv['articles'][0]['image'];
        } else if (($expVid[0] == "http" || $expVid[0] == "https") && $getMagazineAdv['articles'][0]['media_type'] == '1') {
            $video1 = $getMagazineAdv['articles'][0]['video_path'];
        } else {
            if ($getMagazineAdv['articles'][0]['image'] != '') {
                $image1 = $this->config->base_url() . 'assets/Article/' . $getMagazineAdv['articles'][0]['image'];
            }
            if ($getMagazineAdv['articles'][0]['video_path'] != '') {
                $video1 = $this->config->base_url() . 'assets/Article/' . $getMagazineAdv['articles'][0]['video_path'];
            }
        }

        //echo $image1;die;

        /*article 2*/
        $expImg = explode(":", $getMagazineAdv['articles'][1]['image']);
        $expVid = explode(":", $getMagazineAdv['articles'][1]['video_path']);

        if (($expImg[0] == "http" || $expImg[0] == "https") && $getMagazineAdv['articles'][1]['media_type'] == '0') {
            $image2 = $getMagazineAdv['articles'][1]['image'];
        } else if (($expVid[0] == "http" || $expVid[0] == "https") && $getMagazineAdv['articles'][1]['media_type'] == '1') {
            $video2 = $getMagazineAdv['articles'][1]['video_path'];
        } else {
            if ($getMagazineAdv['articles'][1]['image'] != '') {
                $image2 = $this->config->base_url() . 'assets/Article/' . $getMagazineAdv['articles'][1]['image'];
            }
            if ($getMagazineAdv['articles'][1]['video_path'] != '') {
                $video2 = $this->config->base_url() . 'assets/Article/' . $getMagazineAdv['articles'][1]['video_path'];
            }
        }
        //echo $image2;die;

        /*article 3*/
        $expImg = explode(":", $getMagazineAdv['articles'][2]['image']);
        $expVid = explode(":", $getMagazineAdv['articles'][2]['video_path']);
        if (($expImg[0] == "http" || $expImg[0] == "https") && $getMagazineAdv['articles'][2]['media_type'] == '0') {
            $image3 = $getMagazineAdv['articles'][2]['image'];
        } else if (($expVid[0] == "http" || $expVid[0] == "https") && $getMagazineAdv['articles'][2]['media_type'] == '1') {
            $video3 = $getMagazineAdv['articles'][2]['video_path'];
        } else {
            if ($getMagazineAdv['articles'][2]['image'] != '') {
                $image3 = $this->config->base_url() . 'assets/Article/' . $getMagazineAdv['articles'][2]['image'];
            }
            if ($getMagazineAdv['articles'][2]['video_path'] != '') {
                $video3 = $this->config->base_url() . 'assets/Article/' . $getMagazineAdv['articles'][2]['video_path'];
            }
        }

        /*article 4*/
        $expImg = explode(":", $getMagazineAdv['articles'][3]['image']);
        $expVid = explode(":", $getMagazineAdv['articles'][3]['video_path']);
        if (($expImg[0] == "http" || $expImg[0] == "https") && $getMagazineAdv['articles'][3]['media_type'] == '0') {
            $image4 = $getMagazineAdv['articles'][3]['image'];
        } else if (($expVid[0] == "http" || $expVid[0] == "https") && $getMagazineAdv['articles'][3]['media_type'] == '1') {
            $video4 = $getMagazineAdv['articles'][3]['video_path'];
        } else {
            if ($getMagazineAdv['articles'][3]['image'] != '') {
                $image4 = $this->config->base_url() . 'assets/Article/' . $getMagazineAdv['articles'][3]['image'];
            }
            if ($getMagazineAdv['articles'][3]['video_path'] != '') {
                $video4 = $this->config->base_url() . 'assets/Article/' . $getMagazineAdv['articles'][3]['video_path'];
            }
        }

        /*article 5*/
        $expImg = explode(":", $getMagazineAdv['articles'][4]['image']);
        $expVid = explode(":", $getMagazineAdv['articles'][4]['video_path']);
        if (($expImg[0] == "http" || $expImg[0] == "https") && $getMagazineAdv['articles'][4]['media_type'] == '0') {
            $image5 = $getMagazineAdv['articles'][4]['image'];
        } else if (($expVid[0] == "http" || $expVid[0] == "https") && $getMagazineAdv['articles'][4]['media_type'] == '1') {
            $video5 = $getMagazineAdv['articles'][4]['video_path'];
        } else {
            if ($getMagazineAdv['articles'][4]['image'] != '') {
                $image5 = $this->config->base_url() . 'assets/Article/' . $getMagazineAdv['articles'][4]['image'];
            }
            if ($getMagazineAdv['articles'][4]['video_path'] != '') {
                $video5 = $this->config->base_url() . 'assets/Article/' . $getMagazineAdv['articles'][4]['video_path'];
            }
        }


        /*article 6*/
        $expImg = explode(":", $getMagazineAdv['articles'][5]['image']);
        $expVid = explode(":", $getMagazineAdv['articles'][5]['video_path']);
        if (($expImg[0] == "http" || $expImg[0] == "https") && $getMagazineAdv['articles'][5]['media_type'] == '0') {
            $image6 = $getMagazineAdv['articles'][5]['image'];
        } else if (($expVid[0] == "http" || $expVid[0] == "https") && $getMagazineAdv['articles'][5]['media_type'] == '1') {
            $video6 = $getMagazineAdv['articles'][5]['video_path'];
        } else {
            if ($getMagazineAdv['articles'][5]['image'] != '') {
                $image6 = $this->config->base_url() . 'assets/Article/' . $getMagazineAdv['articles'][5]['image'];
            }
            if ($getMagazineAdv['articles'][5]['video_path'] != '') {
                $video6 = $this->config->base_url() . 'assets/Article/' . $getMagazineAdv['articles'][5]['video_path'];
            }
        }

        $externalLink1 = $this->config->base_url() . "index.php/registerAccess?url=" . urlencode($getMagazineAdv['articles'][0]['article_link']) . "&magazineId=" . $_GET['magazineId'] . "&articleId=" . $getMagazineAdv['articles'][0]['id'];
        $externalLink2 = $this->config->base_url() . "index.php/registerAccess?url=" . urlencode($getMagazineAdv['articles'][1]['article_link']) . "&magazineId=" . $_GET['magazineId'] . "&articleId=" . $getMagazineAdv['articles'][1]['id'];
        $externallink3 = $this->config->base_url() . "index.php/registerAccess?url=" . urlencode($getMagazineAdv['articles'][2]['article_link']) . "&magazineId=" . $_GET['magazineId'] . "&articleId=" . $getMagazineAdv['articles'][2]['id'];
        $externallink4 = $this->config->base_url() . "index.php/registerAccess?url=" . urlencode($getMagazineAdv['articles'][3]['article_link']) . "&magazineId=" . $_GET['magazineId'] . "&articleId=" . $getMagazineAdv['articles'][3]['id'];
        $externallink5 = $this->config->base_url() . "index.php/registerAccess?url=" . urlencode($getMagazineAdv['articles'][4]['article_link']) . "&magazineId=" . $_GET['magazineId'] . "&articleId=" . $getMagazineAdv['articles'][4]['id'];

        ?>
        <div class="span6">

            <div class="span6">
                <div class="grid3">
                    <section>
                        <?php if ($getMagazineAdv['articles'][0]['id']): ?>
                        <a target="_blank"
                           href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][0]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                            <?php endif; ?>
                            <h4><?php echo mb_substr($getMagazineAdv['articles'][0]['title'], 0, 45) . "..."; ?></h4>
                            <?php if ($getMagazineAdv['articles'][0]['id']): ?>
                        </a>
                    <?php endif; ?>
                        <div class="article-details">

                            <?php
                            if ($getMagazineAdv['articles'][0]['article_link'] != null && $getMagazineAdv['articles'][0]['article_link'] != '' && $getMagazineAdv['articles'][0]['article_link'] != '0') {
                                $link = str_replace(array('http://', 'https://', 'www.'), '', $getMagazineAdv['articles'][0]['article_link']);
                                $link_name = explode('.', $link);
                                ?>
                                <a target="_blank" href="<?php echo $externalLink1; ?>"
                                   class="article-website"><?php echo ($link_name[0]) . "..."; ?></a>
                            <?php } ?>
                        </div>
                        <?php if ($getMagazineAdv['articles'][0]['id']): ?>
                            <a target="_blank"
                               href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?&source=<?php echo $getMagazineAdv['articles'][0]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                                <p><?php echo mb_substr($getMagazineAdv['articles'][0]['description_without_html'], 0, 105) . " "; ?></p>
                                <a href="<?php if ($getMagazineAdv['articles'][0]['article_link'] != '' && $getMagazineAdv['articles'][0]['article_link'] != '0') {
                                    echo $externalLink1;
                                } else {
                                    echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][0]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                                } ?>" target="_blank"><?php echo $this->lang->line("more"); ?>..</a>
                            </a>
                        <?php endif; ?>
                    </section>
                </div>
            </div>

            <div class="span6">
                <div class="grid3">
                    <?php if ($getMagazineAdv['articles'][0]['id']): ?>
                    <a target="_blank"
                       href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][0]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>"
                       target="_blank">
                        <?php endif; ?>
                        <?php
                        if ($getMagazineAdv['articles'][0]['media_type'] == '0') {
                            ?>
                            <?php if ($getMagazineAdv['articles'][0]['image'] != '') { ?>
                                <img src="<?php echo $image1; ?>" alt=""/>
                            <?php } else { ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
                                     alt=""/>
                            <?php } ?>

                        <?php } else if ($getMagazineAdv['articles'][0]['media_type'] == '1') {


                            if ($getMagazineAdv['articles'][0]['embed_video'] != '') {
                                preg_match('/src="([^"]+)"/', $getMagazineAdv['articles'][0]['embed_video'], $match);
                                ?>
                                <div class="videos">
                                    <a target="_blank" href="<?php echo $match[0] ?>" class="video">
                                        <span></span>
                                        <img src="<?php echo $this->config->base_url() . $getMagazineAdv['articles'][0]['embed_video_thumb']; ?>"/>
                                </div>
                            <?php } else {


                                ?>
                                <?php if ($getMagazineAdv['articles'][0]['video_path'] != '') { ?>
                                    <!--                                <video>
                                    <source src="<?php echo $video1; ?>" type="video/mp4">
                                </video>-->
                                    <div class="video-overlay">
                                        <a target="_blank" href="<?php echo $video1; ?>" target="_blank"><img
                                                    src="<?php echo base_url(); ?>images/website_images/pause-icon.png"
                                                    class="PauseThumbnal"></a>
                                    </div>
                                    <img src="<?php echo $this->config->base_url() . 'assets/Article/thumbs/' . $getMagazineAdv['articles'][0]['video_path']; ?>demo.jpeg" <!--class="videoThumbnal videoThumbnal4_1"--> />
                                <?php } else { ?>
                                    <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
                                         alt=""/>
                                <?php }
                            } ?>

                        <?php } else {
                            ?>
                            <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
                                 alt=""/>
                        <?php } ?>


                        <!--                            <div class="video-overlay">-->
                        <a target="_blank"
                           href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][0]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>"
                           target="_blank">
                            <?php
                            if ($getMagazineAdv['articles'][0]['media_type'] == '1') {
                                ?>
                                <a target="_blank" href="#"><img alt=""
                                                                 src="<?php echo $this->config->base_url(); ?>images/website_images/pause-icon.png"
                                                                 class="PauseThumbnal"></a>
                            <?php } ?>
                            <h4><?php echo $getMagazineAdv['articles'][0]['title']; ?></h4>
                            <div class="article-details">

                                <?php
                                if ($getMagazineAdv['articles'][0]['article_link'] != '') {
                                    $link = str_replace(array('http://', 'https://', 'www.'), '', $getMagazineAdv['articles'][0]['article_link']);
                                    $link_name = explode('.', $link);
                                    ?>
                                    <a target="_blank"
                                       href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][0]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>"
                                       class="article-website"><?php echo ($link_name[0]) . "..."; ?></a>
                                <?php } ?>
                            </div>
                        </a>
                        <!--                        </div>-->

                        <?php if ($getMagazineAdv['articles'][0]['id']): ?>
                    </a>
                <?php endif; ?>
                </div>
            </div>

            <div class="span12">
                <div class="grid5 border-top border-bottom">
                    <div id="replaceAddvertise">
                        <figure>
                            <?php if ($getMagazineAdv['advertisement']['link'] != ""){ ?>
                            <a target="_blank" href="<?php echo $getMagazineAdv['advertisement']['link']; ?>"
                               target="_blank"
                               onclick='remoteaddr(<?php echo $getMagazineAdv['advertisement']['adsid']; ?>);'>
                                <?php } ?>
                                <?php
                                if ($getMagazineAdv['advertisement']['media_type'] == '1') {
                                    ?>
                                    <?php if ($getMagazineAdv['advertisement']['cover_image'] != '') { ?>
                                        <img src="<?php echo $this->config->base_url(); ?>assets/Advertise/<?php echo $getMagazineAdv['advertisement']['cover_image']; ?>"
                                             alt=""/>
                                    <?php } else { ?>
                                        <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-2.jpg"
                                             alt=""/>
                                    <?php } ?>

                                <?php } else if ($getMagazineAdv['advertisement']['media_type'] == '2') { ?>
                                    <?php if ($getMagazineAdv['advertisement']['cover_image'] != '') {
                                        if ($getMagazineAdv['advertisement']['cover_image'] == 'embed') {
                                            //echo $getMagazineAdv['advertisement']['embed_video'];
                                        } else { ?>
                                            <video>
                                                <source src="<?php echo $this->config->base_url(); ?>assets/Advertise/thumbs/<?php echo $getMagazineAdv['advertisement']['cover_image']; ?>demo.jpeg"
                                                        type="video/mp4">
                                            </video>
                                            <div class="video-overlay">
                                                <a target="_blank"
                                                   href="<?php echo $this->config->base_url(); ?>assets/Advertise/<?php echo $getMagazineAdv['advertisement']['cover_image']; ?>"
                                                   target="_blank"><img
                                                            src="<?php echo base_url(); ?>images/website_images/pause-icon.png"
                                                            class="PauseThumbnal"></a>
                                            </div>
                                            <img src="<?php echo $this->config->base_url(); ?>assets/Advertise/thumbs/<?php echo $getMagazineAdv['advertisement']['cover_image']; ?>demo.jpeg"
                                                 class="videoThumbnal"/>
                                        <?php }
                                    } else { ?>
                                        <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-2.jpg"
                                             alt=""/>
                                    <?php } ?>

                                <?php } else {
                                    ?>
                                    <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-2.jpg"
                                         alt=""/>
                                <?php } ?>
                            </a>

                        </figure>

                        <?php if ($getMagazineAdv['advertisement']['cover_image'] == 'embed') {
                            preg_match('/src="([^"]+)"/', $getMagazineAdv['advertisement']['embed_video'], $match);
                            ?>
                            <a target="_blank" href="<?php echo $match[1]; ?>" target="_blank">
                                <div id="openEmbed">
                                    <img src="<?php echo base_url() . $getMagazineAdv['advertisement']['embed_thumb']; ?>"/>
                                </div>
                                <div class="video-overlay">
                                    <a target="_blank" href="<?php echo $match[1]; ?>" target="_blank"><img
                                                src="<?php echo base_url(); ?>images/website_images/pause-icon.png"
                                                class="PauseThumbnal"></a>
                                </div>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="articleTitle">
                        <h4><?php echo $this->lang->line("advertisement_text"); ?></h4>
                        <p><?php //echo $getMagazineAdv['advertisement']['title']; ?></p>
                    </div>
                </div>
            </div>

            <div class="span12">
                <div class="grid2 clearfix">
                    <figure>
                        <?php if ($getMagazineAdv['articles'][1]['id']): ?>
                        <a target="_blank"
                           href="<?php if ($getMagazineAdv['articles'][1]['article_link'] != '' && $getMagazineAdv['articles'][1]['article_link'] != '0') {
                               echo $externallink2;
                           } else {
                               echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][1]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][1]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                           } ?>">
                            <?php endif; ?>
                            <?php
                            if ($getMagazineAdv['articles'][1]['media_type'] == '0') {
                                ?>
                                <?php if ($getMagazineAdv['articles'][1]['image'] != '') { ?>
                                    <img src="<?php echo $image2; ?>" alt=""/>
                                <?php } else { ?>
                                    <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
                                         alt=""/>
                                <?php } ?>

                            <?php } else if ($getMagazineAdv['articles'][1]['media_type'] == '1') {


                                if ($getMagazineAdv['articles'][1]['embed_video'] != '') {
                                    preg_match('/src="([^"]+)"/', $getMagazineAdv['articles'][1]['embed_video'], $match);
                                    ?>
                                    <div class="videos">
                                        <a target="_blank" href="<?php echo $match[1] ?>" class="video">
                                            <span></span>
                                            <img src="<?php echo $this->config->base_url() . $getMagazineAdv['articles'][1]['embed_video_thumb']; ?>"/>
                                    </div>
                                <?php } else {


                                    ?>
                                    <?php if ($getMagazineAdv['articles'][1]['video_path'] != '') { ?>
                                        <div class="video-overlay">
                                            <a target="_blank" href="<?php echo $video2; ?>" target="_blank"><img
                                                        src="<?php echo base_url(); ?>images/website_images/pause-icon.png"
                                                        class="PauseThumbnal"></a>
                                        </div>
                                        <img src="<?php echo $this->config->base_url() . 'assets/Article/thumbs/' . $getMagazineAdv['articles'][1]['video_path']; ?>demo.jpeg" <!--class="videoThumbnal"--> />
                                    <?php } else { ?>
                                        <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
                                             alt=""/>
                                    <?php }
                                } ?>

                            <?php } else {
                                ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
                                     alt=""/>
                            <?php } ?>
                            <?php if ($getMagazineAdv['articles'][1]['id']): ?>
                        </a>
                    <?php endif; ?>
                    </figure>
                    <section>
                        <?php if ($getMagazineAdv['articles'][1]['id']): ?>
                        <a target="_blank"
                           href="<?php if ($getMagazineAdv['articles'][1]['article_link'] != '' && $getMagazineAdv['articles'][1]['article_link'] != '0') {
                               echo $externallink2;
                           } else {
                               echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][1]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][1]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                           } ?>">
                            <?php endif; ?>
                            <h4><?php echo mb_substr($getMagazineAdv['articles'][1]['title'], 0, 25) . ".."; ?></h4>
                            <?php if ($getMagazineAdv['articles'][1]['id']): ?>
                        </a>
                    <?php endif; ?>
                        <div class="article-details">

                            <?php
                            if ($getMagazineAdv['articles'][1]['article_link'] != '') {
                                $link = str_replace(array('http://', 'https://', 'www.'), '', $getMagazineAdv['articles'][1]['article_link']);
                                $link_name = explode('.', $link);
                                ?>
                                <a target="_blank" href="<?php echo $externallink2; ?>"
                                   class="article-website"><?php echo ($link_name[1]) . "..."; ?></a>
                            <?php } ?>
                        </div>
                        <?php if ($getMagazineAdv['articles'][1]['id']): ?>
                            <a target="_blank"
                               href="<?php if ($getMagazineAdv['articles'][1]['article_link'] != '' && $getMagazineAdv['articles'][1]['article_link'] != '0') {
                                   echo $externallink2;
                               } else {
                                   echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][1]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][1]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                               } ?>">
                                <p><?php echo mb_substr($getMagazineAdv['articles'][1]['description_without_html'], 0, 200) . ""; ?></p>
                                <?php if ($getMagazineAdv['articles'][1]['description_without_html'] != "") {
                                    echo $this->lang->line("more"); ?>.. <?php } ?>
                            </a>
                        <?php endif; ?>

                    </section>
                </div>

            </div>

        </div>

        <div class="span3">
            <div class="grid4 border-left">
                <figure>
                    <?php if ($getMagazineAdv['articles'][2]['id']): ?>
                    <a target="_blank"
                       href="<?php if ($getMagazineAdv['articles'][2]['article_link'] != '' && $getMagazineAdv['articles'][2]['article_link'] != '0') {
                           echo $externallink3;
                       } else {
                           echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][2]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][2]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                       } ?>">
                        <?php endif; ?>
                        <?php
                        if ($getMagazineAdv['articles'][2]['media_type'] == '0') {
                            ?>
                            <?php if ($getMagazineAdv['articles'][2]['image'] != '') { ?>
                                <img src="<?php echo $image3; ?>" alt=""/>
                            <?php } else { ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
                                     alt=""/>
                            <?php } ?>

                        <?php } else if ($getMagazineAdv['articles'][2]['media_type'] == '1') {


                            if ($getMagazineAdv['articles'][2]['embed_video'] != '') {
                                preg_match('/src="([^"]+)"/', $getMagazineAdv['articles'][2]['embed_video'], $match);
                                ?>
                                <div class="videos">
                                    <a target="_blank" href="<?php echo $match[1] ?>" class="video">
                                        <span></span>
                                        <img src="<?php echo $this->config->base_url() . $getMagazineAdv['articles'][2]['embed_video_thumb']; ?>"/>
                                </div>
                            <?php } else {


                                ?>
                                <?php if ($getMagazineAdv['articles'][2]['video_path'] != '') { ?>
                                    <div class="video-overlay">
                                        <a target="_blank" href="<?php echo $video3; ?>" target="_blank"><img
                                                    src="<?php echo base_url(); ?>images/website_images/pause-icon.png"
                                                    class="PauseThumbnal"></a>
                                    </div>
                                    <img src="<?php echo $this->config->base_url() . 'assets/Article/thumbs/' . $getMagazineAdv['articles'][2]['video_path']; ?>demo.jpeg" <!--class="videoThumbnal"--> />
                                <?php } else { ?>
                                    <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
                                         alt=""/>
                                <?php }
                            } ?>

                        <?php } else {
                            ?>
                            <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
                                 alt=""/>
                        <?php } ?>
                        <?php if ($getMagazineAdv['articles'][2]['id']): ?>
                    </a>
                <?php endif; ?>
                </figure>
                <section>
                    <?php if ($getMagazineAdv['articles'][2]['id']): ?>
                    <a target="_blank"
                       href="<?php if ($getMagazineAdv['articles'][2]['article_link'] != '' && $getMagazineAdv['articles'][2]['article_link'] != '0') {
                           echo $externallink3;
                       } else {
                           echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][2]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][2]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                       } ?>">
                        <?php endif; ?>
                        <h4><?php echo mb_substr($getMagazineAdv['articles'][2]['title'], 0, 45) . "..."; ?></h4>
                        <?php if ($getMagazineAdv['articles'][2]['id']): ?>
                    </a>
                <?php endif; ?>
                    <div class="article-details">

                        <?php
                        if ($getMagazineAdv['articles'][2]['article_link'] != '') {
                            $link = str_replace(array('http://', 'https://', 'www.'), '', $getMagazineAdv['articles'][2]['article_link']);
                            $link_name = explode('.', $link);
                            ?>
                            <a target="_blank" href="<?php echo $externallink3; ?>"
                               class="article-website"><?php echo ($link_name[0]) . "..."; ?></a>
                        <?php } ?>
                    </div>
                    <a target="_blank"
                       href="<?php if ($getMagazineAdv['articles'][2]['article_link'] != '' && $getMagazineAdv['articles'][2]['article_link'] != '0') {
                           echo $externallink3;
                       } else {
                           echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][2]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][2]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                       } ?>">
                        <p><?php echo mb_substr($getMagazineAdv['articles'][2]['description_without_html'], 0, 120) . ""; ?></p>
                        <?php if ($getMagazineAdv['articles'][2]['description_without_html'] != "") {
                            echo $this->lang->line("more"); ?>.. <?php } ?></a>
                </section>

            </div>

            <div class="grid3 border-top border-left">
                <?php
                if ($getMagazineAdv['articles'][4]['media_type'] == '0') {
                    ?>
                    <?php if ($getMagazineAdv['articles'][4]['image'] != '') { ?>
                        <img src="<?php echo $image5; ?>" alt=""/>
                    <?php } else { ?>
                        <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
                             alt=""/>
                    <?php } ?>

                <?php } else if ($getMagazineAdv['articles'][4]['media_type'] == '1') {


                    if ($getMagazineAdv['articles'][4]['embed_video'] != '') {
                        preg_match('/src="([^"]+)"/', $getMagazineAdv['articles'][4]['embed_video'], $match);
                        ?>
                        <div class="videos">
                            <a target="_blank" href="<?php echo $match[1] ?>" class="video">
                                <span></span>
                                <img src="<?php echo $this->config->base_url() . $getMagazineAdv['articles'][4]['embed_video_thumb']; ?>"/>
                        </div>
                    <?php } else {


                        ?>
                        <?php if ($getMagazineAdv['articles'][4]['video_path'] != '') { ?>
                            <div class="video-overlay">
                                <a target="_blank" href="<?php echo $video5; ?>" target="_blank"><img
                                            src="<?php echo base_url(); ?>images/website_images/pause-icon.png"></a>
                            </div>
                            <img src="<?php echo $this->config->base_url() . 'assets/Article/thumbs/' . $getMagazineAdv['articles'][4]['video_path']; ?>demo.jpeg"
                                 class="videoThumbnal"/>
                        <?php } else { ?>
                            <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
                                 alt=""/>
                        <?php }
                    } ?>

                <?php } else {
                    ?>
                    <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg" alt=""/>
                <?php } ?>
            </div>

        </div>

        <div class="span3">

            <div class="grid4 border-left">

                <figure>
                    <?php if ($getMagazineAdv['articles'][3]['id']): ?>
                    <a target="_blank"
                       href="<?php if ($getMagazineAdv['articles'][3]['article_link'] != '' && $getMagazineAdv['articles'][3]['article_link'] != '0') {
                           echo $externalLink4;
                       } else {
                           echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][3]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][3]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                       } ?>">
                        <?php endif; ?>
                        <?php
                        if ($getMagazineAdv['articles'][3]['media_type'] == '0') {
                            ?>
                            <?php if ($getMagazineAdv['articles'][3]['image'] != '') { ?>
                                <img src="<?php echo $image4; ?>" alt=""/>
                            <?php } else { ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
                                     alt=""/>
                            <?php } ?>

                        <?php } else if ($getMagazineAdv['articles'][3]['media_type'] == '1') {


                            if ($getMagazineAdv['articles'][3]['embed_video'] != '') {
                                preg_match('/src="([^"]+)"/', $getMagazineAdv['articles'][3]['embed_video'], $match);
                                ?>
                                <div class="videos">
                                    <a target="_blank" href="<?php echo $match[1] ?>" class="video">
                                        <span></span>
                                        <img src="<?php echo $this->config->base_url() . $getMagazineAdv['articles'][3]['embed_video_thumb']; ?>"/>
                                </div>
                            <?php } else {

                                ?>
                                <?php if ($getMagazineAdv['articles'][3]['video_path'] != '') { ?>
                                    <div class="video-overlay">
                                        <a target="_blank" href="<?php echo $video4; ?>" target="_blank"><img
                                                    src="<?php echo base_url(); ?>images/website_images/pause-icon.png"
                                                    class="PauseThumbnal"></a>
                                    </div>
                                    <img src="<?php echo $this->config->base_url() . 'assets/Article/thumbs/' . $getMagazineAdv['articles'][3]['video_path']; ?>demo.jpeg"
                                         class="videoThumbnal"/>
                                <?php } else { ?>
                                    <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
                                         alt=""/>
                                <?php }
                            } ?>

                        <?php } else {
                            ?>
                            <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
                                 alt=""/>
                        <?php } ?>
                        <?php if ($getMagazineAdv['articles'][3]['id']): ?>
                    </a>
                <?php endif; ?>
                </figure>

                <section>
                    <?php if ($getMagazineAdv['articles'][3]['id']): ?>
                    <a target="_blank"
                       href="<?php if ($getMagazineAdv['articles'][3]['article_link'] != '' && $getMagazineAdv['articles'][3]['article_link'] != '0') {
                           echo $externalLink4;
                       } else {
                           echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][3]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][3]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                       } ?>">
                        <?php endif; ?>
                        <h4><?php echo mb_substr($getMagazineAdv['articles'][3]['title'], 0, 45) . "..."; ?></h4>
                        <?php if ($getMagazineAdv['articles'][3]['id']): ?>
                    </a>
                <?php endif; ?>

                    <div class="article-details">

                        <?php
                        if ($getMagazineAdv['articles'][3]['article_link'] != '') {
                            $link = str_replace(array('http://', 'https://', 'www.'), '', $getMagazineAdv['articles'][4]['article_link']);
                            $link_name = explode('.', $link);
                            ?>
                            <a target="_blank" href="<?php echo $externalLink4; ?>"
                               class="article-website"><?php echo ($link_name[0]) . "..."; ?></a>
                        <?php } ?>
                    </div>
                    <a target="_blank"
                       href="<?php if ($getMagazineAdv['articles'][3]['article_link'] != '' && $getMagazineAdv['articles'][3]['article_link'] != '0') {
                           echo $externalLink4;
                       } else {
                           echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][3]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][3]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                       } ?>">
                        <p><?php echo mb_substr($getMagazineAdv['articles'][3]['description_without_html'], 0, 120) . ""; ?></p>
                        <?php if ($getMagazineAdv['articles'][3]['description_without_html'] != "") {
                            echo $this->lang->line("more"); ?>.. <?php } ?></a>
                </section>

            </div>

            <div class="grid3 border-top border-left">

                <section>

                    <?php if ($getMagazineAdv['articles'][4]['id']): ?>
                    <a target="_blank"
                       href="<?php if ($getMagazineAdv['articles'][4]['article_link'] != '' && $getMagazineAdv['articles'][4]['article_link'] != '0') {
                           echo $externallink5;
                       } else {
                           echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][4]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][4]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                       } ?>">
                        <?php endif; ?>
                        <h4><h4><?php echo mb_substr($getMagazineAdv['articles'][4]['title'], 0, 45) . "..."; ?></h4></h4>
                        <?php if ($getMagazineAdv['articles'][4]['id']): ?>
                    </a>
                    <?php endif;?>

                    <div class="article-details">

                        <?php
                        if ($getMagazineAdv['articles'][4]['article_link'] != '') {
                            $link = str_replace(array('http://', 'https://', 'www.'), '', $getMagazineAdv['articles'][4]['article_link']);
                            $link_name = explode('.', $link);
                            ?>
                            <a target="_blank" href="<?php echo $externallink5; ?>"
                               class="article-website"><?php echo ($link_name[0]) . "..."; ?></a>
                        <?php } ?>
                    </div>

                    <a target="_blank"
                       href="<?php if ($getMagazineAdv['articles'][4]['article_link'] != '' && $getMagazineAdv['articles'][4]['article_link'] != '0') {
                           echo $externallink5;
                       } else {
                           echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][4]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][4]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                       } ?>">
                        <p><?php echo mb_substr($getMagazineAdv['articles'][4]['description_without_html'], 0, 120) . ""; ?></p>
                        <?php if ($getMagazineAdv['articles'][4]['description_without_html'] != "") {
                            echo $this->lang->line("more"); ?>.. <?php } ?></a>

                </section>

            </div>

        </div>

    </div>

    <div class="pagination clearfix">
        <?php echo $this->pagination->create_links(); ?>
    </div>

</div>

<script>

    function remoteaddr(ads) { // get ip of user

        $.getJSON("http://jsonip.com?callback=?", function (data) {

            var ipA;
            ipA = data.ip;
            //alert(ipA);
            //$('#ip').html(ipA);
        });
        $.ajax({
            //alert("test");
            type: "POST",
            data: {val: 'ipA', ads: ads},
            dataType: "html",
            url: "<?php $this->config->base_url(); ?>website/website_login/saveLatLong",
            success: function (data) {
                //alert(data);
            }

        });
    }

</script>

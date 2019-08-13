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
        if ($_GET['page'] != '') {
        ?>
        var sendValue =<?php echo $_GET['page'] ?>;
        <?php } else { ?>
        sendValue = 1;
        <?php } ?>

        var magazineId =<?php echo $_GET['magazineId']; ?>;

        //alert(sendValue)
        $.ajax({
            type: "POST",
            data: {advetiseValue: sendValue, magazineIdSend: magazineId},
            dataType: "html",
            url: "<?php echo $this->config->base_url(); ?>index.php/website/article_detail/AdvertiseOnly",
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
        $('iframe', window.parent.document).width('300px');
        $('iframe', window.parent.document).height('190px');
    });
</script>
</head>
<body>

<div class="container clearfix">
    <div class="news-container clearfix">
        <?php
        //                echo "here";
        //                print_r($getMagazineAdv['articles']);


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

        $externalLink1 = $this->config->base_url() . "index.php/registerAccess?url=" . urlencode($getMagazineAdv['articles'][0]['article_link']) . "&magazineId=" . $_GET['magazineId'] . "&articleId=" . $getMagazineAdv['articles'][0]['id'];
        $externalLink2 = $this->config->base_url() . "index.php/registerAccess?url=" . urlencode($getMagazineAdv['articles'][1]['article_link']) . "&magazineId=" . $_GET['magazineId'] . "&articleId=" . $getMagazineAdv['articles'][1]['id'];
        $externalLink3 = $this->config->base_url() . "index.php/registerAccess?url=" . urlencode($getMagazineAdv['articles'][2]['article_link']) . "&magazineId=" . $_GET['magazineId'] . "&articleId=" . $getMagazineAdv['articles'][2]['id'];

        ?>
        
        <div class="span6">

            <div class="grid6 border-right">

                <figure>
                    <?php if ($getMagazineAdv['articles'][0]['id']): ?>
                    <a target="_blank" href="<?php if ($getMagazineAdv['articles'][0]['article_link'] != '') {
                        echo $externalLink1;
                    } else {
                        echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][0]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                    } ?>">
                        <?php endif; ?>
                        <?php

                        if ($getMagazineAdv['articles'][0]['media_type'] == '0') { ?>
                            <?php if ($getMagazineAdv['articles'][0]['image'] != '') { ?>
                                <img src="<?php echo $image1; ?>" alt=""/>
                            <?php } else { ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-1.jpg"
                                     alt=""/>
                            <?php } ?>
                        <?php } else if ($getMagazineAdv['articles'][0]['media_type'] == '1') {

                            if ($getMagazineAdv['articles'][0]['embed_video'] != '') {
                                preg_match('/src="([^"]+)"/', $getMagazineAdv['articles'][0]['embed_video'], $match);
                                ?>
                                <div class="videos">
                                    <a target="_blank" href="<?php echo $match[1] ?>" class="video">
                                        <span></span>
                                        <img src="<?php echo $this->config->base_url() . $getMagazineAdv['articles'][0]['embed_video_thumb']; ?>"/>
                                </div>
                            <?php } else {


                                ?>
                                <?php if ($getMagazineAdv['articles'][0]['video_path'] != '') { ?>
                                    <!--                                <video>
                                    <source src="<?php echo $video1; ?>" type="video/mp4" />
                                </video>-->
                                    <div class="video-overlay video-overlay1_1">
                                        <a target="_blank" href="<?php echo $video1; ?>"><img
                                                    src="<?php echo base_url(); ?>images/website_images/pause-icon.png"
                                                    class="PauseThumbnal"></a>
                                    </div>
                                    <img src="<?php echo $this->config->base_url() . 'assets/Article/thumbs/' . $getMagazineAdv['articles'][0]['video_path']; ?>demo.jpeg"
                                         class="videoThumbnal videoThumbnal1_1"/>
                                <?php } else { ?>
                                    <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-1.jpg"
                                         alt=""/>
                                <?php }
                            } ?>

                        <?php } else {
                            ?>
                            <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-1.jpg"
                                 alt=""/>
                        <?php } ?>

                        <?php if ($getMagazineAdv['articles'][0]['id']): ?>
                    </a>
                <?php endif; ?>

                </figure>


                <section>
                    <?php if ($getMagazineAdv['articles'][0]['id']): ?>
                    <a target="_blank" href="<?php if ($getMagazineAdv['articles'][0]['article_link'] != '') {
                        echo $externalLink1;
                    } else {
                        echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][0]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                    } ?>">
                        <?php endif; ?>
                        <h4><?php echo mb_substr($getMagazineAdv['articles'][0]['title'], 0, 47) . "..."; ?></h4>
                        <?php if ($getMagazineAdv['articles'][0]['id']): ?>
                    </a>
                <?php endif; ?>
                    <div class="article-details">
                        <?php
                        if ($getMagazineAdv['articles'][0]['article_link'] != '') {
                            $link = str_replace(array('http://', 'https://', 'www.'), '', $getMagazineAdv['articles'][0]['article_link']);
                            $link_name = explode('.', $link);

                            ?>
                            <a target="_blank" class="article-website"
                               href="<?php echo $externalLink1; ?>"><?php echo mb_substr($link_name[0], 0, 40) . "..."; ?></a>
                        <?php } ?>
                    </div>
                    <a target="_blank" href="<?php if ($getMagazineAdv['articles'][0]['article_link'] != '') {
                        echo $externalLink1;
                    } else {
                        echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][0]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                    } ?>">
                        <p><?php echo mb_substr($getMagazineAdv['articles'][0]['description_without_html'], 0, 450) . ""; ?></p>
                        <?php if ($getMagazineAdv['articles'][0]['description_without_html'] != "") {
                            echo $this->lang->line("more"); ?>.. <?php } ?></a>


                    <!--                        <a target="_blank" href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                        <p>
                         <?php echo mb_substr($getMagazineAdv['articles'][0]['description_without_html'], 0, 550) . ""; ?></p>
                         <?php if ($getMagazineAdv['articles'][0]['description_without_html'] != "") {
                        echo $this->lang->line("more"); ?>.. <?php } ?>
                        
                        </a>-->
                </section>

            </div>
            </a>

        </div>

        <div class="span3">

            <div class="grid7 border-right">

                <figure>

                    <?php if ($getMagazineAdv['articles'][1]['id']): ?>

                    <a target="_blank" href="<?php if ($getMagazineAdv['articles'][1]['article_link'] != '') {
                        echo $externalLink2;
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
                                    <!--                                <video>
                                    <source src="<?php echo $video2; ?>" type="video/mp4">
                                </video>-->
                                    <div class="video-overlay video-overlay1_2">
                                        <a target="_blank" href="<?php echo $video2; ?>"><img
                                                    src="<?php echo base_url(); ?>images/website_images/pause-icon.png"
                                                    class="PauseThumbnal"></a>
                                    </div>
                                    <img src="<?php echo $this->config->base_url() . 'assets/Article/thumbs/' . $getMagazineAdv['articles'][1]['video_path']; ?>demo.jpeg"
                                         class="videoThumbnal videoThumbnal1_2"/>
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

                    <a target="_blank" href="<?php if ($getMagazineAdv['articles'][1]['article_link'] != '') {
                        echo $externalLink2;
                    } else {
                        echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][1]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][1]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                    } ?>">

                        <?php endif; ?>
                        <h4><?php echo mb_substr($getMagazineAdv['articles'][1]['title'], 0, 30) . "..."; ?></h4>

                        <?php if ($getMagazineAdv['articles'][1]['id']): ?>
                    </a>
                <?php endif; ?>

                    <div class="article-details">

                        <?php
                        if ($getMagazineAdv['articles'][1]['article_link'] != '') {
                            $link = str_replace(array('http://', 'https://', 'www.'), '', $getMagazineAdv['articles'][1]['article_link']);
                            $link_name = explode('.', $link);
                            //print_r($link_name);die;
                            ?>
                            <?php if ($getMagazineAdv['articles'][1]['id']): ?>
                                <a target="_blank" href="<?php echo $externalLink2; ?>"
                                class="article-website">
                            <?php endif; ?>

                            <?php echo mb_substr($link_name[0], 0, 25) . "..."; ?>

                            <?php if ($getMagazineAdv['articles'][1]['id']): ?>
                                </a>
                            <?php endif; ?>

                        <?php } ?>
                    </div>

                    <?php if ($getMagazineAdv['articles'][1]['id']): ?>
                    <a target="_blank" href="<?php if ($getMagazineAdv['articles'][1]['article_link'] != '') {
                        echo $externalLink2;
                    } else {
                        echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][1]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][1]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                    } ?>">
                        <?php endif; ?>

                        <p><?php echo mb_substr($getMagazineAdv['articles'][1]['description_without_html'], 0, 500) . ".."; ?></p>
                        <?php if ($getMagazineAdv['articles'][1]['description_without_html'] != "") {
                            echo $this->lang->line("more"); ?>.. <?php } ?>

                        <?php if ($getMagazineAdv['articles'][1]['id']): ?>
                    </a>
                <?php endif; ?>
                </section>


            </div>
            </a>

        </div>

        <div class="span3">
            <div class="grid4">
                <figure>

                    <?php if ($getMagazineAdv['articles'][2]['id']): ?>
                    <a target="_blank" href="<?php if ($getMagazineAdv['articles'][2]['article_link'] != '') {
                        echo $externalLink3;
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
                                        <img src="<?php echo $this->config->base_url() . $getMagazineAdv['articles'][2]['embed_video_thumb']; ?>"
                                             class="video"/>
                                </div>
                            <?php } else {

                                ?>
                                <?php if ($getMagazineAdv['articles'][2]['video_path'] != '') { ?>
                                    <!--                                <video>
                                    <source src="<?php echo $video3; ?>" type="video/mp4">
                                </video>-->
                                    <div class="video-overlay video-overlay1_3">
                                        <a target="_blank" href="<?php echo $video3; ?>"><img
                                                    src="<?php echo base_url(); ?>images/website_images/pause-icon.png"
                                                    class="PauseThumbnal"></a>
                                    </div>
                                    <img src="<?php echo $this->config->base_url() . 'assets/Article/thumbs/' . $getMagazineAdv['articles'][2]['video_path']; ?>demo.jpeg"
                                         class="videoThumbnal videoThumbnal1_3"/>
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
                    <a target="_blank" href="<?php if ($getMagazineAdv['articles'][2]['article_link'] != '') {
                        echo $externalLink3;
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
                            <a target="_blank" href="<?php echo $externalLink3; ?>"
                               class="article-website"><?php echo mb_substr($link_name[0], 0, 25) . "..."; ?></a>
                        <?php } ?>
                    </div>
                    <?php if ($getMagazineAdv['articles'][2]['id']): ?>
                    <a target="_blank" href="<?php if ($getMagazineAdv['articles'][2]['article_link'] != '') {
                        echo $externalLink3;
                    } else {
                        echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=<?php echo $getMagazineAdv['articles'][2]['source']; ?>&articleId=<?php echo $getMagazineAdv['articles'][2]['id']; ?>&magazineId=<?php echo $_GET['magazineId'];
                    } ?>">
                        <?php endif; ?>
                        <p><?php echo mb_substr($getMagazineAdv['articles'][2]['description_without_html'], 0, 120) . ".."; ?></p>
                        <?php if ($getMagazineAdv['articles'][2]['description_without_html'] != "") {
                            echo $this->lang->line("more"); ?>.. <?php } ?>
                        <?php if ($getMagazineAdv['articles'][2]['id']): ?>
                    </a>
                <?php endif; ?>

                </section>

            </div>

            <div class="grid8 border-top">
                <div id="replaceAddvertise">
                    <?php if ($getMagazineAdv['advertisement']['link'] != "") { ?>
                    <a target="_blank" href="<?php echo $getMagazineAdv['advertisement']['link']; ?>"
                       onclick='remoteaddr(<?php echo $getMagazineAdv['advertisement']['adsid']; ?>);'>
                        <?php } ?>
                        <figure>
                            <?php
                            if ($getMagazineAdv['advertisement']['media_type'] == '1') {
                                ?>
                                <?php if ($getMagazineAdv['advertisement']['cover_image'] != '') { ?>
                                    <img src="<?php echo $this->config->base_url(); ?>assets/Advertise/<?php echo $getMagazineAdv['advertisement']['cover_image']; ?>"
                                         alt=""/>
                                <?php } else { ?>
                                    <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
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
                                               href="<?php echo $this->config->base_url(); ?>assets/Advertise/<?php echo $getMagazineAdv['advertisement']['cover_image']; ?>"><img
                                                        src="<?php echo base_url(); ?>images/website_images/pause-icon.png"
                                                        class="PauseThumbnal"></a>
                                        </div>
                                        <img src="<?php echo $this->config->base_url(); ?>assets/Advertise/thumbs/<?php echo $getMagazineAdv['advertisement']['cover_image']; ?>demo.jpeg"
                                             class="videoThumbnal"/>
                                    <?php }
                                } else { ?>
                                    <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
                                         alt=""/>
                                <?php } ?>

                            <?php } else {
                                ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg"
                                     alt=""/>
                            <?php } ?>

                        </figure>
                    </a>

                    <?php if ($getMagazineAdv['advertisement']['cover_image'] == 'embed') {
                    preg_match('/src="([^"]+)"/', $getMagazineAdv['advertisement']['embed_video'], $match);
                    ?>
                    <a href="<?php echo $match[1]; ?>" target="_blank">
                        <div id="openEmbed">
                            <img src="<?php echo base_url() . $getMagazineAdv['advertisement']['embed_thumb']; ?>"/>
                        </div></div>
                <div class="video-overlay">
                    <a href="<?php echo $match[1]; ?>" target="_blank"><img
                                src="<?php echo base_url(); ?>images/website_images/pause-icon.png"
                                class="PauseThumbnal"></a>
                </div>
                </a>
                <?php } ?>
                <div class="articleTitle">
                    <h4><?php echo $this->lang->line("advertisement_text"); ?></h4>
                    <p><?php //echo $getMagazineAdv['advertisement']['title']; ?></p>
                </div>
            </div>


        </div>
        <?php ?>
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
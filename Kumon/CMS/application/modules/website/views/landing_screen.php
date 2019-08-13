
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
                            //alert(data);
                            $("#searchRes").html(data);
                            //$("#getValue").val(data);
                        }


                    });
        });
    }
    function getResult(title, ID) {


        var getValues = $("#getValue").val(title);


        $("#searchRes").hide();
        window.location.href = "<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=" + ID + "&searchId=search";
    }
</script>

</head>
<body>


    <div class="container clearfix">

        <article class="open-article clearfix">

            <div class="span12 no-padding">
                <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-articles">
                    <figure>

                        <?php
                        if ($recentArticle['video_path'] != '') {
                            $videoPathThumb = $this->config->base_url() . "assets/Article/thumbs/" . $recentArticle['video_path']."demo.jpeg";
                            $videoPath = $this->config->base_url() . "assets/Article/" . $recentArticle['video_path'];
                        } else {
                            if($recentArticle['image'] !=""){
                            $expImg = explode(":", $recentArticle['image']);

                            if (($expImg[0] == "http" || $expImg[0] == "https")) {
                                $image1 = $recentArticle['image'];
                            } else {
                                $image1 = $this->config->base_url() . "assets/Article/" . $recentArticle['image'];
                            }}else{
                                $image1 = $this->config->base_url().'images/website_images/placeholder-1.jpg';
                            }
                        }

                        if($recentArticle['embed_video']!=''){?>

                        <img src="<?php echo base_url().$recentArticle['embed_video_thumb']; ?>" alt="" />

                        <div class="video-overlay" style="position: absolute;z-index: 1;background: rgba(0,0,0,0);width: 100%;height: 100%;top: 0;left: 0;text-align: center;padding-top: 25px;">
                            <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-articles"><img id="landinId" src="<?php echo $this->config->base_url(); ?>/images/website_images/pause-icon.png" style="width: 63%;"></a>
                                                </div>
                        <?php }else{

                        if($image1!=''){
                        ?>
                        <img src="<?php echo $image1; ?>" alt="" />
                        <?php }else if($videoPath!=''){?>
                        <video>
                            <source src="<?php echo $videoPath; ?>" type="video/mp4">

                        </video>

                        <?php }}?>
                        <figcaption><?php echo $this->lang->line("Open_Article"); ?></figcaption>
                    </figure>
                </a>

                <section>
                    <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-articles">
                        <h4><?php echo $recentArticle['title']; ?></h4>
                    </a>
                    <div class="article-details">
                        <?php if ($recentArticle['publish_date'] != '') { ?>
                            <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-articles">
                                <span class="article-date">
                                    <?php
                            if ($this->session->userdata['langSelect'] == 'english') {
                            echo date("m/d/y", strtotime($recentArticle['publish_date']));
                            } else {
                            echo date("d/m/Y", strtotime($recentArticle['publish_date']));
                            }?>
                                    <?php //echo date("M. d, Y", strtotime($recentArticle['publish_date'])); ?></span>
                            </a>
                        <?php }if ($recentArticle['article_link'] != '') {

                            $link = str_replace(array('http://','https://','www.'), '', $recentArticle['article_link']);
                                    $link_name = explode('.', $link);
                                 echo $this->lang->line("source_name") .": ". substr($link_name[0], 0, 25) . "";

                            ?>
                            <!--a class="article-website" href="<?php echo $recentArticle['website_url']; ?>"><?php //echo $recentArticle['website_url']; ?></a-->
                        <?php }?>
                    </div>
                    <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-articles"><p><?php echo substr($recentArticle['description_without_html'], "0", "1000"); ?>....</p></a>


                </section>

            </div>

        </article>



        <div class="magzine-container">
            <h4><?php echo $this->lang->line("Magazines"); ?></h4>
            <ul>
                <?php
                if (!empty($userMagazines)) {
                    foreach ($userMagazines as $u) {
                        ?>
                        <li><a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-list-layout?magazineId=<?php echo $u['id']; ?>"><img src="<?php echo $this->config->base_url(); ?>assets/Magazine_cover/<?php echo $u['cover_image']; ?>" alt="" /></a></li>
                    <?php }
                } else {
                    redirect('wootrix-articles');

                    ?>
                    You haven’t subscribed any Magazine yet.
<?php } ?>
            </ul>

        </div>

    </div>





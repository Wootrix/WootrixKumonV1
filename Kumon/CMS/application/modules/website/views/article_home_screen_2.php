<?php
$explode = $getMagazineAdv['advertisement']['display_time'];
?>
<meta http-equiv="refresh" content="300" >
<script>
    <?php if($explode!=''){ ?>
    var timeToRefresh=<?php echo $explode; ?>;
    <?php }?>
    var timeToRefreshPage=timeToRefresh+'000';
    setInterval("my_function();", timeToRefreshPage);
    function my_function() {
        console.log("run");
        <?php 
        if($_GET['page']!=''){
        ?>
        var sendValue=<?php echo $_GET['page'] ?>;
        <?php }else{?>
                sendValue=1;
        <?php }?>
        
        //alert(sendValue)
        $.ajax({
            type: "POST",
            data: {advetiseValue: sendValue},
            dataType: "html",
            url: "<?php echo $this->config->base_url(); ?>index.php/website/website_login/AdvertiseOnlyLayout2",
            success: function (data)
            {
                $('#replaceAddvertise').html(data);
                //location.reload();
                //alert(data);
                //$('.grid8 border-top').load(document.URL +  ' #thisdiv');
            }

        });
    }
    
    function getSearchKeyword(data,seg) {
        var segValue = $("#segValue").val();
        var magValue = $("#magazineId").val();
        //alert(magValue);
        $("#searchRes").show();
        $(document).ready(function () {
            $.ajax(
                    {
                        url: "<?php echo $this->config->base_url(); ?>index.php/wootrix-search-magzine",
                        type: "POST",
                        data: {keyValue: data,keySeg: segValue,keyMagId: magValue},
                        success: function (data)
                        {
                            //alert(data);
                            $("#searchRes").html(data);
                            //$("#getValue").val(data);
                        }


                    });
        });
    }
    function getResult(title, ID, magID,source) {

        
       var getValues =  $("#getValue").val(title);
       
       
        $("#searchRes").hide();
        window.location.href = "<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=" + ID+"&searchId=search&magazineId=" + magID+"&source="+source;
    }
    $( document ).ready(function() {
    $('iframe', window.parent.document).width('585px');
    $('iframe', window.parent.document).height('570px');
});
</script> 
</head>
    <body>
      
        
        <div class="container clearfix">
            <div class="news-container clearfix">
                <?php 
                /*article 1*/
                $expImg = explode(":", $getMagazineAdv['articles'][0]['image']);
                $expVid = explode(":", $getMagazineAdv['articles'][0]['video_path']);
                
                if (($expImg[0] == "http" || $expImg[0] == "https") && $getMagazineAdv['articles'][0]['media_type']=='0') {
                $image1 = $getMagazineAdv['articles'][0]['image'];
                }else if(($expVid[0] == "http" || $expVid[0] == "https") && $getMagazineAdv['articles'][0]['media_type']=='1') {
                $video1 = $getMagazineAdv['articles'][0]['video_path'];
                } else {
                if ($getMagazineAdv['articles'][0]['image'] != '') {
                $image1 = $this->config->base_url().'assets/Article/'.$getMagazineAdv['articles'][0]['image'];
                }if ($getMagazineAdv['articles'][0]['video_path'] != '') {
                $video1 = $this->config->base_url().'assets/Article/'.$getMagazineAdv['articles'][0]['video_path'];
                } 
                }

                $externalLink1 = $this->config->base_url() . "index.php/registerAccess?url=" . urlencode($getMagazineAdv['articles'][0]['article_link']) . "&magazineId=" . $getMagazineAdv['articles'][0]['magazine_id'] . "&articleId=" . $getMagazineAdv['articles'][0]['id'];

                ?>
                <div class="span6">
                        <div class="grid6 border-right">
                            <figure>
                                <?php if ($getMagazineAdv['articles'][0]['article_link'] != '') { ?>
                                    <a href="<?php echo $externalLink1; ?>" target="_blank">
                                <?php }else{ ?>
                                <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                                <?php } ?>
                                    <?php 
                                if($getMagazineAdv['articles'][0]['media_type']=='0'){?>
                                    <?php if($getMagazineAdv['articles'][0]['image'] != ''){ ?>
                                <img src="<?php echo $image1; ?>" alt=""/>
                                    <?php }else{ ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-7.png" alt=""/>
                                    <?php } ?>
                                
                                
                                <?php }else if($getMagazineAdv['articles'][0]['media_type']=='1'){
                                    if($getMagazineAdv['articles'][0]['embed_video']!=''){ 
                                                preg_match('/src="([^"]+)"/', $getMagazineAdv['articles'][0]['embed_video'], $match); 
                                                ?>
                                                <div class="videos">
                                                	<a target="_blank" href="<?php echo $match[1] ?>"  class="video">
                                                	<span></span>
                                                <img src="<?php echo $this->config->base_url() . $getMagazineAdv['articles'][0]['embed_video_thumb']; ?>" />
                                            </div>
                                            <?php }else{
                                    
                                    ?>
                                <?php if($getMagazineAdv['articles'][0]['video_path'] != ''){ ?>
<!--                                <video>
                                    <source src="<?php echo $video1; ?>" type="video/mp4">
                                </video>-->
                                <div class="videos">
                                        <a target="_blank"href="<?php echo $video1; ?>"  class="video">
                                           <span></span>                                       
                                		   <img src="<?php echo $this->config->base_url().'assets/Article/thumbs/'.$getMagazineAdv['articles'][0]['video_path'];?>demo.jpeg" class="videoThumbnal videoThumbnal2_1" />
                                </div>
                                    <?php }else{ ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-7.png" alt=""/>
                                <?php }} ?>
                                   
                                <?php }else {
                                ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-7.png" alt=""/>
                                <?php } ?>
                                </a>
                            </figure>
                            
                        <section>
                            <?php if ($getMagazineAdv['articles'][0]['article_link'] != '') { ?>
                                    <a href="<?php echo $externalLink1; ?>" target="_blank">
                                <?php }else{ ?>
                                <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                                <?php } ?>
                            <!--a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>"-->
                        <h4><?php echo substr($getMagazineAdv['articles'][0]['title'],0,60)."..."; ?></h4>
                            </a>
                        <div class="article-details">
                            <?php if($getMagazineAdv['articles'][0]['publish_date']!=''){ ?>
                            <span class="article-date">
                             <?php
                            if ($this->session->userdata['langSelect'] == 'english') {
                            echo date("m/d/y", strtotime($getMagazineAdv['articles'][0]['publish_date']));
                            } else {
                            echo date("d/m/Y", strtotime($getMagazineAdv['articles'][0]['publish_date']));
                            }
                            ?>   
                                
                                <?php //echo date("M. d, Y",strtotime($getMagazineAdv['articles'][0]['publish_date'])); ?></span>
                            <?php }if($getMagazineAdv['articles'][0]['article_link']!=''){ 
                                //$link = explode("http://www.", $getMagazineAdv['articles'][0]['article_link']);
                                $link = str_replace(array('http://','https://','www.'), '', $getMagazineAdv['articles'][0]['article_link']);
                                $link_name = explode('.', $link);
                                ?>
                            <!--a href="<?php echo $getMagazineAdv['articles'][0]['article_link']; ?>" targe="_blank" class="article-website"><?php //echo substr($link[1],0,25)."..."; ?></a-->
                              <?php echo $this->lang->line("source_name").": ". substr($link_name[0], 0, 25) . ""; ?>
                                <?php } ?>
                           
                        </div>
                        
                        <p><?php echo substr($getMagazineAdv['articles'][0]['description_without_html'],0,450).""; ?>
                            <?php if ($getMagazineAdv['articles'][0]['article_link'] != '') { ?>
                                    <a href="<?php echo $externalLink1; ?>" target="_blank">
                                        <?php if($getMagazineAdv['articles'][0]['description_without_html'] !=""){ echo $this->lang->line("more");?>.. <?php } ?></a>
                                <?php }else{ ?>
                        <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>"><?php echo $this->lang->line("more"); ?>..</a>
                                <?php } ?>
                        </p>
                         
                        </section>
                        </div>
                    
                </div>
                
                <div class="span6">
                    <div class="grid9">
                        <div id="replaceAddvertise">
                        <figure>
                            <?php if($getMagazineAdv['advertisement']['link'] !=""){ ?>
                            <a href="<?php echo $getMagazineAdv['advertisement']['link']; ?>" target="_blank" onclick='remoteaddr(<?php echo $getMagazineAdv['advertisement']['adsid']; ?>);'>
                            <?php } ?>
                            <?php 
                                if($getMagazineAdv['advertisement']['media_type']=='1'){?>
                            <?php if($getMagazineAdv['advertisement']['cover_image'] != ''){ ?>
                                <img src="<?php echo $this->config->base_url(); ?>assets/Advertise/<?php echo $getMagazineAdv['advertisement']['cover_image']; ?>" alt=""/>
                                    <?php }else{ ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-8.png" alt=""/>
                                    <?php } ?>
                                
                                <?php }else if($getMagazineAdv['advertisement']['media_type']=='2'){?>
                                <?php if($getMagazineAdv['advertisement']['cover_image'] != ''){
                                    if($getMagazineAdv['advertisement']['cover_image']=='embed'){
                //echo $getMagazineAdv['advertisement']['embed_video'];
            }else{
                                    ?>
                                <video>
                                    <source src="<?php echo $this->config->base_url(); ?>assets/Advertise/<?php echo $getMagazineAdv['advertisement']['cover_image']; ?>" type="video/mp4">
                                </video>
                                <div class="video-overlay">
                                            <a href="<?php echo $this->config->base_url(); ?>assets/Advertise/<?php echo $getMagazineAdv['advertisement']['cover_image']; ?>" target="_blank"><img src="<?php echo base_url(); ?>images/website_images/pause-icon.png" class="PauseThumbnal"></a>
                                </div>
                                <img src="<?php echo $this->config->base_url(); ?>assets/Advertise/thumbs/<?php echo $getMagazineAdv['advertisement']['cover_image']; ?>demo.jpeg" class="videoThumbnal" />
            <?php }}else{ ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-8.png" alt=""/>
                                    <?php } ?>
                                  
                                <?php }else{
                                ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-8.png" alt=""/>
                                <?php }?>
                            </a>
                           
                        </figure>
                        
                            </a>
                        
                        <?php if ($getMagazineAdv['advertisement']['cover_image'] == 'embed') { 
                           ?>
                        <a href="<?php echo $match[1]; ?>" target="_blank" >
                            <div id="openEmbed">
                            <img src="<?php echo base_url().$getMagazineAdv['advertisement']['embed_thumb']; ?>" />
                            </div>
                            <div class="video-overlay">
                              <a href="<?php echo $match[1]; ?>" target="_blank"><img src="<?php echo base_url(); ?>images/website_images/pause-icon.png" class="PauseThumbnal"></a>
                            </div>
                        </a>
                        <?php }?>
                        <div class="articleTitle">
                            <h4><?php echo $this->lang->line("advertisement_text"); ?></h4>
                            <p><?php //echo $getMagazineAdv['advertisement']['title'];   ?></p>
                        </div>
                    </div>
                </div>

        </div>

        <div class="pagination clearfix">
			<?php echo $this->pagination->create_links(); ?>
        </div>


    </div>  
      <script>
       
        function remoteaddr(ads) { // get ip of user
            
            $.getJSON("http://jsonip.com?callback=?", function(data) {
                
             var ipA;
                ipA = data.ip;
                //alert(ipA);
                //$('#ip').html(ipA);
            });
            $.ajax({
                //alert("test");
                type: "POST",
                data: {val: '1',ads:ads},
                dataType: "html",
                url: "<?php $this->config->base_url(); ?>website/website_login/saveLatLong",
                success: function(data)
                {
                    //alert(data);
                }

            });
        }

    </script>    

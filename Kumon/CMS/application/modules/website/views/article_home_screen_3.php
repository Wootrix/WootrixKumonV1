<?php
$explode = $getMagazineAdv['advertisement']['display_time'];
if($explode!=''){
    
}else{
    $explode='';
}
?>
<meta http-equiv="refresh" content="300" >
<script>
    var timeToRefresh=<?php echo $explode; ?>;
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
            url: "<?php echo $this->config->base_url(); ?>index.php/website/website_login/AdvertiseOnlyLayout3",
            success: function (data)
            {
                //console.log(data);
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
    $('iframe', window.parent.document).width('580px');
    $('iframe', window.parent.document).height('380px');
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
                
                //echo $image1;die;
                
                 /*article 2*/
                $expImg = explode(":", $getMagazineAdv['articles'][1]['image']);
                $expVid = explode(":", $getMagazineAdv['articles'][1]['video_path']);
                
                if (($expImg[0] == "http" || $expImg[0] == "https") && $getMagazineAdv['articles'][1]['media_type']=='0') {
                $image2 = $getMagazineAdv['articles'][1]['image'];
                }else if(($expVid[0] == "http" || $expVid[0] == "https") && $getMagazineAdv['articles'][1]['media_type']=='1') {
                $video2 = $getMagazineAdv['articles'][1]['video_path'];
                } else {
                if ($getMagazineAdv['articles'][1]['image'] != '') {
                $image2 = $this->config->base_url().'assets/Article/'.$getMagazineAdv['articles'][1]['image'];
                }if ($getMagazineAdv['articles'][1]['video_path'] != '') {
                $video2 = $this->config->base_url().'assets/Article/'.$getMagazineAdv['articles'][1]['video_path'];
                }
                }
                //echo $image2;die;
                
                 /*article 3*/
                $expImg = explode(":", $getMagazineAdv['articles'][2]['image']);
                $expVid = explode(":", $getMagazineAdv['articles'][2]['video_path']);
                if (($expImg[0] == "http" || $expImg[0] == "https") && $getMagazineAdv['articles'][2]['media_type']=='0') {
                $image3 = $getMagazineAdv['articles'][2]['image'];
                }else if(($expVid[0] == "http" || $expVid[0] == "https") && $getMagazineAdv['articles'][2]['media_type']=='1') {
                $video3 = $getMagazineAdv['articles'][2]['video_path'];
                } else {
                if ($getMagazineAdv['articles'][2]['image'] != '') {
                $image3 = $this->config->base_url().'assets/Article/'.$getMagazineAdv['articles'][2]['image'];
                }if ($getMagazineAdv['articles'][2]['video_path'] != '') {
                $video3 = $this->config->base_url().'assets/Article/'.$getMagazineAdv['articles'][2]['video_path'];
                }
                }
                
                 /*article 4*/
                $expImg = explode(":", $getMagazineAdv['articles'][3]['image']);
                $expVid = explode(":", $getMagazineAdv['articles'][3]['video_path']);
                if (($expImg[0] == "http" || $expImg[0] == "https") && $getMagazineAdv['articles'][3]['media_type']=='0') {
                $image4 = $getMagazineAdv['articles'][3]['image'];
                }else if(($expVid[0] == "http" || $expVid[0] == "https") && $getMagazineAdv['articles'][3]['media_type']=='1') {
                $video4 = $getMagazineAdv['articles'][3]['video_path'];
                } else {
                if ($getMagazineAdv['articles'][3]['image'] != '') {
                $image4 = $this->config->base_url().'assets/Article/'.$getMagazineAdv['articles'][3]['image'];
                }if ($getMagazineAdv['articles'][3]['video_path'] != '') {
                $video4 = $this->config->base_url().'assets/Article/'.$getMagazineAdv['articles'][3]['video_path'];
                }
                }
                
                 /*article 5*/
                $expImg = explode(":", $getMagazineAdv['articles'][4]['image']);
                $expVid = explode(":", $getMagazineAdv['articles'][4]['video_path']);
                if (($expImg[0] == "http" || $expImg[0] == "https") && $getMagazineAdv['articles'][4]['media_type']=='0') {
                $image5 = $getMagazineAdv['articles'][4]['image'];
                }else if(($expVid[0] == "http" || $expVid[0] == "https") && $getMagazineAdv['articles'][4]['media_type']=='1') {
                $video5 = $getMagazineAdv['articles'][4]['video_path'];
                } else {
                if ($getMagazineAdv['articles'][4]['image'] != '') {
                $image5 = $this->config->base_url().'assets/Article/'.$getMagazineAdv['articles'][4]['image'];
                }if ($getMagazineAdv['articles'][4]['video_path'] != '') {
                $video5 = $this->config->base_url().'assets/Article/'.$getMagazineAdv['articles'][4]['video_path'];
                }
                }

                $externalLink1 = $this->config->base_url() . "index.php/registerAccess?url=" . urlencode( $getMagazineAdv['articles'][0]['article_link'] ) . "&magazineId=" . $getMagazineAdv['articles'][0]['magazine_id'] . "&articleId=" . $getMagazineAdv['articles'][0]['id'];
                $externalLink2 = $this->config->base_url() . "index.php/registerAccess?url=" . urlencode($getMagazineAdv['articles'][1]['article_link'] ) . "&magazineId=" . $getMagazineAdv['articles'][1]['magazine_id'] . "&articleId=" . $getMagazineAdv['articles'][1]['id'];
                $externalLink3 = $this->config->base_url() . "index.php/registerAccess?url=" . urlencode($getMagazineAdv['articles'][2]['article_link'] ) . "&magazineId=" . $getMagazineAdv['articles'][2]['magazine_id'] . "&articleId=" . $getMagazineAdv['articles'][2]['id'];
                $externalLink4 = $this->config->base_url() . "index.php/registerAccess?url=" . urlencode($getMagazineAdv['articles'][3]['article_link'] ) . "&magazineId=" . $getMagazineAdv['articles'][3]['magazine_id'] . "&articleId=" . $getMagazineAdv['articles'][3]['id'];
                $externalLink5 = $this->config->base_url() . "index.php/registerAccess?url=" . urlencode($getMagazineAdv['articles'][4]['article_link'] ) . "&magazineId=" . $getMagazineAdv['articles'][4]['magazine_id'] . "&articleId=" . $getMagazineAdv['articles'][4]['id'];
                
                ?>
                
                <div class="span6">
                    <div class="grid1 border-right border-bottom">
                        <div id="replaceAddvertise">
                            <?php if($getMagazineAdv['advertisement']['link'] !=""){ ?>
                        <a href="<?php echo $getMagazineAdv['advertisement']['link']; ?>" target="_blank" onclick='remoteaddr(<?php echo $getMagazineAdv['advertisement']['adsid']; ?>);'>
                            <?php } ?>
                        <figure>
                            <?php 
                                if($getMagazineAdv['advertisement']['media_type']=='1'){?>
                        <?php if($getMagazineAdv['advertisement']['cover_image'] != ''){ ?>
                                <img src="<?php echo $this->config->base_url(); ?>assets/Advertise/<?php echo $getMagazineAdv['advertisement']['cover_image'] ?>" alt=""/>
                                    <?php }else{ ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-1.jpg" alt=""/>
                                    <?php } ?>
                        
                                <?php }elseif($getMagazineAdv['advertisement']['media_type']=='2'){ ?>
                                <?php if($getMagazineAdv['advertisement']['cover_image'] != ''){
                                    if($getMagazineAdv['advertisement']['cover_image']=='embed'){
                //echo $getMagazineAdv['advertisement']['embed_video'];
            }else{?>
                                <video>
                                    <source src="<?php echo $this->config->base_url(); ?>assets/Advertise/thumbs/<?php echo $getMagazineAdv['advertisement']['cover_image']; ?>demo.jpeg" type="video/mp4">
                                </video>
                                <div class="video-overlay">
                                            <a href="<?php echo $this->config->base_url(); ?>assets/Advertise/<?php echo $getMagazineAdv['advertisement']['cover_image']; ?>" target="_blank"><img src="<?php echo base_url(); ?>images/website_images/pause-icon.png" class="PauseThumbnal"></a>
                                </div>
                                <img src="<?php echo $this->config->base_url(); ?>assets/Advertise/thumbs/<?php echo $getMagazineAdv['advertisement']['cover_image']; ?>demo.jpeg" class="videoThumbnal" />
            <?php }}else{ ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-1.jpg" alt=""/>
                                    <?php } ?>
                         
                                <?php }else{ ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-1.jpg" alt=""/>
                                <?php } ?>
                               
                        </figure>
                        </a>
                            
                        <?php if ($getMagazineAdv['advertisement']['cover_image'] == 'embed') { 
                            preg_match('/src="([^"]+)"/', $getMagazineAdv['advertisement']['embed_video'], $match);
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
                            </div>
                         <div class="articleTitle">
                                <h4><?php echo $this->lang->line("advertisement_text"); ?></h4>
                                <p><?php //echo $getMagazineAdv['advertisement']['title'];  ?></p>
                            </div>
                    </div>
                    <div class="grid2 border-right clearfix">
                        <?php if ($getMagazineAdv['articles'][0]['article_link'] != '') { ?>
                                    <a href="<?php echo $externalLink1; ?>" target="_blank">
                                <?php }else{ ?>
                        <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                                <?php } ?>
                            <figure>
                            
                             <?php 
                                if($getMagazineAdv['articles'][0]['media_type']=='0'){?>
                                    <?php if($getMagazineAdv['articles'][0]['image'] != ''){ ?>
                                <img src="<?php echo $image1; ?>" alt=""/>
                                    <?php }else{ ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.png" alt=""/>
                                    <?php } ?>
                                
                                
                                <?php }else if($getMagazineAdv['articles'][0]['media_type']=='1'){
                                    if($getMagazineAdv['articles'][0]['embed_video']!=''){ 
                                                preg_match('/src="([^"]+)"/', $getMagazineAdv['articles'][0]['embed_video'], $match); 
                                                ?>
                                                <div class="videos">
                                                <a target="_blank" href="<?php echo $match[1] ?>" class="video">
													<span></span>
                                            		<img src="<?php echo $this->config->base_url() . $getMagazineAdv['articles'][0]['embed_video_thumb']; ?>" />
                                            	</div>
                                            <?php }else{
                                    
                                    ?>
                                <?php if($getMagazineAdv['articles'][0]['video_path'] != ''){ ?>
<!--                                <video>
                                    <source src="<?php echo $video1; ?>" type="video/mp4">
                                </video>-->
                                <div class="video-overlay overlay3_1">
                                            <a href="<?php echo $video1; ?>" target="_blank"><img src="<?php echo base_url(); ?>images/website_images/pause-icon.png" class="PauseThumbnal"></a>
                                </div>
                                <img src="<?php echo $this->config->base_url().'assets/Article/thumbs/'.$getMagazineAdv['articles'][0]['video_path'];?>demo.jpeg" class="videoThumbnal videoThumbnal3_1" />
                                    <?php }else{ ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.png" alt=""/>
                                <?php }} ?>
                                   
                                <?php }else {
                                ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-7.png" alt=""/>
                                
                                    <?php } ?>
                            
                            
<!--                            <img src="<?php echo $this->config->base_url(); ?>assets/Article/<?php echo $getMagazineAdv['articles'][0]['image']; ?>" alt="" />-->
                        
                        </figure>
                        </a>
                        <section>
                            <?php if ($getMagazineAdv['articles'][0]['article_link'] != '') { ?>
                                    <a href="<?php echo $externalLink1; ?>" target="_blank">
                                <?php }else{ ?>
                            <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                                <?php } ?>
                                <h4><?php echo substr($getMagazineAdv['articles'][0]['title'],0,25).".."; ?></h4>
                            
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
                                <?php //echo date("M. d, Y",strtotime($getMagazineAdv['articles'][0]['publish_date'])); ?>
                            </span>
                            <?php }if($getMagazineAdv['articles'][0]['article_link']!=''){ 
                                 //$link = explode("http://www.", $getMagazineAdv['articles'][0]['article_link']);
                                 $link = str_replace(array('http://','https://','www.'), '', $getMagazineAdv['articles'][0]['article_link']);
                                $link_name = explode('.', $link);
                                 echo $this->lang->line("source_name").": ". substr($link_name[0], 0, 25) . "";                                
                                ?>
                            
                            <?php } ?>
                        </div>
                            
                            <p><?php echo substr($getMagazineAdv['articles'][0]['description_without_html'],0,220)." "; ?>
                                <?php if ($getMagazineAdv['articles'][0]['article_link'] != '') { ?>
                                    <a href="<?php echo$externalLink1; ?>" target="_blank"><?php echo $this->lang->line("more"); ?>..</a>
                                <?php }else{ ?>
                            <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                                <?php if($getMagazineAdv['articles'][0]['description_without_html'] !=""){ echo $this->lang->line("more");?>.. <?php } ?></a>
                                <?php } ?>
                            </p>
                            
                            
                        </section>
                    </div>
                    
                </div>
                <div class="span3">
                    <div class="grid3 border-right">
                        <section>
                            <?php if ($getMagazineAdv['articles'][1]['article_link'] != '') { ?>
                                    <a href="<?php echo $externalLink2; ?>" target="_blank">
                                <?php }else{ ?>
                            <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][1]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                                <?php } ?>
                                <h4><?php echo substr($getMagazineAdv['articles'][1]['title'],0,30); ?></h4>
                            </a>
                        <div class="article-details">
                            <?php if($getMagazineAdv['articles'][1]['publish_date']!=''){ ?>
                            <span class="article-date">
                                <?php
                            if ($this->session->userdata['langSelect'] == 'english') {
                            echo date("m/d/y", strtotime($getMagazineAdv['articles'][1]['publish_date']));
                            } else {
                            echo date("d/m/Y", strtotime($getMagazineAdv['articles'][1]['publish_date']));
                            }
                            ?> 
                                <?php //echo date("M. d, Y",strtotime($getMagazineAdv['articles'][1]['publish_date'])); ?></span>
                            <?php }if($getMagazineAdv['articles'][1]['article_link']!=''){ 
                                //$link = explode("http://www.", $getMagazineAdv['articles'][1]['article_link']);                                
                                $link = str_replace(array('http://','https://','www.'), '', $getMagazineAdv['articles'][1]['article_link']);
                                $link_name = explode('.', $link);
                                 echo $this->lang->line("source_name").": ". substr($link_name[0], 0, 25) . "";  
                                ?>                          
                            
                            <?php } ?>
                        </div>
                        
                            <p><?php echo substr($getMagazineAdv['articles'][1]['description_without_html'],0,170)." "; ?>
                                <?php if ($getMagazineAdv['articles'][1]['article_link'] != '') { ?>
                                    <a href="<?php echo $externalLink2; ?>" target="_blank"><?php echo $this->lang->line("more"); ?>..</a>
                                <?php }else{ ?>
                            <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][1]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                                <?php if($getMagazineAdv['articles'][1]['description_without_html'] !=""){ echo $this->lang->line("more");?>.. <?php } ?></a>
                                <?php } ?>
                            </p>
                        
                        </section>
                    </div>  
                    
                    <div class="grid4 border-right border-top">
                        <?php if ($getMagazineAdv['articles'][2]['article_link'] != '') { ?>
                                    <a href="<?php echo $externalLink3; ?>" target="_blank">
                                <?php }else{ ?>
                        <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][2]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                                <?php } ?>
                            <figure>
                            <?php if($getMagazineAdv['articles'][2]['media_type'] == "0" ){ ?>
                            <?php if($getMagazineAdv['articles'][2]['image'] != ''){ ?>
                                <img src="<?php echo $image3; ?>" alt=""/>
                                    <?php }else{ ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg" alt=""/>
                                    <?php } ?>
                            
                            <?php }elseif($getMagazineAdv['articles'][2]['media_type'] == "1"){
                               if($getMagazineAdv['articles'][2]['embed_video']!=''){ 
                                                preg_match('/src="([^"]+)"/', $getMagazineAdv['articles'][2]['embed_video'], $match); 
                                                ?>
                                                <div class="videos">
                                                	<a target="_blank" href="<?php echo $match[1] ?>" class="video">
                                                		<span></span>
                                            			<img src="<?php echo $this->config->base_url() . $getMagazineAdv['articles'][2]['embed_video_thumb']; ?>" />
                                                </div>
                                            <?php }else{
                                ?>
                            <?php if($getMagazineAdv['articles'][2]['video_path'] != ''){ ?>
<!--                                <video>
                                    <source src="<?php echo $video3; ?>" type="video/mp4">
                                </video>-->
                                <div class="video-overlay video-overlay3_2">
                                            <a href="<?php echo $video3; ?>" target="_blank"><img src="<?php echo base_url(); ?>images/website_images/pause-icon.png" class="PauseThumbnal"></a>
                                </div>
                                <img src="<?php echo $this->config->base_url().'assets/Article/thumbs/'.$getMagazineAdv['articles'][2]['video_path'];?>demo.jpeg" class="videoThumbnal videoThumbnal3_2" />
                                    <?php }else{ ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg" alt=""/>
                                            <?php }} ?>
                                
                            <?php }else{ ?>
                            <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg" alt=""/>
                            <?php } ?>
                        </figure>
                        </a>
                        <section>
                            <?php if ($getMagazineAdv['articles'][2]['article_link'] != '') { ?>
                                    <a href="<?php echo $externalLink3; ?>" target="_blank">
                                <?php }else{ ?>
                            <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][2]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                                <?php } ?>
                                <h4><?php echo substr($getMagazineAdv['articles'][2]['title'],0,25).".."; ?></h4>
                            </a>
                        <div class="article-details">
                            <?php if($getMagazineAdv['articles'][2]['publish_date']!=''){ ?>
                            <span class="article-date">
                                <?php
                            if ($this->session->userdata['langSelect'] == 'english') {
                            echo date("m/d/y", strtotime($getMagazineAdv['articles'][2]['publish_date']));
                            } else {
                            echo date("d/m/Y", strtotime($getMagazineAdv['articles'][2]['publish_date']));
                            }
                            ?> 
                                <?php //echo date("M. d, Y",strtotime($getMagazineAdv['articles'][2]['publish_date'])); ?></span>
                            <?php }if($getMagazineAdv['articles'][2]['article_link']!=''){ 
                                //$link = explode("http://www.", $getMagazineAdv['articles'][2]['article_link']);                                
                                $link = str_replace(array('http://','https://','www.'), '', $getMagazineAdv['articles'][2]['article_link']); 
                                $link_name = explode('.', $link);
                                 echo $this->lang->line("source_name").": ". substr($link_name[0], 0, 25) . "";  
                                ?>
                            
                            <?php } ?>
                        </div>
                        
                            <p><?php echo substr($getMagazineAdv['articles'][2]['description_without_html'],0,150).".."; ?>
                                <?php if ($getMagazineAdv['articles'][2]['article_link'] != '') { ?>
                                    <a href="<?php echo $externalLink3; ?>" target="_blank"><?php echo $this->lang->line("more"); ?>..</a>
                                <?php }else{ ?>
                            <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][0]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                               <?php if($getMagazineAdv['articles'][2]['description_without_html'] !=""){ echo $this->lang->line("more");?>.. <?php } ?></a> 
                            
                                <?php } ?>
                            </p>
                        
                        </section>
                        
                        
                    </div>
                </div>
                
                <div class="span3">
                    <div class="grid3 border-bottom">
                        <?php if($getMagazineAdv['articles'][3]['media_type'] == "0") { ?>
                        <?php if($getMagazineAdv['articles'][3]['image'] != ''){ ?>
                                <img src="<?php echo $image4; ?>" alt="" />
                                    <?php }else{ ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg" alt=""/>
                                    <?php } ?>
                        
                        <?php }elseif($getMagazineAdv['articles'][3]['media_type'] == "1"){
                            if($getMagazineAdv['articles'][3]['embed_video']!=''){ 
                                                preg_match('/src="([^"]+)"/', $getMagazineAdv['articles'][3]['embed_video'], $match); 
                                                ?>
                                                <div class="videos">
                                                	<a target="_blank" href="<?php echo $match[1] ?>" class="video">
													<span></span>
                                                	<img src="<?php echo $this->config->base_url() . $getMagazineAdv['articles'][3]['embed_video_thumb']; ?>" />
                                                	<a/>
                                            	</div>
                                            <?php }else{
                            
                            ?>
                        <?php if($getMagazineAdv['articles'][3]['video_path'] != ''){ ?>
<!--                                <video>
                                    <source src="<?php echo $video4; ?>" type="video/mp4">
                                </video>-->
                                <div class="video-overlay">
                                            <a href="<?php echo $video4; ?>" target="_blank"><img src="<?php echo base_url(); ?>images/website_images/pause-icon.png" class="PauseThumbnal"></a>
                                </div>
                                <img src="<?php echo $this->config->base_url().'assets/Article/thumbs/'.$getMagazineAdv['articles'][3]['video_path'];?>demo.jpeg" class="videoThumbnal" />
                                    <?php }else{ ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg" alt=""/>
                        <?php }} ?>
                                
                        <?php }else{ ?>
                        <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg" alt=""/>
                        <?php } ?>
                        
                        <div class="image-overlay">
                            
                            <h4><?php echo substr($getMagazineAdv['articles'][3]['title'],0,30); ?></h4>
                            <div class="article-details">
                                <?php if($getMagazineAdv['articles'][3]['publish_date']!=''){ ?>
                                <span class="article-date article-date-white">
                                    <?php
                            if ($this->session->userdata['langSelect'] == 'english') {
                            echo date("m/d/y", strtotime($getMagazineAdv['articles'][3]['publish_date']));
                            } else {
                            echo date("d/m/Y", strtotime($getMagazineAdv['articles'][3]['publish_date']));
                            }
                            ?> </span>
                                    <?php //echo date("M. d, Y",strtotime($getMagazineAdv['articles'][3]['publish_date'])); ?></span>
                                <?php }if($getMagazineAdv['articles'][3]['article_link']!=''){ 
                                    //$link = explode("http://www.", $getMagazineAdv['articles'][3]['article_link']);
                                    $link = str_replace(array('http://','https://','www.'), '', $getMagazineAdv['articles'][3]['article_link']);
                                 $link_name = explode('.', $link); ?>
                            <br><span class="article-date article-date-white" style="background:transparent;margin: -21px;font-size: 16px;">
                            <?php 
                                 echo $this->lang->line("source_name").": ". substr($link_name[0], 0, 25) . "";  
                                    ?>
                                                            </span>

                                <?php } ?>
                             </div>
                            <p><?php echo substr($getMagazineAdv['articles'][3]['description_without_html'],0,150); ?>
                            <?php if ($getMagazineAdv['articles'][3]['article_link'] != '') { ?>
                                    <a href="<?php echo $externalLink4; ?>" target="_blank"><?php echo $this->lang->line("more"); ?>..</a>
                                <?php }else{ ?>
                        <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][3]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                            <?php if($getMagazineAdv['articles'][3]['description_without_html'] !=""){ echo $this->lang->line("more");?>.. <?php } ?></a>
                                <?php } ?>
                        </p>
                            
                            
                           
                        </div>
                        
                    </div>
                    
                    <div class="grid4">
                        <?php if ($getMagazineAdv['articles'][4]['article_link'] != '') { ?>
                                    <a href="<?php echo $externalLink5; ?>" target="_blank">
                                <?php }else{ ?>
                        <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][4]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                                <?php } ?>
                            <figure>
                            <?php if($getMagazineAdv['articles'][4]['media_type'] == "0") { ?>
                            <?php if($getMagazineAdv['articles'][4]['image'] != ''){ ?>
                                <img src="<?php echo $image5; ?>">
                                    <?php }else{ ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg" alt=""/>
                                    <?php } ?>
                            
                            <?php }elseif($getMagazineAdv['articles'][4]['media_type'] == "1"){
                                
                                if($getMagazineAdv['articles'][4]['embed_video']!=''){ 
                                                preg_match('/src="([^"]+)"/', $getMagazineAdv['articles'][4]['embed_video'], $match); 
                                                ?>
                                                <div class="videos">
                                                	<a target="_blank" href="<?php echo $match[1] ?>" class="video">
                                                		<span></span>
                                            			<img src="<?php echo $this->config->base_url() . $getMagazineAdv['articles'][4]['embed_video_thumb']; ?>" />
                                            	</div>
                                            <?php }else{
                                ?>
                            <?php if($getMagazineAdv['articles'][4]['video_path'] != ''){ ?>
<!--                                <video>
                                    <source src="<?php echo $video5; ?>" type="video/mp4">
                                </video>-->
                                <div class="video-overlay video-overlay3_4">
                                            <a href="<?php echo $video5; ?>" target="_blank"><img src="<?php echo base_url(); ?>images/website_images/pause-icon.png" class="PauseThumbnal"></a>
                                </div>
                                <img src="<?php echo $this->config->base_url().'assets/Article/thumbs/'.$getMagazineAdv['articles'][4]['video_path'];?>demo.jpeg" class="videoThumbnal videoThumbnal3_4" />
                                    <?php }else{ ?>
                                <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg" alt=""/>
                                            <?php }} ?>
                                
                            <?php }else{ ?>
                            <img src="<?php echo $this->config->base_url(); ?>images/website_images/placeholder-3.jpg" alt=""/>
                            <?php } ?>
                            
                        
                        </figure>
                        </a>
                        <section>
                            <?php if ($getMagazineAdv['articles'][4]['article_link'] != '') { ?>
                                    <a href="<?php echo $externalLink5; ?>" target="_blank">
                                <?php }else{ ?>
                            <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][4]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                                <?php } ?>
                                <h4><?php echo substr($getMagazineAdv['articles'][4]['title'],0,30); ?></h4>
                            </a>
                        <div class="article-details">
                            <?php if($getMagazineAdv['articles'][4]['publish_date']!=''){ ?>
                            <span class="article-date ">
                                <?php
                            if ($this->session->userdata['langSelect'] == 'english') {
                            echo date("m/d/y", strtotime($getMagazineAdv['articles'][4]['publish_date']));
                            } else {
                            echo date("d/m/Y", strtotime($getMagazineAdv['articles'][4]['publish_date']));
                            }
                            ?> 
                                <?php //echo date("M. d, Y",strtotime($getMagazineAdv['articles'][4]['publish_date'])); ?></span>
                            <?php }if($getMagazineAdv['articles'][4]['article_link']!=''){ 
                                //$link = explode("http://www.", $getMagazineAdv['articles'][4]['article_link']);                               
                                $link = str_replace(array('http://','https://','www.'), '', $getMagazineAdv['articles'][4]['article_link']); 
                                $link_name = explode('.', $link);
                                 echo $this->lang->line("source_name").": ". substr($link_name[0], 0, 25) . "";  
                                ?>                         
                            
                            <?php } ?>
                        </div>
                        
                        <p><?php echo substr($getMagazineAdv['articles'][4]['description_without_html'],0,150); ?>
                            <?php if ($getMagazineAdv['articles'][4]['article_link'] != '') { ?>
                                    <a href="<?php echo $externalLink5; ?>" target="_blank"><?php echo $this->lang->line("more"); ?>..</a>
                                <?php }else{ ?>
                        <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=<?php echo $getMagazineAdv['articles'][4]['id']; ?>&magazineId=<?php echo $_GET['magazineId']; ?>">
                            <?php if($getMagazineAdv['articles'][4]['description_without_html'] !=""){ echo $this->lang->line("more");?>.. <?php } ?></a>
                                <?php } ?>
                        </p>
                        </section>
                        
                    </div>
                </div>
            </div>
            <div class="pagination clearfix">
                 <?php echo $this->pagination->create_links();?>
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
        
        

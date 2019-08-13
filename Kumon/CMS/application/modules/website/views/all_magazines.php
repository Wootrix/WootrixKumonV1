<script>
    function getSearchKeyword(data,seg) {
        var segValue = $("#segValue").val();
        
        $("#searchRes").show();
        $(document).ready(function () {
            $.ajax(
                    {
                        url: "<?php echo $this->config->base_url(); ?>index.php/wootrix-search-magzine",
                        type: "POST",
                        data: {keyValue: data,keySeg: segValue},
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

        
       var getValues =  $("#getValue").val(title);
       
       
        $("#searchRes").hide();
        window.location.href = "<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?articleId=" + ID+"&searchId=search";
    }
</script> 
 <?php 
$articleObj = $this->load->model("webservices/new_articles_model");
$articleObj = new new_articles_model();

$articleObj->set_token($this->session->userdata("user_id"));
$articleObj->set_language_name("en");
$getLangId = $articleObj->getLanguageId();
if($this->session->userdata('languages')!=''){
$articleObj->set_language_id(ltrim($this->session->userdata('languages'),","));    
}else{
$articleObj->set_language_id($getLangId['id']);
}
$getUserMagzines = $articleObj->getUserArticles();


//echo "<pre>";print_r($getUserMagzines);
?>   
    
</head>
    <body>
        
        
        <div class="container clearfix">

            <div class="all-magzine clearfix">
                <div class="heading-container clearfix">
                    <h4><?php echo strtoupper($this->lang->line("Magazines")); ?></h4>
                </div>
                
                
                <div class="magazines-list">
                    <ul>
                        <?php if($magazineList != '') { ?>
                        <?php foreach($magazineList as $m) { ?>
                        <li>
                            <figure><img src="<?php echo $this->config->base_url(); ?>assets/Magazine_cover/<?php echo $m['cover_image']; ?>" alt=""/>
                                 <div class="magazine-details">
                                     <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-list-layout?magazineId=<?php echo $m['id']; ?>"><?php echo $this->lang->line("view_details_web"); ?></a>
                                </div>
                            </figure>
                            
                            <div class="bottom-content">
                                <h5><?php echo $m['title']; ?> (<?php echo $m['totalArticle']; ?> <?php echo $this->lang->line("articles_web"); ?>)</h5>                                
                                <span><?php echo date("d-M-Y",  strtotime($m['publish_date_from'])); ?> <?php echo $this->lang->line("to_web"); ?> <?php echo date("d-M-Y",  strtotime($m['publish_date_to'])); ?></span>
                            </div>
                        </li>
                        <?php } }else{  ?>
                        <?php foreach($getUserMagzines as $m) { ?>
                        <li>
                            <figure><img src="<?php echo $this->config->base_url(); ?>assets/Magazine_cover/<?php echo $m['cover_image']; ?>" alt=""/>
                                 <div class="magazine-details">
                                     <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-list-layout?magazineId=<?php echo $m['id']; ?>"><?php echo $this->lang->line("view_details_web"); ?></a>
                                </div>
                            </figure>
                            
                            <div class="bottom-content">
                                <h5><?php echo $m['title']; ?></h5>
<!--                                <span>--><?php //echo date("d-M-Y",  strtotime($m['publish_date_from'])); ?><!-- --><?php //echo $this->lang->line("to_web"); ?><!-- --><?php //echo date("d-M-Y",  strtotime($m['publish_date_to'])); ?><!--</span>-->
                            </div>
                        </li>
                        <?php } }?>
                    
                    </ul>
                
                </div>
                
            </div>
            
            

        
        </div>
        
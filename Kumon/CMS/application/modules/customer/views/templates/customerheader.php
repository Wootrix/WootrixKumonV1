<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Admin panel developed with the Bootstrap from Twitter.">
<meta name="author" content="dev" >

<title>::Wootrix::</title>
<link href="<?php echo $this->config->base_url();?>css/style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $this->config->base_url();?>css/simplePagination.css" rel="stylesheet" type="text/css">
<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<!--<script src="<?php echo $this->config->base_url();?>js/jquery.js"></script>-->
<script type="text/javascript" src="<?php echo $this->config->base_url();?>jss/jquery.latest.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->base_url();?>jss/google-charts.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->base_url();?>jss/jquery.simplePagination.js"></script>

 <script>
     $(document).ready(function(){
      $(".my-account").click(function(){
    $(".login-detail-box").toggle();
  });
  $(".close-bttn, .layer-bg-popupOverlay").click(function() {
            $(".layer-bg").fadeOut();
        });
         });
</script>
    
</head>

<body>
    <?php
    $langcode=$this->session->userdata('language');;
    ?>
   <?php //echo"<pre>"; print_r($this->session->userdata); die;
    $user_id = $this->session->userdata['user_id'];
    $sql = $this->db->query("SELECT name,user_name,image FROM tbl_customer WHERE id= $user_id");
    $data = $sql->row_array();
    $user_name = $data['name'];
    $image = $data['image'];    
    if($image == ""){
        $image= $this->config->base_url().'images/profile-pic.png';
    }else{
        $image= $this->config->base_url().'assets/customer_img/'.$image;
    }
?>
    
    <header class="clearfix">
        <div class="fix-container clearfix">
            <div class="profile-option">
                <div class="my-profile">
                    <span><?php echo $user_name; ?></span>
                    <div class="image-cont"><img src="<?php echo $image; ?>" alt=""/></div>
                </div>
                <div class="settings"><span class="my-account"><img src="<?php echo $this->config->base_url();?>images/setting-icon.png" alt=""/></span>
                
                    <div class="login-detail-box">
                        <ul>
                            <li><a href="<?php echo $this->config->base_url();?>index.php/customerdashbord"><?php echo $this->lang->line('Dashboard') ?></a></li>
                            <li><a href="<?php echo $this->config->base_url();?>index.php/customereditprofile"><?php echo $this->lang->line('Edit_Profile') ?></a></li>
                             <li><a href="javascript:void(0)" onclick="showLangForm()"><?php echo $this->lang->line('change_language') ?></a></li>
                             <li><a href="<?php echo $this->config->base_url();?>index.php/customeradvertiselisting"><?php echo $this->lang->line('Advertisements') ?></a></li>
                            <li><a href="<?php echo $this->config->base_url();?>index.php/customer_logout"><?php echo $this->lang->line('Logout') ?></a></li>
                        </ul>
                          <div class="arrow-up"></div>
                    </div>
                </div>
            </div>
                <div class="dashboard-logo"><a href="customerdashbord"><img src="<?php echo $this->config->base_url();?>images/logo.png" alt="" /></a></div>
            
        </div>
        <div class="layer-bg showLangForm">
    <div class="popup-container clearfix">
        <div class="popup-inner"><h4><?php echo $this->lang->line('Select_Language') ?></h4></div>
        <div class="close-bttn" ></div>        
        <div class="popup-inner clearfix">
           <?php $attributes = array('name' => 'change_password', 'id' => 'change_password', 'class' => 'form-horizontal');
           echo form_open_multipart($this->config->base_url() . 'index.php/selectcustomerlanguage', $attributes);
           ?>  
         <?php //echo"<pre>"; print_r($data_result); ?>         
            <div class="select-container">
                <div class="select-arrow">
                <div class="arrow-down"></div>
                </div>
                <?php $sql= $this->db->query("SELECT language,language_code FROM tbl_language WHERE status='1'");
                    $language_result = $sql->result_array();
                ?>
                <select class="choose-lang" name="lang" id="lang">
                <?php 
                    foreach ($language_result as $state) {?>
                    <option value="<?php echo $state['language_code'];?>" <?php if($state['language_code']==$langcode){?> selected="selected" <?php }?>><?php echo $state['language'];?></option>
                   <?php  }
                    ?>
                </select>                
            </div><div>
            <input type="hidden" name="redirectPage" value="<?php echo $this->uri->segment(1); ?>">
            <input type="submit" value="save" name ="save" /></div>
        </div>    
       
    </div>
    </div>
    </header>
    <?php
    $langcode=$this->session->userdata('language');;
    ?>
    
<?php echo form_close(); ?>

        <script>
        function showLangForm(){
            $('.showLangForm').show();
        }
        </script>
    
    
    
    
    
    
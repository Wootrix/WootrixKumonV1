<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Admin panel developed with the Bootstrap from Twitter.">
<meta name="author" content="dev" >

<title>::Wootrix::</title>
<link href="<?php echo $this->config->base_url();?>css/style.css" rel="stylesheet" type="text/css">
<!--<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>-->
<!--<script src="<?php echo $this->config->base_url();?>js/jquery.js"></script>-->
<script type="text/javascript" src="<?php echo $this->config->base_url();?>jss/jquery.latest.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->base_url();?>jss/google-charts.js"></script>

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
    <?php //echo"<pre>"; print_r($this->session->userdata); die;
    $user_id = $this->session->userdata['id'];
    $sql = $this->db->query("SELECT name,user_name,image,permission6 FROM tbl_admin WHERE id= '$user_id'");
    $data = $sql->row_array();
    $user_name = $data['name'];
    $image = $data['image'];
    $permission = $data['permission6'];  
    if($image == ""){
        $image= $this->config->base_url().'images/profile-pic.png';
    }else{
        $image= $this->config->base_url().'assets/customer_img/'.$image;
    }
?>
    <?php
    $langcode=$this->session->userdata('language');;
    ?>
    
    <header class="clearfix">
        <div class="fix-container clearfix">
            <div class="profile-option">
                <div class="my-profile">
                    <span><?php echo $user_name; ?></span>
                    <div class="image-cont"><img src="<?php echo $image ?>" alt=""/></div>
                </div>
                <div class="settings"><span class="my-account"><img src="<?php echo $this->config->base_url();?>images/setting-icon.png" alt=""/></span>
                
                    <div class="login-detail-box">
                        <ul>
                            <li><a href="<?php echo $this->config->base_url();?>index.php/superadmin"><?php echo $this->lang->line('Dashboard') ?></a></li>
                            <li><a href="<?php echo $this->config->base_url();?>index.php/admineditprofile"><?php echo $this->lang->line('Edit_Profile') ?></a></li>
                            <?php if($this->session->userdata['role'] == '1'){ ?>
                            <li><a href="<?php echo $this->config->base_url();?>index.php/advertiselisting"><?php echo $this->lang->line('Advertisements') ?></a></li>
                            <?php } ?>
                            <?php if($permission == '1' || $this->session->userdata['role'] == '1'){ //echo"hello"; ?> 
                             <li><a href="javascript:void(0)" onclick="showLangForm()"><?php echo $this->lang->line('change_language') ?></a></li>
                            <?php } ?>
                            <li><a href="<?php echo $this->config->base_url();?>index.php/logout"><?php echo $this->lang->line('Logout') ?></a></li>
                        </ul>
                          <div class="arrow-up"></div>
                    </div>
                </div>
            </div>
            <div class="dashboard-logo">
            <?php if($this->session->userdata['role'] == '1'){ ?>
            <a href="superadmin"><img src="<?php echo $this->config->base_url();?>images/logo.png" alt="" /></a>
            <?php } else { ?>
            <a href="subadmin"><img src="<?php echo $this->config->base_url();?>images/logo.png" alt="" /></a>
            <?php } ?>
            </div>    
                    </div>
        
        <div class="layer-bg showLangForm">
    <div class="popup-container clearfix">
        <div class="popup-inner"><h4><?php echo $this->lang->line('Select_Language') ?></h4></div>
        <div class="close-bttn"></div>        
        <div class="popup-inner clearfix">
           <?php $attributes = array('name' => 'change_password', 'id' => 'change_password', 'class' => 'form-horizontal');
           echo form_open_multipart($this->config->base_url() . 'index.php/selectlanguage', $attributes);
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
    
<?php echo form_close(); ?>
 <script>
        function showLangForm(){
            $('.showLangForm').show();
        }
        </script>
    
    
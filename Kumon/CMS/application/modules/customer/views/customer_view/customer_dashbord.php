
 <script>
    $(document).ready(function() {
        $(".language").click(function() {
            $(".layer-bg").fadeIn();
        });

        $(".close-bttn").click(function() {
            $(".layer-bg").fadeOut();
        });
    });    
   
   
    
    function LanguageData(val)
    {      var msg = '';
            $.ajax({
                type: "POST",
                data: {val: val},
                dataType: "html",
                url: "<?php echo $this->config->base_url(); ?>index.php/selectcustomerlanguage",
                success: function(data)
                { //alert(data);
                    //console.log(data);
                    location.reload();
                }
            });
    }
    
    
    
 </script>
 
 <?php //echo"<pre>"; print_r($this->session->userdata);
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
 
<div class="subheader">
    <div class="fix-container clearfix">
        <h4><?php echo $this->lang->line('Customer'); ?></h4>

    </div>
</div>
<div class="fix-container clearfix"> <?php //echo $this->config->base_url().'assets/customer_img/'.$this->session->userdata['image']; ?>
    <div class="super-admin-cont">
        <div class="admin-profile clearfix">         
            <div class="profile-image-cont"><img src="<?php echo
            $image; ?>" alt="" /></div>
                 
            

            <h4><?php echo $user_name; ?></h4>
            <h5><?php echo $this->lang->line('Customer'); ?></h5>

        </div>

        <div class="categories">
            <ul>

                <li>
                    <a href="<?php echo $this->config->base_url(); ?>index.php/customer_magazinelist">
                        <span><img src="<?php echo $this->config->base_url(); ?>images/icon-5.png" alt="" /></span>
                        <strong><?php echo "Revistas"; ?></strong>
                        <?php if($revied_mag_article > 0){?>
                        <div class="notification"><?php echo$revied_mag_article; ?></div>
                        <?php } ?>
                    </a>
                </li>



                <li>
                    <a class="language" href="#">
                        <span><img src="<?php echo $this->config->base_url(); ?>images/icon-7.png" alt="" /></span>
                        <strong><?php echo $this->lang->line('change_language'); ?></strong>
                    </a>
                </li>
                
                <li>
                    <a href="<?php echo $this->config->base_url(); ?>index.php/customeradvertiselisting">
                        <span><img src="<?php echo $this->config->base_url(); ?>images/icon-4.png" alt="" /></span>
                        <strong><?php echo $this->lang->line('Advertisements'); ?></strong>
                        <?php if($revied_ads > 0){?>
                        <div class="notification"><?php echo$revied_ads; ?></div>
                        <?php } ?>
                    </a>
                </li>

                <li>
                    <a href="<?php echo $this->config->base_url();?>index.php/customer_notifications">
                        <span><img src="<?php echo $this->config->base_url();?>images/icon-9.png" alt="" /></span>
                        <strong><?php echo $this->lang->line('Push_Notification'); ?></strong>
                    </a>
                </li>

                <li>
                    <a href="<?php echo $this->config->base_url(); ?>index.php/closed_article_report">
                        <span><img src="<?php echo $this->config->base_url(); ?>images/icon-6.png" alt=""/></span>
                        <strong><?php echo $this->lang->line('access_use_report_title') ?></strong>
                    </a>
                </li>

            </ul>


        </div>


    </div>

</div>
 <?php
    $langcode=$this->session->userdata('language');;
    ?>
<div class="layer-bg">
    <div class="popup-container clearfix">
        <div class="popup-inner"><h4><?php echo $this->lang->line('Select_Language') ?></h4></div>
        <div class="close-bttn"></div>        
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
<?php echo form_close(); ?>



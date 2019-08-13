<!doctype html>
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
                url: "<?php echo $this->config->base_url(); ?>index.php/selectlanguage",
                success: function(data)
                { //alert(data);
                    //console.log(data);
                    location.reload();
                }
            });
    }

 </script>
 
 <?php //echo"<pre>"; print_r($this->session->userdata);
    $user_id = $this->session->userdata['id'];
    $sql = $this->db->query("SELECT name,user_name,image FROM tbl_admin WHERE id= $user_id");
    $data = $sql->row_array();
    $user_name = $data['name'];
    $image = $data['image'];    
    if($image == ""){
        $image= $this->config->base_url().'images/profile-pic.png';
    }else{
        $image= $this->config->base_url().'assets/customer_img/'.$image;
    }
?>
 <?php
    $langcode=$this->session->userdata('language');;
    ?>
 
<?php //echo"<pre>"; print_r($this->session->userdata);  ?>
    <div class="subheader">
        <div class="fix-container clearfix">
            <h4><?php echo $this->lang->line('Super_Admin') ?></h4>
        
        </div>
    </div>
    
    <div class="fix-container clearfix">
        <div class="super-admin-cont">
            <div class="admin-profile clearfix">            
            <div class="profile-image-cont"><img src="<?php echo$image; ?>" alt="" /></div>
            <h4><?php echo $user_name; ?></h4>
            <h5><?php echo $this->lang->line('Super_Admin'); ?></h5>

        </div>
            
            <div class="categories">
                <ul>
<!--                    <li>-->
<!--                         <a href="--><?php //echo $this->config->base_url();?><!--index.php/catagory">-->
<!--                        <span><img src="--><?php //echo $this->config->base_url();?><!--images/icon-1.png" alt="" /></span>-->
<!--                        <strong>--><?php //echo $this->lang->line('Categories') ?><!--</strong>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a href="--><?php //echo $this->config->base_url();?><!--index.php/groups">-->
<!--                            <span><img src="--><?php //echo $this->config->base_url();?><!--images/icon-3.png" alt="" /></span>-->
<!--                            <strong>Grupo de Usuários</strong>-->
<!--                        </a>-->
<!--                    </li>-->
                     <li>
                          <a href="<?php echo $this->config->base_url();?>index.php/admin">
                        <span><img src="<?php echo $this->config->base_url();?>images/icon-2.png" alt="" /></span>
                        <strong><?php echo $this->lang->line('Admins') ?></strong>
                         </a>
                    </li>
                     <li>
                          <a href="<?php echo $this->config->base_url();?>index.php/customers">
                        <span><img src="<?php echo $this->config->base_url();?>images/icon-3.png" alt="" /></span>
                        <strong><?php echo $this->lang->line('Manage_Customers') ?></strong>
                         </a>
                    </li>
                     <li>
                        <a href="<?php echo $this->config->base_url();?>index.php/advertiselisting">
                        <span><img src="<?php echo $this->config->base_url();?>images/icon-4.png" alt="" /></span>
                        <strong><?php echo $this->lang->line('Advertisements') ?></strong>
                        <?php if($mag_review_ads >0){ ?>
                        <div class="notification"><?php echo$mag_review_ads; ?></div>
                        <?php } ?>
                         </a>
                    </li>
                    
                     <li>
                        <a href="<?php echo $this->config->base_url();?>index.php/magazinelist">
                        <span><img src="<?php echo $this->config->base_url();?>images/icon-5.png" alt="" /></span>
                        <strong><?php echo "Revistas"; ?></strong>
                        <?php if($mag_review_article >0){ ?>
                        <div class="notification"><?php echo$mag_review_article; ?></div>
                        <?php } ?>
                         </a>
                    </li>
                    
                     <li>
                        <a href="<?php echo $this->config->base_url();?>index.php/openarticlelist">
                        <span><img src="<?php echo $this->config->base_url();?>images/icon-6.png" alt="" /></span>
                        <strong><?php echo "Artigos" ?></strong>
                         <?php if($all_new_ads >0){ ?>
                         <div class="notification"><?php echo$all_new_ads; ?></div>
                         <?php }?>
                         </a>

                    </li>
                    
                     <li>
                         <a class="language" href="#">
                        <span><img src="<?php echo $this->config->base_url();?>images/icon-7.png" alt="" /></span>
                        <strong><?php echo $this->lang->line('change_language') ?></strong>
                         </a>
                    </li>
                    
                     <li>
                         <a href="<?php echo $this->config->base_url();?>index.php/history">
                        <span><img src="<?php echo $this->config->base_url();?>images/icon-8.png" alt="" /></span>
                        <strong><?php echo $this->lang->line('History') ?></strong>
                         <?php if($mag_del_count >0){ ?>
                         <div class="notification"><?php echo$mag_del_count; ?></div>
                         <?php } ?>
                         </a>
                    </li>
                    
                    <li>
                         <a href="<?php echo $this->config->base_url();?>index.php/notifications">
                        <span><img src="<?php echo $this->config->base_url();?>images/icon-9.png" alt="" /></span>
                        <strong><?php echo $this->lang->line('Push_Notification'); ?></strong></a>
                    </li>

<!--                    <li>-->
<!--                        <a href="--><?php //echo $this->config->base_url(); ?><!--index.php/public_article_report">-->
<!--                            <span><img src="--><?php //echo $this->config->base_url(); ?><!--images/icon-6.png" alt=""/></span>-->
<!--                            <strong>--><?php //echo $this->lang->line('access_public_article_report_title') ?><!--</strong>-->
<!--                        </a>-->
<!--                    </li>-->
<!---->
                    <li>
                        <a href="<?php echo $this->config->base_url(); ?>index.php/closed_article_report">
                            <span><img src="<?php echo $this->config->base_url(); ?>images/icon-6.png" alt=""/></span>
                            <strong>Relatório</strong>
                        </a>
                    </li>
<!---->
<!--                    <li>-->
<!--                        <a href="--><?php //echo $this->config->base_url(); ?><!--index.php/deepLink">-->
<!--                            <span><img src="--><?php //echo $this->config->base_url(); ?><!--images/icon-6.png" alt=""/></span>-->
<!--                            <strong>Deep Link</strong>-->
<!--                        </a>-->
<!--                    </li>-->
<!---->
<!--                    <li>-->
<!--                        <a href="--><?php //echo $this->config->base_url(); ?><!--index.php/campaignLayoutList">-->
<!--                            <span><img src="--><?php //echo $this->config->base_url(); ?><!--images/icon-6.png" alt=""/></span>-->
<!--                            <strong>Layout de Campanha de Cliente</strong>-->
<!--                        </a>-->
<!--                    </li>-->

                </ul>
            
            
            </div>
            
            
        </div>
        
    </div>
    

<!--Pop up-->


<div class="layer-bg">
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
<?php echo form_close(); ?>
    
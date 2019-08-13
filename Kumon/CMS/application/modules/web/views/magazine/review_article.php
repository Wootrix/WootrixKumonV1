<script src="js/jquery.js"></script>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<!-- Load jQuery and the validate plugin -->  
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

<!-- Define some CSS -->


<script>
    $(document).ready(function() {
        $(".rejected-btn").click(function() {
            $(".layer-bg").fadeIn();
        });

        $(".close-bttn, .layer-bg-popupOverlay").click(function() {
            $(".layer-bg").fadeOut();
        });
    });
</script>

</head>

<div class="subheader">
    <div class="fix-container clearfix">
        <h4><?php echo$this->lang->line('Magazine_Name').": ". $data_result['mag_title']; ?></h4> 
    </div>
</div>


<div class="fix-container clearfix">
    <div class="addvertisement-wrapper clearfix">
        <?php //echo "<pre>"; print_r($data_result); ?>  
        <div class="advertisment-image">
              <?php 
              $url = parse_url($data_result['image']);
              if($url['scheme']=='http' || $url['scheme']=='https'){?>
              <img src = "<?php echo $data_result['image']; ?>" />
              <?php }else{
              ?>
             <img src = "<?php echo $this->config->base_url() . 'assets/Article/thumbs/'. $data_result['image']; ?>" />
              <?php }?>
        </div>
    </div>
        <div style="color: red"><p><?php echo $msg;  echo $this->session->flashdata("msg");?>   </p>
                    <?php echo validation_errors(); ?></div>
        <div class="addvertisment-details">
            <div class="fields-cont clearfix"><label><?php echo $this->lang->line('Title') ?></label> <span> <?php echo $data_result['title']; ?></span> </div>
            <div class="fields-cont clearfix"><label><?php echo $this->lang->line('Writer_Name') ?></label> <span> <?php echo $data_result['name']; ?></span> </div>
            <div class="fields-cont clearfix"><label><?php echo $this->lang->line('Publish_From') ?></label> <span> <?php echo $data_result['publish_from']; ?></span> </div>
             <div class="fields-cont clearfix"><label><?php echo $this->lang->line('Publish_To') ?></label> <span> <?php echo $data_result['publish_to']; ?></span> </div>
            
            <div class="fields-cont clearfix"><label><?php echo $this->lang->line('Categories') ?></label> <span>  <?php
                    $name = array();
                    foreach ($data_result['categoy_name'] as $cn) {
                        $name[] = $cn['category_name'];
                    }
                    echo implode(',', $name);
                    ?></span>
            </div>
            
            <div class="fields-cont clearfix"><label><?php echo $this->lang->line('Tags') ?></label> <span> <?php echo $data_result['tags']; ?></span> </div>
            
            
           
            <div class="fields-cont clearfix">
                   <label><?php echo $this->lang->line('Description') ?></label></div>
                   <div class="">
                   <span> <?php echo $data_result['description']; ?></span>
            </div> 
            <div class="fields-cont clearfix">
            <!--a class="aprove-btn" href="#" onclick="Approve(<?php //echo $value['id']; ?>, '<?php //echo "Approve"; ?>');">Approve</a-->
            <a class="rejected-btn" href="#"><?php echo $this->lang->line('Decline') ?></a>
            <a class="aprove-btn" href="<?php echo $this->config->base_url();?>index.php/approvemagazarticle?rowId=<?php echo $data_result['id']; ?>&magId=<?php echo $_GET['magId']; ?>&source=<?php echo $_GET['source']; ?>">Approve</a>
            </div>
        </div>

    
</div>
</div>

<!--Pop up-->


<div class="layer-bg">
    <div class="layer-bg-popupOverlay"></div>
    <div class="popup-container clearfix">
        <div class="close-bttn"></div>
        <h4><?php echo $this->lang->line('Rejection_Report') ?></h4>
        <?php
        $attributes = array('name' => 'publish_form', 'id' => 'publish_form', 'class' => 'form-horizontal');
        echo form_open($this->config->base_url() . 'index.php/rejectmagazinearticle?rowId='.$data_result['id'].'&magId='.$_GET['magId'], $attributes);
        ?>  
        <div style="color: red"><p><?php echo $msg;  echo $this->session->flashdata("msg");?>   </p>
                    <div style="color: green"><p><?php echo $msg;  echo $this->session->flashdata("sus_msg");?>   </p></div>
                    <?php echo validation_errors(); ?></div>
        <div class="popup-inner clearfix">
            <h5><?php echo $this->lang->line('Article_Title') ?>:  <?php echo $data_result['title']; ?></h5>
       <div class="input-box">
            
            <textarea id="txtArea" name="rejection_report" value=""></textarea>
            <input type="hidden" name="article_id" id="advertise_id" value="<?php echo $data_result['id']; ?>">            
            <input class="button-change" type="submit" value="Send" name="send" />
        </div>
    </div>        
</div>
</div>
<?php echo form_close(); ?>

<script>
    // validation
    jQuery.validator.setDefaults({
        debug: false,
        success: "valid"
    });

    $("#publish_form").validate({
        rules: {
            rejection_report: {
                required: true
            }
        }

    });
</script>
</body>
</html>
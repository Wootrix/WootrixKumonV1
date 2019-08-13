<script src="js/jquery.js"></script>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<!-- Load jQuery and the validate plugin -->  
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

<!-- Define some CSS -->
<style type="text/css">
    .label {width:100px;text-align:right;float:left;padding-right:10px;font-weight:bold;}
    add_subadmin.error, .output {color:#FFFFFF;font-weight:bold;}
    .googlemapimage{float: left; width: 100%; overflow: hidden; height: 500px; margin-top: 10px;}
</style>


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
        <h4><?php echo $this->lang->line('Advertisements') ?></h4>
    </div>
</div>


<div class="fix-container clearfix">
    <div class="addvertisement-wrapper clearfix">
        <?php //echo "<pre>"; print_r($ad_result); ?>  
        <div class="advertisment-image">
            <?php if($ad_result['media_type'] =='2') {
                if($ad_result['cover_image']=='embed'){
                    echo $ad_result['embed_video'];
                }else{
                ?>
            <img src =" <?php echo $this->config->base_url() . 'assets/Advertise/thumbs/' . $ad_result['cover_image']."demo.jpeg"; ?>">
                <?php }}else{?>
            <img src =" <?php echo $this->config->base_url() . 'assets/Advertise/' . $ad_result['cover_image']; ?>">
            <?php } ?>
        </div>
        <div class="addvertisment-details">
            <div class="fields-cont clearfix"><label><?php echo $this->lang->line('Link') ?></label> 
                <span> <?php echo $ad_result['link']; ?></span> </div>
            <div class="fields-cont clearfix"><label><?php echo $this->lang->line('Magazine') ?></label> 
                <span>  <?php
                    $name = array();
                    foreach ($ad_result['categoy_name'] as $cn) {
                        $name[] = $cn['title'];
                    }
                    echo implode(',', $name);
                    ?></span>
            </div>
            <div class="fields-cont clearfix"><label><?php echo $this->lang->line('Status') ?></label> 
                <span> <?php
                    if ($ad_result['status'] == '0') {
                        echo"new";
                    } elseif ($ad_result['status'] == '1') {
                        echo"ForReview";
                    }
                    ?></span>
            </div>
            <div class="fields-cont clearfix"><label><?php echo $this->lang->line('Publish_Date_From') ?></label>
                <span><?php
                if ($ad_result['publish_date_from'] != "") {
                    echo date('Y:m:d', strtotime($ad_result['publish_date_from'])) . " To " . date('Y:m:d', strtotime($ad_result['publish_date_to']));
                }
                ?></span>
            </div>
            <div class="fields-cont clearfix">
            <!--a class="aprove-btn" href="#" onclick="Approve(<?php //echo $value['id']; ?>, '<?php //echo "Approve"; ?>');">Approve</a-->
           <a class="rejected-btn" href="<?php echo $this->config->base_url();?>index.php/decline_magazi_advertise?rowId=<?php echo $ad_result['id']; ?>">Decline</a>
           <a class="aprove-btn" href="<?php echo $this->config->base_url();?>index.php/approve_magazi_advertise?rowId=<?php echo $ad_result['id']; ?>">Approve</a>
           
           <!-a class="rejected-btn" href="#"><?php //echo $this->lang->line('Decline') ?></a-->
            </div>
            
            
            
            <!--Pop up-->


<div class="layer-bg">
    <div class="layer-bg-popupOverlay"></div>
    <div class="popup-container clearfix">
        <div class="close-bttn"></div>
        <h4><?php echo $this->lang->line('Rejection_Report') ?></h4>
        <?php
        $attributes = array('name' => 'publish_form', 'id' => 'publish_form', 'class' => 'form-horizontal');
        echo form_open($this->config->base_url() . 'index.php/rejectmagazinearticle', $attributes);
        ?>  
        <?php echo validation_errors(); ?>
        <div class="popup-inner clearfix">
            <div class="select-container">
                <div class="select-arrow">
                    <div class="arrow-down"></div>
                </div>             
            </div> 
        </div>
        <table>
            <tr><textarea id="txtArea" name="rejection_report" value=""></textarea></tr>
            <input type="hidden" name="article_id" id="advertise_id" value="<?php echo $data_result['id']; ?>">            
        </table>

        <div class="popup-inner clearfix">
            <input type="submit" value="Send" name="send" />
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
            txtArea: {
                required: true
            }
        }

    });
</script>
            
</body>
</html>
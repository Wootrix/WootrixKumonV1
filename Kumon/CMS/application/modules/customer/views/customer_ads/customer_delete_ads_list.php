<script src="js/jquery.js"></script>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<!-- Load jQuery and the validate plugin -->  
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

<!-- Define some CSS -->
<style type="text/css">
    .label {width:100px;text-align:right;float:left;padding-right:10px;font-weight:bold;}
    add_subadmin.error, .output {color:#FFFFFF;font-weight:bold;}
</style>


<script>
    $(document).ready(function() {
        $(".review").click(function() {
            $(".layer-bg").fadeIn();
        });

        $(".close-bttn, .layer-bg-popupOverlay").click(function() {
            $(".layer-bg").fadeOut();
        });
    });
</script>

<script>
     /* GET LANGUAGE ID AND FETCH LANGUAGE DATA*/

    function LanguageData(val,catId,langId) {
        window.location.href="<?php echo base_url();?>index.php/customerDeletedarticle?catagory="+catId+"&lang="+val;
    }

  /* SORT CATAGORY DATA*/
  
   function CatagoryData(val,catId,langId) {
        var rowId = val;//      
        window.location.href="<?php echo base_url();?>index.php/customerDeletedarticle?catagory="+val+"&lang="+langId;
        //$('#catagory_form').submit();
    }
  
  

    /*PERFORMING DELETE < EDIT AND OTHER OPERATION*/

    function DELETE(val) {
        var tid1 = val;
        //alert(tid1);
        var disable = confirm('<?php echo $this->lang->line("Are_you_sure_want_to_delete_this_record"); ?>');
        if (disable === true)
        {
        $.ajax({
            type: "POST",
            data: {Id: tid1},
            url: "<?php echo $this->config->base_url(); ?>index.php/customeradvertisedelete",
            success: function(data)
            {
                //console.log(data);
                location.reload();
                //$(".popUpDiv").html(data);
            }

        });

    }}
    /*Permanently delete from system*/
    function SHIFTDELETE(val) {
        var tid1 = val;
        //alert(tid1);
        var disable = confirm('<?php echo $this->lang->line("Are_you_sure_want_to_perdelete_this_record"); ?>');
        if (disable === true)
        {
        $.ajax({
            type: "POST",
            data: {Id: tid1},
            url: "<?php echo $this->config->base_url(); ?>index.php/customerAdsShiftdelete",
            success: function(data)
            {
                //console.log(data);
               location.reload();
            }

        });

    }}
    /*Restore Ads*/
    function RESTORE(val) {
        var tid1 = val;
        //alert(tid1);
        var disable = confirm('<?php echo $this->lang->line("Are_you_sure_want_to_restore_this_record"); ?>');
        if (disable === true)
        {
        $.ajax({
            type: "POST",
            data: {Id: tid1},
            url: "<?php echo $this->config->base_url(); ?>index.php/restoreCustomerads",
            success: function(data)
            {
                //console.log(data);
                location.reload();
            }

        });

    }}
    
    /*SEND FOR REVIEW STATE*/
    function SENDFORREVIEW(val) {
        var tid1 = val;
        //alert(tid1);
        $.ajax({
            type: "POST",
            data: {Id: tid1},
            url: "<?php echo $this->config->base_url(); ?>index.php/sendAdsforreview",
            success: function(data)
            {
                //console.log(data);
                location.reload();
            }

        });

    }






</script>

</head>

<?php
$lang = $_GET['lang'];
$catagory = $_GET['catagory'];
?>

<div class="subheader">
    <div class="fix-container clearfix">
        <h4><?php echo $this->lang->line('Advertisements'); ?></h4>
        <a class="add-new" href="<?php echo $this->config->base_url() . 'index.php/addcustomeradvertise' ?>">Add New</a>

    </div>
</div>

<nav class="clearfix">
    <div class="fix-container clearfix">
        <ul>
            <li><a href="customeradvertiselisting"><?php echo $this->lang->line('All') ?> <span class="blue"><?php echo $all_advertise; ?></span></a></li>
            <li><a href="customerPublishedarticle"><?php echo $this->lang->line('Published') ?> <span class="green"><?php echo $all_publish_advertise; ?></span></a></li>
            <li><a href="customerDraftedarticle"><?php echo $this->lang->line('Draft') ?> <span class="yellow"><?php echo $all_drafted_advertise; ?></span></a></li>
            <li class="active"><a href="customerDeletedarticle"><?php echo $this->lang->line('Deleted') ?> <span class="red"><?php echo $all_deleted_advertise; ?></span></a></li>
            <li><a href="customerReviewarticle"><?php echo $this->lang->line('Review') ?> <span class="dark-green"><?php echo $all_review_advertise; ?></span></a></li>
        </ul>
    </div>
</nav>


<div class="fix-container clearfix">
<!--    <div class="upper_bar clearfix filter-bar">


        <div class="search-bar right-align-box">    
            <div class="language-filter clearfix">
                <div class="select-container">
                    <div class="select-arrow">
                        <div class="arrow-down"></div>
                    </div>    
                    <form id="languge_form" action="<?php echo $this->config->base_url() ?>index.php/customerDeletedarticle" method="POST">
                        <select class="choose-lang" onChange="LanguageData(this.value,'<?php echo $_GET['catagory'] ?>','<?php echo $_GET['lang'] ?>');" name="lang" id="lang">
                            <option value="" name=""><?php echo $this->lang->line('Select_Language') ?></option>
                            <?php foreach ($language_result as $state) { ?>
                                <option value="<?php echo $state['id']; ?>" <?php if ($state['id'] == $lang) { ?> selected="selected" <?php } ?>><?php echo $state['language']; ?></option>
                            <?php }
                            ?>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <div class="search-bar right-align-box">    
            <div class="language-filter clearfix">
                <div class="select-container">
                    <div class="select-arrow">
                        <div class="arrow-down"></div>
                    </div> 
                    <form id="catagory_form" action="<?php echo $this->config->base_url() ?>index.php/customerDeletedarticle" method="POST">
                        <select class="choose-lang" onChange="CatagoryData(this.value,'<?php echo $_GET['catagory'] ?>','<?php echo $_GET['lang'] ?>');" name="catagory" id="catagory">
                            <option value="" name=""><?php echo $this->lang->line('Select_Magazine') ?></option>
                            <?php foreach ($magazine_list as $state) { ?>
                                    <option value="<?php echo $state['id']; ?>" <?php if ($state['id'] == $catagory) { ?> selected="selected" <?php } ?>><?php echo $state['title']; ?></option>
                                <?php }
                                ?>
                        </select>
                    </form>
                </div>
            </div>
        </div>


    </div>-->

    <div class="category-list">
        <div class="table-container">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <!--th><input type="checkbox" /></th-->
                    <th><?php echo $this->lang->line('Banner') ?></th>
                    <th><?php echo $this->lang->line('Type') ?></th>
                    <th><?php echo $this->lang->line('Size') ?></th>
                    <th><?php echo $this->lang->line('Link') ?></th>
                    <th><?php echo $this->lang->line('Magazine') ?></th>
                    <th><?php echo $this->lang->line('Status') ?></th>
                    <th><?php echo $this->lang->line('Report') ?></th>
                    <th class="action-colm"><?php echo $this->lang->line('Action') ?></th>
                </tr>
                <?php //echo"<pre>"; print_r($data_result); die; ?>
                <tr> <?php
                    if ($data_result != "") {
                        foreach ($data_result as $value) {
                            ?> 
                            <td>
                                <?php if ($value['media_type'] == '1') { ?>
                                    <div class="banner-cont"><img src =" <?php echo $this->config->base_url() . 'assets/Advertise/thumbs/' . $value['cover_image']; ?>" style="height:80px;width:80px"></div>
                                <?php } else {
                                    if($value['cover_image']=='embed'){
                                        preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $value['embed_video'], $matches);
                                        echo $matches[0];
                                    }else{
                                    ?>
                                    <div class="banner-cont">
                                     <img class="upper-image" src ="<?php echo $this->config->base_url() . 'images/'."video-thumb.png"; ?>">
                                     <img src ="<?php echo $this->config->base_url() . 'assets/Advertise/thumbs/' . $value['cover_image']."demo.jpeg"; ?>" style="height:80px;width:80px"></div>
                                <?php }} ?>
                            </td> 
                            <td><?php if ($value['media_type'] == '1') { ?>
                                    <?php echo $this->lang->line('Standard') ?>
                                <?php } else { ?>
                                    <?php echo $this->lang->line('video') ?>
                                    <?php } ?>

                            </td>
                            <td><?php echo$value['size']; ?></td>
                            <td><?php echo$value['link']; ?></td>
                            <td><?php
                                $name = array();
                                foreach ($value['title'] as $cn) {
                                    $name[] = $cn['title'];
                                }
                                echo implode(',', $name);
                                ?>
                            </td>
                            <!--td><?php if ($value['is_approved'] == '1') { ?>
                            <spam class="aprove-btn"><?php echo $this->lang->line('Approved') ?></spam>
                           <?php } elseif ($value['is_approved'] == '2') { ?>
                            <spam class="rejected-btn"><?php echo $this->lang->line('Rejected') ?></spam>
                            <?php } elseif ($value['status'] == '0') { ?>
                            <spam class="rejected-btn"><?php echo $this->lang->line('Draft') ?></spam>
                            <?php } elseif ($value['status'] == '1' && $value['is_approved'] == '0') { ?>
                            <spam class="pending-btn"><?php echo $this->lang->line('Pending') ?></spam>
                            <?php } elseif ($value['status'] == '3') { ?>
                            <spam class="rejected-btn"><?php echo $this->lang->line('Deleted') ?></spam>
                           <?php }  ?>
                           </a></td-->
                            <td><?php $todays_date = strtotime(date("Y-m-d H:i:s")); ?>
                                <?php if ($value['is_approved'] == '1') { ?>
                                <?php if ($todays_date < strtotime($value['publish_date_from'])) { ?>
                                <spam class="aprove-btn"><?php echo $this->lang->line('Scheduled') ?></spam>
                                <?php } elseif ($value['status'] == '3') { ?>
                                <spam class="rejected-btn"><?php echo $this->lang->line('Deleted') ?></spam>
                                <?php } else{?>
                                <spam class="aprove-btn"><?php echo $this->lang->line('Approved') ?></spam>
                                 <?php }?>                           
                            
                            <?php } elseif ($value['is_approved'] == '2' && $value['status'] == '0') { ?>                       
                          
                            <spam class="pending-btn"><?php echo $this->lang->line('Draft') ?></spam>
                           <?php } elseif ($value['status'] == '1' && $value['is_approved'] == '0') { ?>
                            <spam class="pending-btn"><?php echo $this->lang->line('Pending') ?></spam>
                          <?php } elseif ($value['status'] == '3') { ?>
                            <spam class="rejected-btn"><?php echo $this->lang->line('Deleted') ?></spam>
                        <?php }else{ ?>
                            <spam class="rejected-btn"><?php echo $this->lang->line('Rejected') ?></spam>
                        <?php }?>
                        </a></td>
            
                    <td><a class="view-btn" href="<?php echo $this->config->base_url(); ?>index.php/view_custo_advertise?rowId=<?php echo $value['id']; ?>">View</a></td>
                 
                        <td><?php if ($value['status'] == '0') { ?>
                                <a class="review" href="#" onclick="SENDFORREVIEW(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');">send for review</a>
                                <a class="delete-bttn" href="#" onclick="DELETE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');">delete</a>
                                 <a class="edit-icon" href="<?php echo $this->config->base_url(); ?>index.php/editcustomeradvertise?rowId=<?php echo $value['id']; ?>">Edit</a>
                            <?php } elseif($value['status'] == '3') { ?>
                                <a class="shift-delete-bttn" href="#" onclick="SHIFTDELETE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');">Permanent delete</a>
                                <a class="restore-bttn" href="#" onclick="RESTORE(<?php echo $value['id']; ?>, '<?php echo "Restore"; ?>');">restore</a>
                            <?php } elseif($value['is_approved'] == '1') { ?>                                
                                 <a class="delete-bttn" href="#" onclick="DELETE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');">delete</a>
                                 <a class="edit-icon" href="<?php echo $this->config->base_url(); ?>index.php/editcustomeradvertise?rowId=<?php echo $value['id']; ?>">Edit</a>
                                                  
                              <?php } ?>
                           </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>

        </div>

    </div>


    <div class="pagination clearfix">
        <ul><?php echo $this->pagination->create_links(); ?></ul> 
    </div>



</div>    


<!--Pop up-->


<div class="layer-bg">
    <div class="layer-bg-popupOverlay"></div>
    <div class="popup-container clearfix">
        <div class="close-bttn"></div>
        <h4><?php echo $this->lang->line('Advertisement_Publish') ?></h4>
        <?php
        $attributes = array('name' => 'publish_form', 'id' => 'publish_form', 'class' => 'form-horizontal');
        echo form_open($this->config->base_url() . 'index.php/publish_customer_advertise', $attributes);
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

            <script>
    $(function() {
        $("#datefrom").datepicker({yearRange: "1900:3000", dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, minDate: 'today', inline: true});
    });
            </script>
            <tr><td><?php echo $this->lang->line('Publish_Date_From') ?></td><td><input type="text" id ="datefrom" placeholder="enter date" name="datefrom"></td></tr>
            <script>
                $(function() {
                    $("#dateto").datepicker({yearRange: "1900:3000", dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, minDate: 'today', inline: true});
                });
            </script>
            <tr><td><?php echo $this->lang->line('Publish_Date_To') ?></td><td><input type="text" id ="dateto" placeholder="enter date" name="dateto"></td></tr>

            <tr><td><?php echo $this->lang->line('Display_time') ?></td><td><input type="text" id ="time" placeholder="enter time" name="time"></td></tr>
            <input type="hidden" name="advertise_id" id="advertise_id" value="<?php echo $value['id']; ?>">            
        </table>

        <div class="popup-inner clearfix">
            <input type="submit" value="Save" />
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
            datefrom: {
                required: true
            },
            dateto: {
                required: true
            },
            time: {
                required: true
            }
        }

    });
</script>
</body>
</html>
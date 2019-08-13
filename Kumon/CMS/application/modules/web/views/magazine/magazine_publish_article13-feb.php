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

    function LanguageData(val,catId,langId,rowId) {
        window.location.href="<?php echo base_url();?>index.php/magazinepublishedarticlelist?catagory="+catId+"&lang="+val+"&rowId="+rowId;
    }

  /* SORT CATAGORY DATA*/
  
   function CatagoryData(val,catId,langId,rowId1) {   
        window.location.href="<?php echo base_url();?>index.php/magazinepublishedarticlelist?catagory="+val+"&lang="+langId+"&rowId="+rowId1;
        //$('#catagory_form').submit();
    }



   
 
    
    /*Delete a article*/
function DELETE(rowId)
    {
       var disable = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_delete_this_record"); ?>");
        if (disable === true)
        {
            var msg = '';
            $.ajax({
                type: "POST",
                data: {val: rowId},
                dataType: "html",
                url: "<?php echo $this->config->base_url(); ?>index.php/deletemagzineads",
                success: function(data)
                { //alert(data);
                    location.reload();
                }
            });
        
    }}
    



</script>
</head>

<?php
$lang = $_GET['lang'];
$catagory = $_GET['catagory'];
?>

<div class="subheader">
    <div class="fix-container clearfix">
       <h4><?php echo $this->lang->line('Magazine').":".$magazine_title['title']; ?></h4>
    </div>
</div>

<nav class="clearfix">
    <div class="fix-container clearfix">
        <ul>
            <li><a href="magazinearticlelist?rowId=<?php echo$_REQUEST['rowId']; ?>"><?php echo $this->lang->line('All') ?> <span class="blue"><?php echo $all_article; ?></span></a></li>
            <li><a href="magazinepublishedarticlelist?rowId=<?php echo$_REQUEST['rowId']; ?>"><?php echo $this->lang->line('Published') ?> <span class="green"><?php echo $magazine_publish_article; ?></span></a></li>
            <li><a href="magazinereviewarticlelist?rowId=<?php echo$_REQUEST['rowId']; ?>"><?php echo $this->lang->line('Review') ?> <span class="yellow"><?php echo $magazine_review_article; ?></span></a></li>            
            <li><a href="magazinedeletedarticlelist?rowId=<?php echo$_REQUEST['rowId']; ?>"><?php echo $this->lang->line('Deleted') ?> <span class="red"><?php echo $magazine_deleted_article; ?></span></a></li>
            
        </ul>
    </div>
</nav>


<div class="fix-container clearfix">
    <div class="upper_bar clearfix filter-bar"> 
        <div class="search-bar right-align-box">    
            <div class="language-filter clearfix">
                <div class="select-container">
                    <div class="select-arrow">
                        <div class="arrow-down"></div>
                    </div>
                    <?php //echo"<pre>";  print_r($magazine_id) ;  ?>                    
                    <form id="languge_form" action="<?php echo $this->config->base_url() ?>index.php/magazinepublishedarticlelist?rowId=<?php echo $magazine_id;?>" method="POST">
                        <select class="choose-lang" onChange="LanguageData(this.value,'<?php echo $_GET['catagory'] ?>','<?php echo $_GET['lang'] ?>','<?php echo $_GET['rowId'] ?>');" name="lang" id="lang">
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
                    <form id="sort_catagory" action="<?php echo $this->config->base_url() ?>index.php/magazinepublishedarticlelist?rowId=<?php echo $magazine_id;?>" method="POST">
                        <select class="choose-lang" onChange="CatagoryData(this.value,'<?php echo $_GET['catagory'] ?>','<?php echo $_GET['lang'] ?>','<?php echo $_GET['rowId'] ?>');" name="catagory" id="catagory">
                            <option value="" name="">Select Category</option>
                            <?php foreach ($catagory_result as $state) { ?>
                                    <option value="<?php echo $state['id']; ?>" <?php if ($state['id'] == $catagory) { ?> selected="selected" <?php } ?>><?php echo $state['category_name']; ?></option>
                                <?php }
                                ?>
                        </select>
                    </form>
                </div>
            </div>
        </div>


    </div>

    <div class="category-list">
        <div class="table-container">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <!--th><input type="checkbox" /></th-->
                    <th><?php echo $this->lang->line('Title') ?></th>
                    <th><?php echo $this->lang->line('Author') ?></th>                    
                    <th><?php echo $this->lang->line('Categories') ?></th>
                    <th><?php echo $this->lang->line('Tags') ?></th>
                    <th><span class="commentNotification"><?php echo $this->lang->line('No_of_comments') ?></span></th>
                    <th><?php echo $this->lang->line('Language') ?></th>
                    <th><?php echo $this->lang->line('Status') ?></th>                    
                    <th class="action-colm"><?php echo $this->lang->line('Action') ?></th>
                </tr>
                <div style="color: red"><p><?php echo $msg;  echo $this->session->flashdata("msg");?>   </p>
                    <div style="color: green"><p><?php echo $msg;  echo $this->session->flashdata("sus_msg");?>   </p></div>
                    <?php echo validation_errors(); ?></div>
                <?php //echo"<pre>"; print_r($data_result); die; ?>
                <tr> <?php
                    if ($data_result != "") {
                        foreach ($data_result as $value) { ?> 
                            <?php $todays_date = date("Y-m-d") ?>
                            <td><?php echo$value['title']; ?></td>
                            <td><?php echo$value['name']; ?></td>                            
                             <td><?php
                                $name = array();
                                if($value['categoy_name'] !=""){
                                foreach ($value['categoy_name'] as $cn) {
                                    $name[] = $cn['category_name'];
                                }
                                echo implode(',', $name);
                                }
                                ?></td>
                            <td><?php echo$value['tags']; ?></td>
                            <td><span class="commentNotification"><em><?php echo$value['comments_count']; ?></em></span></td> 
                            <td><?php echo$value['language']; ?></td>
                            
                         <!--td><?php if ($todays_date <= $value['publish_from'] && $value['status'] == '2') {  ?>
                            <spam class="aprove-btn"><?php echo $this->lang->line('Published') ?></spam>
                        <?php } elseif ($todays_date > $value['publish_from'] && $value['status'] == '2') { ?>
                            <spam class="rejected-btn"><?php echo $this->lang->line('Scheduled') ?></spam>
                        <?php } else if($value['status'] == '0') { ?>
                            <spam class="pending-btn"><?php echo $this->lang->line('Deleted') ?></spam>                        
                        <?php } else {?>
                            <spam class="rejected-btn">Pending</spam>
                        <?php } ?></a></td-->
                        <td>
                          <?php if ($value['status'] == '2') {  
                             if($todays_date >= $value['publish_from']){ ?>
                             <spam class="aprove-btn"><?php echo $this->lang->line('Published') ?></spam>
                            <?php } else{ ?>
                             <spam class="rejected-btn"><?php echo $this->lang->line('Scheduled') ?></spam>
                              <?php } ?>                          
                        <?php } else if($value['status'] == '4') { //as new added by customer?>
                            <spam class="pending-btn"><?php echo $this->lang->line('Draft') ?></spam>                        
                        <?php } else if($value['status'] == '0') { ?>
                            <spam class="rejected-btn"><?php echo $this->lang->line('Deleted') ?></spam>
                        <?php } else if($value['status'] == '1') {?></a>
                         <spam class="publish-btn"><?php echo $this->lang->line('Pending') ?></spam>
                        <?php } else if($value['status'] == '3'){ ?>
                              <spam class="publish-btn"><?php echo $this->lang->line('Rejected') ?></spam> </a>
                        <?php } ?>
                         </td>
                        
                        <td><?php if ($value['status'] == '1') { ?>
                               <a class="aprove-btn" href="<?php echo $this->config->base_url(); ?>index.php/reviewmagazinearticle?rowId=<?php echo $value['id']; ?>" >Review</a>
                           <?php } ?>                               
                            <a class="delete-bttn" href="#" onclick="DELETE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');">delete</a>
                            <a class="edit-icon" href="<?php echo $this->config->base_url(); ?>index.php/editMagazinearticle?rowId=<?php echo $_GET['rowId']; ?>&artId=<?php echo $value['id']; ?>">Edit</a></td>
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
        echo form_open($this->config->base_url() . 'index.php/publishadvertise', $attributes);
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
            <input type="hidden" name="advertise_id" id="advertise_id" value="">            
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
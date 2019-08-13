<script src="js/jquery.js"></script>

<script>
    $(document).ready(function() {
        $(".view-bttn").click(function() {
            $(".layer-bg").fadeIn();
        });

        $(".close-bttn").click(function() {
            $(".layer-bg").fadeOut();
        });
    });


    /* GET LANGUAGE ID AND FETCH LANGUAGE DATA*/

    function LanguageData(val,catId,langId) { 
        window.location.href="<?php echo base_url();?>index.php/openarticlelist?catagory="+catId+"&lang="+val;
    }

  /* SORT CATAGORY DATA*/
  
   function CatagoryData(val,catId,langId) {
        var rowId = val;//      
        window.location.href="<?php echo base_url();?>index.php/openarticlelist?catagory="+val+"&lang="+langId;
        //$('#catagory_form').submit();
    }

    /* GET ADMIN DETAILS AJAX*/
    function PUBLISHARTICLE(val1) {

        var tid1 = val1;
        //alert(tid1);
        $.ajax({
            type: "POST",
            data: {Id: tid1},
            url: "<?php echo $this->config->base_url(); ?>index.php/publisharticle",
            success: function(data)
            {
                //console.log(data);
                $(".popUpDiv").html(data);

            }

        });

    }

    /* DELETE A ADMIN*/

    function ConfirmDel() {
        var x = confirm("Are you sure want to delete this record?")
        if (x) {
            return true;
        } else {
            return false;
        }
    }
    /*DELETE A ARTICLE*/
    function DELETE(rowId) {
        var disable = confirm("Are you sure want to delete this article");
        if (disable === true)
        {
            var msg = '';
            $.ajax({
                type: "POST",
                data: {val: rowId},
                dataType: "html",
                url: "<?php echo $this->config->base_url(); ?>index.php/deleteopenarticle",
                success: function(data)
                { //alert(data);
                    location.reload();
                }
            });

        }
    }

    /*RESTORE A ARTICLE*/
    function RESTORE(rowId) {
        var disable = confirm("Are you sure want to restore this article");
        if (disable === true)
        {
            var msg = '';
            $.ajax({
                type: "POST",
                data: {val: rowId},
                dataType: "html",
                url: "<?php echo $this->config->base_url(); ?>index.php/restoreopenarticle",
                success: function(data)
                { //alert(data);
                    location.reload();
                }
            });

        }
    }

    /*RESTORE A ARTICLE*/
    function PERMANENTDELETE(rowId) {
        var disable = confirm("Are you sure want to permanently delete this article");
        if (disable === true)
        {
            var msg = '';
            $.ajax({
                type: "POST",
                data: {val: rowId},
                dataType: "html",
                url: "<?php echo $this->config->base_url(); ?>index.php/permanentdeleteopenarticle",
                success: function(data)
                { //alert(data);
                    location.reload();
                }
            });

        }
    }



</script>


</head>
<?php
$lang = $_GET['lang'];
$catagory = $_GET['catagory'];
?>

<div class="subheader">
    <div class="fix-container clearfix">
        <h4>Open Article</h4>
        <a class="add-new" href="<?php echo $this->config->base_url(); ?>index.php/addarticle">Add New</a>

    </div>
</div>

<nav class="clearfix">
    <div class="fix-container clearfix">
        <ul>
            <li><a href="openarticlelist"><?php echo $this->lang->line('All') ?> <span class="blue"><?php echo $all_article; ?></span></a></li>
            <li><a href="getpublishedarticlelist"><?php echo $this->lang->line('Published') ?> <span class="green"><?php echo $all_publish_article; ?></span></a></li>            
            <li><a href="getnewarticlelist"><?php echo $this->lang->line('New') ?> <span class="yellow"><?php echo $all_new_article; ?></span></a></li>
            <li><a href="getdraftarticlelist"><?php echo $this->lang->line('Draft') ?> <span class="yellow"><?php echo $all_drafted_article; ?></span></a></li>
            <li><a href="getdeletedarticlelist"><?php echo $this->lang->line('Deleted') ?> <span class="red"><?php echo $all_deleted_article; ?></span></a></li>            
        </ul>
    </div>
</nav>

<div class="fix-container clearfix">
    <div class="upper_bar clearfix filter-bar">       

        <div class="search-bar">
            <div class="search-box">
                <div class="language-filter clearfix">
                    <div class="select-container">
                        <div class="select-arrow">
                            <div class="arrow-down"></div>
                        </div> 
                        <form id="catagory_form" action="<?php echo $this->config->base_url() ?>index.php/openarticlelist" method="POST">
                             <select class="choose-lang" onChange="CatagoryData(this.value,'<?php echo $_GET['catagory'] ?>','<?php echo $_GET['lang'] ?>');" name="catagory" id="catagory">
                            <option value="" name="">Select Category</option>
                            <?php foreach ($catagory_list as $state) { ?>
                                    <option value="<?php echo $state['id']; ?>" <?php if ($state['id'] == $catagory) { ?> selected="selected" <?php } ?>><?php echo $state['category_name']; ?></option>
                                <?php }
                                ?>
                        </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="search-bar right-align-box">
            <div class="language-filter clearfix">
                <div class="select-container">
                    <div class="select-arrow">
                        <div class="arrow-down"></div>
                    </div> 
                    <form id="languge_form" action="<?php echo $this->config->base_url() ?>index.php/openarticlelist" method="POST">
                        <select class="choose-lang" onChange="LanguageData(this.value,'<?php echo $_GET['catagory'] ?>','<?php echo $_GET['lang'] ?>');" name="lang" id="lang">
                            <option value="" name="">Select Language</option>
                            <?php foreach ($language_result as $state) { ?>
                                <option value="<?php echo $state['id']; ?>" <?php if ($state['id'] == $lang) { ?> selected="selected" <?php } ?>><?php echo $state['language']; ?></option>
                            <?php }
                            ?>
                        </select>
                    </form>
                </div>
            </div>


        </div>

    </div>

    <div class="category-list">
        <div style="color: red"><p><?php
                echo $msg;
                echo $this->session->flashdata("msg");
                ?>   </p>
            <?php echo validation_errors(); ?></div>
        <div class="table-container">
            <table class="adjust-table" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Catagory</th>
                    <th>Tags</th>
                    <th>No of comments</th>
                    <th>Language</th>
                    <th>Status</th>                    
                    <th class="action-grid">Action</th>
                </tr>
        <tr></tr>
         <tr></tr>
         <tr></tr>
                <?php //echo"<pre>"; print_r($data_result); die; ?>
                <?php
                if ($data_result != "") {
                    foreach ($data_result as $value) {
                        ?>
                        <tr> 
                            <td><?php echo $value['title']; ?>  </td>
                            <td>
                                <?php
                                if ($value['author'] == "") {
                                    echo$value['user_name'];
                                } else {
                                    echo $value['author'];
                                }
                                ?>
                            </td>


                            <td><?php
                                $name = array();
                                foreach ($value['category_name'] as $cn) {
                                    $name[] = $cn['category_name'];
                                }
                                echo implode(',', $name);
                                ?></td>
                            <td><?php echo $value['Tags']; ?>  </td>
                            <td><?php echo $value['comment_count']; ?>  </td>
                            <td><?php echo $value['language']; ?>  </td>
                            <td>
                                <?php /* CREATED BY ADMIN */ if ($value['created_by'] == '1') { ?>                                
                                    <?php if ($value['status'] == '1') { ?>
                                <spam class="rejected-btn">Draft</spam>
                            <?php } elseif ($value['status'] == '2') { ?>

                                <?php if (strtotime('today') >= $value['publish_from'] && $value['status'] == '2') { ?>
                                    <spam class="aprove-btn">Published</spam>
                                <?php } elseif (strtotime('today') < $value['publish_from']) { ?>
                                    <spam class="rejected-btn">Scheduled</spam>
                                <?php } ?>

                            <?php } else { ?>
                                <spam class="pending-btn">Deleted</spam>
                            <?php } ?> 

                        <?php } elseif ($value['created_by'] == '0') /* CREATED BY CROLLER */ { ?>                                    
                            <?php if ($value['status'] == '1') { ?>
                                <spam class="rejected-btn">NEW</spam>
                            <?php } elseif ($value['status'] == '2') { ?>

                                <?php if (strtotime('today') >= $value['publish_from']) { ?>
                                    <spam class="aprove-btn">Published</spam>
                                <?php } elseif (strtotime('today') < $value['publish_from']) { ?>
                                    <spam class="rejected-btn">Scheduled</spam>
                                <?php } ?>                                    

                            <?php } else { ?>
                                <spam class="pending-btn">Deleted</spam>
                                <?php
                            }
                        }
                        ?>

                        </td>

                        <td>
                            <?php if ($value['status'] == '1') { ?>
                                <a class="aprove-btn publish_class" href="javascript:void(0)" >Publish</a>

                                <a class="edit-icon" href="<?php echo $this->config->base_url(); ?>index.php/editopenarticle?rowId=<?php echo $value['id']; ?>">Edit</a>
                                <a class="delete-bttn" href="#" onclick="DELETE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');">DELETE</a>

                            <?php } elseif ($value['status'] == '2') { ?>                                
                                <a class="edit-icon" href="<?php echo $this->config->base_url(); ?>index.php/editopenarticle?rowId=<?php echo $value['id']; ?>">Edit</a>
                                <a class="delete-bttn" href="#" onclick="DELETE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');">DELETE</a> 
                            <?php } else { ?>                                
                                <a class="rejected-btn" href="#" onclick="RESTORE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');">RESTORE</a>
                                <a class="delete-bttn" href="#" onclick="PERMANENTDELETE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');">PERMANENT DELETE</a>
                            <?php } ?>                              
                        </td>

                            </td>
                </tr>
        
        <tr>
            <td colspan="8" class="padding-free">
                <div class="show_form" style="display:none;">
                                    <div class="add-article">

                                        <div class="add-article-form">
                                            
                                            <div class="left-grid">
                                                                                            
                                            
                                            <div class="input-box">
                                                <label>Title :</label>            
                                                <input id="title"  type="text" name="title" value="<?php echo $value['title']; ?>">
                                            </div>
                                                
                                                
                                            <div class="input-box clearfix"> 
                                                <div class="date-field"><span><?php echo $this->lang->line('Publish_Date_From') ?></span> <small><?php echo $value['publish_from']; ?></small>  </div>
                                                
                                                <div class="date-field"><span><?php echo $this->lang->line('Publish_Date_To') ?> </span> <small><?php echo $value['publish_to']; ?></small></div>
                                                
                                            
                                                </div> 
                                                
                                             <div class="input-box">
                                                <label>Tags : <?php echo $value['tags']; ?></label>
                                                 <textarea rows="6" readonly></textarea>
                                            </div> 
                                                
                                                
                                            <div class="advertisement-type clearfix">
                                                <div class="checkbox-container"><input type="checkbox" name="comments" id="media_type" value="1" checked="true"> Allow Comments </div>
                                                <div class="checkbox-container"><input type="checkbox" name="share" id="media_type"  value="2" checked="true"> Allow Share </div>
                                            </div>
                                                
                                                
                                             <div class="advertisement-type clearfix">
                                                   <h3><?php echo $this->lang->line('Advertisement_Format') ?></h3>
                                                <div class="checkbox-container"><input type="checkbox" name="media_type" id="media_type_s" value="0" <?php if ($value['media_type'] == 0) { ?> checked='checked' <?php } ?> > Standard </div>
                                                <div class="checkbox-container"><input type="checkbox" name="media_type" id="media_type_v"  value="1" <?php if ($value['media_type'] == 1) { ?> checked='checked' <?php } ?>> video </div>
                                            </div>
                                                
                                                
                                            </div>
                                            <div class="right-grid">
                                                <div class="input-box">
                                                    <label><?php echo $this->lang->line('Language') ?></label>
                                                     
                                                <select class="choose-lang" name ='language' id="languageval">
                                                    <option selected>Select Language</option>
                                                    <?php $lang = $value['language_id']; ?>
                                                    <?php foreach ($language_result as $state) { ?>
                                                        <option value="<?php echo $state['id']; ?>" <?php if ($state['id'] == $lang) { ?> selected="selected" <?php } ?>><?php echo $state['language']; ?></option>
                                                    <?php }
                                                    ?>                    

                                                </select>
                                                </div>
                                                
                                                
                                                <div class="input-box">
                                                    <label><?php echo $this->lang->line('Categories') ?></label>
                                                <div class="multiselect-box clearfix">                    
                                                    <?php
                                                    $selected_catagory = $value['category_id'];
                                                    $selected_catagoryArray = explode(',', $selected_catagory);
                                                    foreach ($catagory_result as $value1) {
                                                        $catagory_id = $value1['id'];
                                                        $catagory_name = $value1['category_name'];
                                                        ?>
                                                                   <!--div class="cbox"><input type="checkbox" id="catagory[]" name="catagory[]" value="' . $catagory_id . '" />' . $catagory_name.'</div-->
                                                        <div class="cbox"> <input type="checkbox" id="catagory[]" name="catagory[]" value="<?php echo $catagory_id ?>"<?php if (in_array($catagory_id, $selected_catagoryArray)) { ?> checked="checked" <?php } ?>/> <?php echo $catagory_name ?></div>
                                                    <?php } ?>                      


                                                </div>

                                                </div>
                                                
                                                
                                       <div class="input-container clearfix">   
                                         <a class="publish-magazine" href="#" onclick="PUBLISHARTICLE(<?php echo $value['id']; ?>, '<?php echo "publish"; ?>');">Publish</a>
                                      <button type="cancel" onclick="javascript:window.location = '<?php echo $this->config->base_url(); ?>index.php/openarticlelist';">Cancel</button>
                                       
                                                        </div>

                                                       
                                            
                                            </div>
                                            
                                                
                                                
                                                <div class="input-box">
                                                    <input type='hidden' name='article_id' value='<?php echo $value['id']; ?>'>
                                                    <div class="input-box">
                                                        <div class="input-container clearfix"> 
          
                                                    </div>

                                                </div>



                                            

                                        </div>

                                    </div>
                                </div>
            </td>
        </tr>
        
        
        

                    <?php }
                } ?>    

            </table>

        

    </div>


    <div class="pagination clearfix">
        <ul><?php echo $this->pagination->create_links(); ?></ul> 
    </div>


</div>    



<script>

    $('.publish_class').click(function() {
//        $('.show_form').each(function(){
//           $(this).hide(); 
//        });


       $(this).parent().parent().next().find('.show_form').toggle();
    });
</script>
</body>
</html>
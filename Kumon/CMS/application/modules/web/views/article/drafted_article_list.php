<script src="js/jquery.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="<?php echo $this->config->base_url() . 'js/ckeditor/ckeditor.js'; ?>"></script>

<!-- Load jQuery and the validate plugin -->  
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

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

    function LanguageData(val, catId, langId) {
        window.location.href = "<?php echo base_url(); ?>index.php/getdraftarticlelist?catagory=" + catId + "&lang=" + val;
    }

    /* SORT CATAGORY DATA*/

    function CatagoryData(val, catId, langId) {
        var rowId = val;//      
        window.location.href = "<?php echo base_url(); ?>index.php/getdraftarticlelist?catagory=" + val + "&lang=" + langId;
        //$('#catagory_form').submit();
    }
    /* GET ADMIN DETAILS AJAX*/
    function getAllSupportDetails(val1) {

        var tid1 = val1;
        //alert(tid1);
        $.ajax({
            type: "POST",
            data: {Id: tid1},
            url: "<?php echo $this->config->base_url(); ?>index.php/admindetails",
            success: function(data)
            {
                //console.log(data);
                $(".popUpDiv").html(data);

            }

        });

    }

    /* DELETE A ADMIN*/

    function ConfirmDel() {
        var x = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_delete_this_record"); ?>")
        if (x) {
            return true;
        } else {
            return false;
        }
    }
    /*DELETE A ARTICLE*/
    function DELETE(rowId) {
        var disable = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_delete_this_record"); ?>");
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
        var disable = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_restore_this_article"); ?>");
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
        var disable = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_delete_this_record"); ?>");
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
    <div class="fix-container clearfix openArticlesTab">
        <h4><?php echo $this->lang->line('Open_Article') ?></h4>
<!--        <a class="add-new" href="<?php echo $this->config->base_url(); ?>index.php/addarticle">Add New</a>-->
        <a class="add-new" href="<?php echo $this->config->base_url(); ?>index.php/addarticle">Manually</a>
        <a class="add-new" href="<?php echo $this->config->base_url(); ?>index.php/addarticle?request=1">Via URL</a>

    </div>
</div>

<nav class="clearfix">
    <div class="fix-container clearfix">
        <ul>
            <li><a href="openarticlelist"><?php echo $this->lang->line('All') ?> <span class="blue"><?php echo $all_article; ?></span></a></li>
            <li><a href="getpublishedarticlelist"><?php echo $this->lang->line('Published') ?> <span class="green"><?php echo $all_publish_article; ?></span></a></li>            
            <li><a href="getnewarticlelist"><?php echo $this->lang->line('New') ?> <span class="yellow"><?php echo $all_new_article; ?></span></a></li>
            <li class="active"><a href="getdraftarticlelist"><?php echo $this->lang->line('Draft') ?> <span class="yellow"><?php echo $all_drafted_article; ?></span></a></li>
            <li><a href="getdeletedarticlelist"><?php echo $this->lang->line('Deleted') ?> <span class="red"><?php echo $all_deleted_article; ?></span></a></li>            
        </ul>
    </div>
</nav>

<div class="fix-container clearfix">
    <div class="upper_bar clearfix filter-bar">       

<!--        <div class="search-bar">
            <div class="search-box">
                <div class="language-filter clearfix">
                    <div class="select-container">
                        <div class="select-arrow">
                            <div class="arrow-down"></div>
                        </div> 
                        <form id="catagory_form" action="<?php echo $this->config->base_url() ?>index.php/getdraftarticlelist" method="POST">
                            <select class="choose-lang" onChange="CatagoryData(this.value, '<?php echo $_GET['catagory'] ?>', '<?php echo $_GET['lang'] ?>');" name="catagory" id="catagory">
                                <option value="" name=""><?php echo $this->lang->line('Select_Category') ?></option>
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

        <div class="search-bar right-align-box">
            <div class="language-filter clearfix">
                <div class="select-container">
                    <div class="select-arrow">
                        <div class="arrow-down"></div>
                    </div> 
                    <form id="languge_form" action="<?php echo $this->config->base_url() ?>index.php/getdraftarticlelist" method="POST">
                        <select class="choose-lang" onChange="LanguageData(this.value, '<?php echo $_GET['catagory'] ?>', '<?php echo $_GET['lang'] ?>');" name="lang" id="lang">
                            <option value="" name=""><?php echo $this->lang->line("Article_Language"); ?></option>
                            <?php foreach ($language_result as $state) { ?>
                                <option value="<?php echo $state['id']; ?>" <?php if ($state['id'] == $lang) { ?> selected="selected" <?php } ?>><?php echo $state['language']; ?></option>
                            <?php }
                            ?>
                        </select>
                    </form>
                </div>
            </div>


        </div>-->

    </div>

    <div class="category-list">
        <div style="color: red"><p><?php
                echo $msg;
                echo $this->session->flashdata("msg");
                ?>   </p>
<?php echo validation_errors(); ?></div>
        <div class="table-container">
            <?php
                if ($data_result != "") {?>
            <script class="jsbin" src="https://datatables.net/download/build/jquery.dataTables.nightly.js"></script>
        <script>
    $(document).ready(function () {
        $('#draftedArticle').dataTable({"bPaginate": false,"bSort": false});
    });
        </script>
            <table id="draftedArticle" border="0" cellpadding="0" cellspacing="0">
                <thead>
                <tr>
                    <th><?php echo $this->lang->line('Title') ?></th>
                    <th><?php echo $this->lang->line('Author') ?></th>
                    <th><?php echo $this->lang->line('Category') ?></th>
                    <th><?php echo $this->lang->line('Tags') ?></th>
                    <th><span class="commentNotification"><?php echo $this->lang->line('No_of_comments') ?></span></th>
<!--                    <th><?php echo $this->lang->line('Category_Language') ?></th>
-->                    <th><?php echo $this->lang->line('Article_Language') ?></th>
                    <th><?php echo $this->lang->line('Status') ?></th>                    
                    <th class="action-colm"><?php echo $this->lang->line('Action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php //echo"<pre>"; print_r($data_result); die; ?>
                <?php
                
                    foreach ($data_result as $value) {
                        ?>
                        <tr> 
                            <td><?php echo $value['title']; ?>  </td>
                            <td>
                                <?php
                                if ($value['author'] == "") {
                                    $explode=  explode(".", $value['website_url']);
                                    echo $explode[1];
                                }if($value['created_by']=='1'){ 
                                    echo "Admin";
                                    }if($value['created_by']=='2'){ 
                                    echo "Customer";
                                    }if($value['created_by']=='3'){ 
                                    echo "User";
                                    }else {
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
                            <td><?php echo $value['tags']; ?>  </td>

                            <td><span class="commentNotification"><em><?php echo$value['comment_count']; ?></em></span></td>
<!--                            <td><?php echo $value['language']; ?>  </td>
-->                            <td><?php if($value['article_language']=='1'){ echo"English"; }
                            elseif($value['article_language']=='2'){ echo"Portuguese"; }
                            elseif($value['article_language']=='3'){ echo"Spanish"; } ?>  
                            </td>
                            <td>
                        <spam class="pending-btn"><?php echo $this->lang->line("Draft"); ?></spam>
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
                        </tr>
                        


    <?php }

?>


            </table>
                <?php } ?>
        </div>

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
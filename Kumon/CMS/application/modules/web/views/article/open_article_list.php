<script src="js/jquery.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="<?php echo $this->config->base_url() . 'js/ckeditor/ckeditor.js'; ?>"></script>

<!-- Load jQuery and the validate plugin -->
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

<script>
    $(document).ready(function () {
        $(".view-bttn").click(function () {
            $(".layer-bg").fadeIn();
        });

        $(".close-bttn").click(function () {
            $(".layer-bg").fadeOut();
        });
    });


    /* GET LANGUAGE ID AND FETCH LANGUAGE DATA*/

    function LanguageData(val, catId, langId, catlang) {
        window.location.href = "<?php echo base_url(); ?>index.php/openarticlelist?catagory=" + catId + "&lang=" + val + "&cat_lang=" + catlang;
    }

    /* SORT CATAGORY DATA*/

    function CatagoryData(val, catId, langId, catlang) {
        var rowId = val;//      
        window.location.href = "<?php echo base_url(); ?>index.php/openarticlelist?catagory=" + val + "&lang=" + langId + "&cat_lang=" + catlang;
        //$('#catagory_form').submit();
    }

    /*Article language Data*/
    function CatLangData(val, catId, artId, langId) {

        var rowId = val;//      
        window.location.href = "<?php echo base_url(); ?>index.php/openarticlelist?catagory=" + catId + "&cat_lang=" + val + "&lang=" + langId;
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
            success: function (data) {
                //console.log(data);
                location.reload();

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
        if (disable === true) {
            var msg = '';
            $.ajax({
                type: "POST",
                data: {val: rowId},
                dataType: "html",
                url: "<?php echo $this->config->base_url(); ?>index.php/deleteopenarticle",
                success: function (data) { //alert(data);
                    location.reload();
                }
            });

        }
    }

    /*RESTORE A ARTICLE*/
    function RESTORE(rowId) {
        var disable = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_restore_this_article"); ?>");
        if (disable === true) {
            var msg = '';
            $.ajax({
                type: "POST",
                data: {val: rowId},
                dataType: "html",
                url: "<?php echo $this->config->base_url(); ?>index.php/restoreopenarticle",
                success: function (data) { //alert(data);
                    location.reload();
                }
            });

        }
    }

    /*RESTORE A ARTICLE*/
    function PERMANENTDELETE(rowId) {
        var disable = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_perdelete_this_record"); ?>");
        if (disable === true) {
            var msg = '';
            $.ajax({
                type: "POST",
                data: {val: rowId},
                dataType: "html",
                url: "<?php echo $this->config->base_url(); ?>index.php/permanentdeleteopenarticle",
                success: function (data) { //alert(data);
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
$cat_lang = $_GET['cat_lang'];
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
            <li class="active"><a href="openarticlelist"><?php echo $this->lang->line('All') ?> <span
                            class="blue"><?php echo $all_article; ?></span></a></li>
            <li><a href="getpublishedarticlelist"><?php echo $this->lang->line('Published') ?> <span
                            class="green"><?php echo $all_publish_article; ?></span></a></li>
            <li><a href="getnewarticlelist"><?php echo $this->lang->line('New') ?> <span
                            class="yellow"><?php echo $all_new_article; ?></span></a></li>
            <li><a href="getdraftarticlelist"><?php echo $this->lang->line('Draft') ?> <span
                            class="yellow"><?php echo $all_drafted_article; ?></span></a></li>
            <li><a href="getdeletedarticlelist"><?php echo $this->lang->line('Deleted') ?> <span
                            class="red"><?php echo $all_deleted_article; ?></span></a></li>
        </ul>
    </div>
</nav>

<div class="fix-container clearfix">


    <div class="category-list">
        <div style="color: red"><p><?php
                echo $msg;
                echo $this->session->flashdata("msg");
                ?>   </p>
            <div style="color: green"><p><?php
                    echo $msg;
                    echo $this->session->flashdata("sus_msg");
                    ?>   </p></div>
            <?php echo validation_errors(); ?>
        </div>
        <?php if ($data_result != "") { ?>
        <script class="jsbin" src="https://datatables.net/download/build/jquery.dataTables.nightly.js"></script>
        <script>
            $(document).ready(function () {
                $('#openArticle').dataTable({"bPaginate": false, "bSort": false});
            });
        </script>
        <div class="table-container">

            <table class="adjust-table clearfix" id="openArticle" border="0" cellpadding="0" cellspacing="0">
                <thead>
                <tr>
                    <th><?php echo $this->lang->line('Title') ?></th>
                    <th><?php echo $this->lang->line('Author') ?></th>
                    <th><?php echo $this->lang->line('Magazine') ?></th>
                    <th><?php echo $this->lang->line('Tags') ?></th>
                    <th><span class="commentNotification"><?php echo $this->lang->line('No_of_comments') ?></span></th>
                    <!--                    <th><?php echo $this->lang->line('Category_Language') ?></th>-->
                    <th><?php echo $this->lang->line('Article_Language') ?></th>
                    <th><?php echo $this->lang->line('Status') ?></th>
                    <th class="action-colm"><?php echo $this->lang->line('Action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php //echo"<pre>"; print_r($data_result);die;  ?>
                <?php
                foreach ($data_result as $value) {
                    ?>
                    <tr>
                        <td><?php echo $value['title']; ?>  </td>
                        <td>
                            <?php
                            if ($value['author'] == "") {
                                if ($value['website_url'] != '') {
                                    $explode = explode(".", $value['website_url']);
                                } else {
                                    $explode = explode(".", $value['article_link']);
                                }
                                echo $explode[1];
                            }
                            if ($value['created_by'] == '1') {
                                echo "Admin";
                            }
                            if ($value['created_by'] == '2') {
                                echo "Customer";
                            }
                            if ($value['created_by'] == '3') {
                                echo "User";
                            } else {
                                echo $value['author'];
                            }
                            ?>
                        </td>


                        <td>
                            <?php echo $value["magazines"]; ?>
                        </td>
                        <td><?php echo $value['tags']; ?>  </td>
                        <td><span class="commentNotification"><em><?php echo $value['comment_count']; ?></em></span>
                        </td>
                        <!--                            <td><?php echo $value['language']; ?>  </td>-->
                        <td><?php
                            if ($value['article_language'] == '1') {
                                echo "English";
                            } elseif ($value['article_language'] == '2') {
                                echo "Portuguese";
                            } elseif ($value['article_language'] == '3') {
                                echo "Spanish";
                            }
                            ?>
                        </td>
                        <td>
                            <?php /* CREATED BY ADMIN */
                            if ($value['created_by'] == '1') { ?>
                            <?php if ($value['status'] == '1') { ?>
                                <spam class="pending-btn"><?php echo $this->lang->line('Draft') ?></spam>
                            <?php } elseif ($value['status'] == '2') { ?>

                            <?php
                            $todays_date = date("Y-m-d");
                            if ($todays_date >= $value['publish_from'] && $todays_date <= $value['publish_to'] && $value['status'] == '2') {
                            ?>
                            <spam class="aprove-btn"><?php echo $this->lang->line('Published') ?>
                                <spam>
                                    <?php } elseif ($todays_date < $value['publish_from']) { ?>
                                        <spam class="rejected-btn"><?php echo $this->lang->line('Scheduled') ?></spam>
                                    <?php } elseif ($todays_date > $value['publish_to']) { ?>
                                        <spam class="rejected-btn"><?php echo $this->lang->line('Expired') ?></spam>
                                    <?php } ?>

                                    <?php } else { ?>
                                        <spam class="rejected-btn"><?php echo $this->lang->line('Deleted') ?></spam>
                                    <?php } ?>

                                    <?php } elseif ($value['created_by'] == '0') /* CREATED BY CROLLER */ { ?>
                                        <?php if ($value['status'] == '1') { ?>
                                            <spam class="pending-btn-btn"><?php echo $this->lang->line("New"); ?></spam>
                                        <?php } elseif ($value['status'] == '2') { ?>

                                            <?php
                                            $todays_date = date("Y-m-d");
                                            if ($todays_date >= $value['publish_from'] && $todays_date <= $value['publish_to'] && $value['status'] == '2') {
                                                ?>
                                                <spam class="aprove-btn"><?php echo $this->lang->line("Published"); ?></spam>
                                            <?php } elseif ($todays_date <= $value['publish_from']) { ?>
                                                <spam class="rejected-btn"><?php echo $this->lang->line("Scheduled"); ?></spam>
                                            <?php } elseif ($todays_date > $value['publish_to']) { ?>
                                                <spam class="rejected-btn"><?php echo $this->lang->line('Expired') ?></spam>
                                            <?php } ?>

                                        <?php } else { ?>
                                            <spam class="rejected-btn"><?php echo $this->lang->line("Deleted"); ?></spam>
                                            <?php
                                        }
                                    }
                                    ?>

                        </td>

                        <td>
                            <?php if ($value['status'] == '1') { ?>
                                <a class="aprove-btn publish_class" href="javascript:void(0)">Publish</a>

                                <a class="edit-icon"
                                   href="<?php echo $this->config->base_url(); ?>index.php/editopenarticle?rowId=<?php echo $value['id']; ?>">Edit</a>
                                <a class="delete-bttn" href="#"
                                   onclick="DELETE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');">DELETE</a>

                            <?php } elseif ($value['status'] == '2') { ?>
                                <a class="edit-icon"
                                   href="<?php echo $this->config->base_url(); ?>index.php/editopenarticle?rowId=<?php echo $value['id']; ?>">Edit</a>
                                <a class="delete-bttn" href="#"
                                   onclick="DELETE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');">DELETE</a>
                            <?php } else { ?>
                                <a class="restore-bttn" href="#"
                                   onclick="RESTORE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');">RESTORE</a>
                                <a class="shift-delete-bttn" href="#"
                                   onclick="PERMANENTDELETE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');">PERMANENT
                                    DELETE</a>
                            <?php } ?>
                        </td>

                        </td>
                    </tr>

                    <?php
                }
                ?>
                </tbody>
            </table>

            <?php } ?>

        </div>


        <div class="pagination clearfix">
            <ul><?php echo $this->pagination->create_links(); ?></ul>
        </div>


    </div>

</div>

<script>
    $('.publish_class').click(function () {

        $(this).parent().parent().next().find('.show_form').toggle();
    });


</script>

</body>
</html>

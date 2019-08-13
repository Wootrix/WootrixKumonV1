<script src="js/jquery.js"></script>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<!-- Load jQuery and the validate plugin -->
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

<!-- Define some CSS -->
<style type="text/css">
    .label {
        width: 100px;
        text-align: right;
        float: left;
        padding-right: 10px;
        font-weight: bold;
    }

    add_subadmin.error, .output {
        color: #FFFFFF;
        font-weight: bold;
    }
</style>


<script>
    $(document).ready(function () {
        $(".review").click(function () {
            var id = $(this).attr('id');//alert(id);
            $('#advertise_id').val(id);
            $(".layer-bg").fadeIn();
        });

        $(".close-bttn, .layer-bg-popupOverlay").click(function () {
            $(".layer-bg").fadeOut();
        });
    });
</script>

<script>
    /*Sort by status*/
    function StatusData(val) {
        var rowId = val;
        $('#sort_status').submit();
    }

    /* GET LANGUAGE ID AND FETCH LANGUAGE DATA*/

    function LanguageData(val, catId, langId) {
        window.location.href = "<?php echo base_url();?>index.php/advertiselisting?catagory=" + catId + "&lang=" + val;
    }

    /* SORT CATAGORY DATA*/

    function CatagoryData(val, catId, langId) {
        var rowId = val;//      
        window.location.href = "<?php echo base_url();?>index.php/advertiselisting?catagory=" + val + "&lang=" + langId;
        //$('#catagory_form').submit();
    }

    /*PERFORMING DELETE < EDIT AND OTHER OPERATION*/

    function DELETE(val) {
        var tid1 = val;
        var disable = confirm('<?php echo $this->lang->line("Are_you_sure_want_to_delete_this_record"); ?>');
        if (disable === true) {
            //alert(tid1);
            $.ajax({
                type: "POST",
                data: {Id: tid1},
                url: "<?php echo $this->config->base_url(); ?>index.php/advertisedelete",
                success: function (data) {
                    //console.log(data);
                    location.reload();

                }

            });

        }
    }

    /*RESTORE DELETED ADVERTISE*/

    function RESTORE(val) {
        var tid1 = val;
        var disable = confirm('<?php echo $this->lang->line("Are_you_sure_want_to_restore_this_record"); ?>');
        if (disable === true) {
            //alert(tid1);
            $.ajax({
                type: "POST",
                data: {Id: tid1},
                url: "<?php echo $this->config->base_url(); ?>index.php/advertiserestore",
                success: function (data) {
                    //console.log(data);
                    location.reload();

                }

            });

        }
    }

    /*RESTORE DELETED ADVERTISE*/

    function SHIFTDELETE(val) {
        var tid1 = val;
        //alert(tid1);
        var disable = confirm('<?php echo $this->lang->line("Are_you_sure_want_to_perdelete_this_record"); ?>');
        if (disable === true) {
            $.ajax({
                type: "POST",
                data: {Id: tid1},
                url: "<?php echo $this->config->base_url(); ?>index.php/advertise_perma_delete",
                success: function (data) {
                    //console.log(data);
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
        <h4><?php echo $this->lang->line('Advertisements') ?></h4>
        <a class="add-new" href="<?php echo $this->config->base_url() . 'index.php/addadvertise' ?>">Add New</a>

    </div>
</div>

<nav class="clearfix">
    <div class="fix-container clearfix">
        <ul>
            <li class="active"><a href="advertiselisting"><?php echo $this->lang->line('All') ?> <span
                            class="blue"><?php echo $all_advertise; ?></span></a></li>
            <li><a href="publishadvertiselisting"><?php echo $this->lang->line('Published') ?> <span
                            class="green"><?php echo $all_publish_advertise; ?></span></a></li>
            <li><a href="draftadvertiselisting"><?php echo $this->lang->line('Draft') ?> <span
                            class="yellow"><?php echo $all_drafted_advertise; ?></span></a></li>

            <li><a href="deletedadvertiselisting"><?php echo $this->lang->line('Deleted') ?> <span
                            class="red"><?php echo $all_deleted_advertise; ?></span></a></li>
            <li><a href="reviewadvertiselisting"><?php echo $this->lang->line('Review') ?> <span
                            class="dark-green"><?php echo $all_review_advertise; ?></span></a></li>
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
                    <form id="languge_form" action="<?php echo $this->config->base_url() ?>index.php/advertiselisting" method="POST">
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
                    <form id="catagory_form" action="<?php echo $this->config->base_url() ?>index.php/advertiselisting" method="POST">
                        <select class="choose-lang" onChange="CatagoryData(this.value,'<?php echo $_GET['catagory'] ?>','<?php echo $_GET['lang'] ?>');" name="catagory" id="catagory">
                            <option value="" name=""><?php echo $this->lang->line('Select_Category') ?></option>
                            <?php foreach ($catagory_list as $state) { ?>
                                    <option value="<?php echo $state['id']; ?>" <?php if ($state['id'] == $catagory) { ?> selected="selected" <?php } ?>><?php echo $state['category_name']; ?></option>
                                <?php }
    ?>
                        </select>
                    </form>
                </div>
            </div>
        </div>


    </div>-->

    <div class="category-list">
        <div style="color: red"><p><?php echo $msg;
                echo $this->session->flashdata("msg"); ?>   </p>
            <div style="color: green"><p><?php echo $msg;
                    echo $this->session->flashdata("sus_msg"); ?>   </p></div>
            <?php echo validation_errors(); ?></div>
        <div class="table-container">
            <?php

            if ($data_result != "") { ?>
                <script class="jsbin" src="https://datatables.net/download/build/jquery.dataTables.nightly.js"></script>
                <script>
                    $(document).ready(function () {
                        $('#advertiseBoard').dataTable({"bPaginate": false});
                    });
                </script>
                <table id="advertiseBoard" border="0" cellpadding="0" cellspacing="0">
                    <thead>
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
                    </thead>
                    <tbody>
                    <?php //echo"<pre>"; print_r($data_result); die; ?>
                    <tr> <?php
                        if ($data_result != "") {
                        foreach ($data_result as $value) {
                        $sourceId = $value['source'];
                        //print_r($value);die;
                        ?>

                        <td> <?php $todays_date = strtotime(date("Y-m-d 00:00:00")); ?>
                            <?php if ($value['media_type'] == '1') { ?>
                                <div class="banner-cont"><img
                                            src=" <?php echo $this->config->base_url() . 'assets/Advertise/thumbs/' . $value['cover_image']; ?>"
                                            style="height:80px;width:80px"></div>
                            <?php } else {
                                if ($value['cover_image'] == 'embed') {
                                    preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $value['embed_video'], $matches);
                                    echo $matches[0];
                                } else {
                                    ?>
                                    <div class="banner-cont">
                                        <!--                                     <img class="upper-image" src ="<?php echo $this->config->base_url() . 'images/' . "video-thumb.png"; ?>">-->
                                        <img src="<?php echo $this->config->base_url() . 'assets/Advertise/thumbs/' . $value['cover_image'] . "demo.jpeg"; ?>"
                                             style="height:80px;width:80px"></div>
                                <?php }
                            } ?>
                        </td>
                        <td><?php if ($value['media_type'] == '1') { ?>
                                standard
                            <?php } else { ?>
                                video
                            <?php } ?>

                        </td>
                        <td><?php echo $value['size']; ?></td>
                        <td class="linkbreak"><?php echo $value['link']; ?></td>
                        <td class="linkbreak"><?php echo $value["magazines"]; ?></td>
                        <td>
                            <?php if ($value['status'] == '0') { ?>
                                <spam class="pending-btn"><?php echo $this->lang->line('Draft') ?></spam>
                            <?php } elseif ($value['status'] == '3') { ?>
                                <spam class="rejected-btn"><?php echo $this->lang->line('Deleted') ?></spam>
                            <?php } elseif ($todays_date <= strtotime($value['publish_date_from']) && $value['status'] == '1' && $value['is_approved'] == '0') { ?>
                                <spam class="rejected-btn"><?php echo $this->lang->line('Scheduled') ?></spam>
                            <?php } elseif ($todays_date <= strtotime($value['publish_date_to']) && $todays_date >= strtotime($value['publish_date_from'])) { ?>
                                <spam class="aprove-btn"><?php echo $this->lang->line('Published') ?></spam>
                            <?php }
                            elseif ($todays_date > strtotime($value['publish_date_to']) && $value['status'] == '1') { ?>
                            <spam class="rejected-btn"><?php echo $this->lang->line('Expired') ?></spam>
                            <?php } ?></a>
                        </td>
                        <?php

                        ?>
                        <td><a class="view-btn"
                               href="<?php echo $this->config->base_url(); ?>index.php/viewadvertise?rowId=<?php echo $value['id']; ?>&source=<?php echo $sourceId; ?>">View</a>
                        </td>
                        <td><?php if ($value['status'] == '0') { ?>
                                <a class="review" id="<?php echo $value['id']; ?>" href="#">Publish</a>
                                <a class="edit-icon"
                                   href="<?php echo $this->config->base_url(); ?>index.php/editadvertise?rowId=<?php echo $value['id']; ?>&source=<?php echo $sourceId; ?>">Edit</a>

                                <a class="delete-bttn"
                                   href="<?php echo $this->config->base_url(); ?>index.php/advertisedelete?rowId=<?php echo $value['id']; ?>&source=<?php echo $sourceId; ?>">delete</a>
                            <?php } else if ($value['status'] == '3') { ?>

                                <a class="shift-delete-bttn"
                                   href="<?php echo $this->config->base_url(); ?>index.php/advertise_perma_delete?rowId=<?php echo $value['id']; ?>&source=<?php echo $sourceId; ?>">delete</a>


                                <a class="restore-bttn"
                                   href="<?php echo $this->config->base_url(); ?>index.php/advertiserestore?rowId=<?php echo $value['id']; ?>&source=<?php echo $sourceId; ?>">restore</a>
                            <?php } else { ?>
                                <a class="edit-icon"
                                   href="<?php echo $this->config->base_url(); ?>index.php/editadvertise?rowId=<?php echo $value['id']; ?>&source=<?php echo $sourceId; ?>">Edit</a>

                                <a class="delete-bttn"
                                   href="<?php echo $this->config->base_url(); ?>index.php/advertisedelete?rowId=<?php echo $value['id']; ?>&source=<?php echo $sourceId; ?>">delete</a>
                            <?php } ?>

                        </td>
                    </tr>
                    <?php
                    }
                    }
                    ?>


                    </tbody>

                </table>
            <?php } ?>

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
        <div style="color: red"><p>
            <?php echo validation_errors(); ?></div>
        <div class="popup-inner clearfix">

            <!--            <script>
                $(function() {
                    $("#datefrom").datepicker({yearRange: "1900:3000", dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, minDate: 'today', inline: true});
                });
                        </script>-->

            <div class="input-box clearfix"><label><?php echo $this->lang->line('Publish_Date_From') ?></label> <input
                        type="text" id="dt1" placeholder="enter date" name="datefrom" readonly="true"></div>

            <!--            <script>
                            $(function() {
                                $("#dateto").datepicker({yearRange: "1900:3000", dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, minDate: 'today', inline: true});
                            });
                        </script>-->
            <script>
                $(document).ready(function () {

                    $("#dt1").datepicker({
                        dateFormat: "yy-mm-dd",
                        minDate: 0,
                        onSelect: function (date) {
                            var date2 = $('#dt1').datepicker('getDate');
                            date2.setDate(date2.getDate() + 1);
                            $('#dt2').datepicker('setDate', date2);
                            //sets minDate to dt1 date + 1
                            $('#dt2').datepicker('option', 'minDate', date2);
                        }
                    });
                    $('#dt2').datepicker({
                        dateFormat: "yy-mm-dd",
                        onClose: function () {
                            var dt1 = $('#dt1').datepicker('getDate');
                            var dt2 = $('#dt2').datepicker('getDate');
                            //check to prevent a user from entering a date below date of dt1
                            if (dt2 <= dt1) {
                                var minDate = $('#dt2').datepicker('option', 'minDate');
                                $('#dt2').datepicker('setDate', minDate);
                            }
                        }
                    });
                });
            </script>
            <div class="input-box clearfix"><label><?php echo $this->lang->line('Publish_Date_To') ?></label> <input
                        type="text" id="dt2" placeholder="enter date" name="dateto" readonly="true"></div>

            <div class="input-box clearfix"><label><?php echo $this->lang->line('Display_time') ?></label> <input
                        type="text" id="time" placeholder="enter time" name="time"></div>
            <input type="hidden" name="advertise_id" id="advertise_id">


            <div class="input-box clearfix">
                <input type="submit" value="Save"/>
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
            datefrom: {
                required: true
            },
            dateto: {
                required: true
            },
            time: {
                required: true,
                number: true,
                min: 5
            }
        }

    });
</script>
</body>
</html>
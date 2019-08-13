<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <!--<script src="js/jquery.js"></script>-->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
    <script src="https://code.jquery.com/jquery-1.9.1.js"></script>

    <script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
    <!--script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js" type="text/javascript"></script-->
    <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>/jss/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>/jss/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>/jss/evol.colorpicker.min.js"></script>


    <!-- Define some CSS -->
    <script>
        $(document).ready(function () {
            $(".add-new").click(function () {
                $("#addMagazine , .popup-containerMagzine_bord").fadeIn();
                $('.create_mag').show();
                $('.edit_mag').hide();
            });

            $(".close-bttn").click(function () {
                $("#addMagazine , .popup-containerMagzine_bord").fadeOut();
            });

            $(".edit-icon").click(function () {
                var id = $(this).attr('id');
                $.ajax({
                    type: "GET",
                    data: {Id: id},
                    url: "<?php echo $this->config->base_url(); ?>index.php/web/magazine/magazine_details",
                    success: function (data) {
                        var newData = data.split(",");

                        $('#magazine_id').val(id);
                        $('.title').hide();
                        $('.date_from').hide();
                        $('.date_to').hide();
                        $('.customer').hide();
                        $('.create_mag').hide();
                        $('.edit_mag').show();
                        $("#editMagazime, .popup-containerMagzine_bord, .layer-bg").fadeIn();
                    }

                });
            });

            $(".close-bttn").click(function () {
                $("#editMagazime, .popup-containerMagzine_bord").fadeOut();
                location.reload();
            });
        });

        /* DELETE A ADMIN*/

        /*conform box*/

        function ConfirmDel() {
            var x = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_delete_this_record"); ?>")
            if (x) {
                return true;
            } else {
                return false;
            }
        }


        /*Delete a catagory*/
        function DELETE(rowId) {
            var disable = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_delete_this_record"); ?>");
            if (disable === true) {
                var msg = '';
                $.ajax({
                    type: "POST",
                    data: {val: rowId},
                    dataType: "html",
                    url: "<?php echo $this->config->base_url(); ?>index.php/deletemagazine",
                    success: function (data) { //alert(data);
                        location.reload();
                    }
                });

            }
        }

        function showHideUsersInput(data) {

            if (data == '1') {
                $('.edit_no_of_user').show();
            } else if (data == '2') {
                $('.edit_no_of_user').hide();
            }
        }

        function toggleLocation(source) {
            checkboxes = document.getElementsByName('location[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

    </script>
</head>
<?php
$searchString = '';
$searchString = $this->session->userdata('searchUser');
?>

<div class="subheader">
    <div class="fix-container clearfix">
        <h4><a href="magazinelist"><?php echo $this->lang->line('All_Magazine') ?> </a></h4>
    </div>
</div>

<div class="fix-container clearfix">
    <div class="search-bar">
        <div class="search-box">
            <form id="search" action="<?php echo $this->config->base_url() ?>index.php/magazinelist" method="POST">
                <input type="text" id="username" name="title" size="10"
                       placeholder="<?php echo $this->lang->line('Search_Magazine') ?>"
                       value="<?php echo $searchString; ?>"/>
                <input type="submit" id="submit-button" name="sa" value="search"/>
            </form>
        </div>
    </div>

    <div class="category-list">
        <div class="magzine-container">
            <h2><?php echo $this->lang->line('Magazines') ?></h2>
            <div style="color: red"><p><?php
                    echo $msg;
                    echo $this->session->flashdata("msg");
                    ?>   </p>
                <div style="color: green"><p><?php
                        echo $msg;
                        echo $this->session->flashdata("sus_msg");
                        ?>   </p></div>
            </div>
            <ul class="clearfix">

                <li class="add-new">
                    <div class="magzine add-placeholder">
                        <em></em>
                        <strong><?php echo $this->lang->line('Create_Magazine') ?></strong>
                    </div>
                </li>

                <?php $todays_date = date("Y-m-d") ?>
                <?php
                //echo"<pre>"; print_r($data_result);
                if ($data_result != "") {
                    foreach ($data_result as $value) {
                        ?>
                        <li>
                            <div class="magzine"><a
                                        href="<?php echo $this->config->base_url(); ?>index.php/magazinearticlelist?rowId=<?php echo $value['id']; ?>"><img
                                            src=" <?php echo $this->config->base_url() . 'assets/Magazine_cover/' . $value['cover_image']; ?>">
                                </a></div>

                            <h4><?php echo $value['title']; ?> (<?php echo $value['all_article_count']; ?>)</h4>
                            <h5><?php echo date("Y-M-d", strtotime($value['publish_date_from'])) . ' to ' . date("Y-M-d", strtotime($value['publish_date_to'])); ?></h5>
                            <div class="publish-magzine publish-status">
                                <?php
                                if ($todays_date < $value['publish_date_from']) {
                                    echo $this->lang->line("Scheduled");
                                } else {
                                    echo $this->lang->line("Published");
                                }
                                ?>
                            </div>
                            <a class="edit-icon alignPublish" href="#" id="<?php echo $value['id']; ?>">EDIT</a>
                            <a class="delete-bttn alignPublish" href="#"
                               onClick="DELETE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');"></a>
                            <?php if ($value['new_article_count'] > 0) { ?>
                                <span><?php echo $value['new_article_count']; ?></span>
                            <?php } ?>

                        </li>
                        <?php
                    }
                } else {
                    ?>
                    <div style="color: red"><p><?php echo $this->lang->line('No_RECORD_FOUND'); ?>
                        <p></div>
                <?php } ?>


            </ul>

        </div>

    </div>


    <div class="pagination clearfix">
        <ul><?php echo $this->pagination->create_links(); ?></ul>
    </div>


</div>


<!--Pop up-->
<script type="text/javascript">
    $(document).ready(function () {
        $(".uploadFile").change(function () {
            var filePath = $(this).val();
            if (filePath.substring(0, 2) === "C:") {
                filePath = filePath.slice(12);
            }
            $(this).parent().parent().find("span").html(filePath).addClass('fileName');
        });
    })
</script>


<!-- Load jQuery and the validate plugin -->
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>


<script type="text/javascript">
    function getUsers(name) {


        if (name != '') {
            $.ajax({
                type: "POST",
                data: {'name': name},
                dataType: "html",
                url: "<?php echo $this->config->base_url(); ?>index.php/suggestionlist",
                success: function (data) {

                    //console.log(data);
                    // var source = $.parseJSON(data);
                    //submitButton
                    $('#showDisableMsg').html('');
                    if (data == '') {
                        $('input[type="submit"]').attr('disabled', 'disabled');
                        $('#showDisableMsg').html('please select valid customer');
                    } else {
                        $('input[type="submit"]').removeAttr('disabled');
                        $('.emp-search-div').show();
                        $('.emp-search-div').html(data);
                    }
                },
                error: function () {
                    alert('The source is unavailable!');
                }
            });
        }
    }

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
    $(document).ready(function () {
        $('#cpDiv').colorpicker();

    });

    function validates1() {
        //$('#hiddenColor').val('');
        $('#hiddenColor').val($('.chooseColor').html());

        return true;
    }

    function jumpToBox(name, id) {
        $('#username1').val(name);
        $('#usernameHid').val(id);
        $('.emp-search-div').hide();
    }

    // When the browser is ready...
    $(function () {

        // Setup form validation on the #register-form element
        $("#add_magazine").validate({
            // Specify the validation rules
            rules: {
                title: "required",
                datefrom: "required",
                dateto: "required",
                username: "required",
                usernameHid: "required"
            },
            // Specify the validation error messages
            messages: {
                title: "<?php echo $this->lang->line("Please_enter_title"); ?>",
                datefrom: "<?php echo $this->lang->line("Please_chose_date_from"); ?>",
                dateto: "<?php echo $this->lang->line("Please_chose_date_to"); ?>",
                username: "<?php echo $this->lang->line("Select_customer"); ?>",
            },
            submitHandler: function (form) {
                if ($('#magazine_id').val() != '') {
                } else {

                    if ($('#codeType').val() == '2') {
                    } else if ($('#codeType').val() == '1' && $("#no_user").val() == '') {
                        $(".error_msg_user").css("display", "block");
                        return false;
                    }
                    if ($('#cover_pic').val() == '') {
                        $("#cover_pic_error").css("display", "block");
                        return false;
                    }
                }
                form.submit();
            }
        });

    });

</script>


<div class="layer-bg" id="addMagazine"></div>
<div class="popup-container popup-containerMagzine_bord clearfix create-magazine-popup">
    <div class="close-bttn"></div>
    <h4 class="create_mag"><?php echo $this->lang->line('Create_Magazine') ?></h4>
    <h4 class="edit_mag"><?php echo $this->lang->line('Edit_Magazine') ?></h4>
    <?php
    $attributes = array('name' => 'add_magazine', 'id' => 'add_magazine', 'class' => 'form-horizontal', 'novalidate' => "novalidate");
    echo form_open_multipart($this->config->base_url() . 'index.php/addmagazine', $attributes);
    ?>
    <?php echo validation_errors(); ?>
    <div class="create-magazine clearfix">
        <style type="text/css">
            .label {
                width: 100px;
                text-align: right;
                float: left;
                padding-right: 10px;
                font-weight: bold;
            }

            add_magazine.error, .output {
                color: #FFFFFF;
                font-weight: bold;
            }
        </style>


        <div class="left-colm">
            <div class="input-box title"><label><?php echo $this->lang->line('Title') ?></label>
                <input type="text" id="title" placeholder="enter title" name="title"></div>

            <div class="input-box date_from"><label><?php echo $this->lang->line('Publish_Date_From') ?></label>
                <input class="calender" type="text" id="dt1" placeholder="enter date" name="datefrom" readonly="true">
            </div>

            <div class="input-box date_to"><label><?php echo $this->lang->line('Publish_Date_To') ?></label>
                <input class="calender" type="text" id="dt2" placeholder="enter date" name="dateto" readonly="true">
            </div>
            <div class="input-box coverContainer">
                <label><?php echo $this->lang->line('Cover_Photo') ?></label>
                <div class="drop-banner">
                        <span> 
                        </span>
                    <div class="file-type-container"><input type="file" class="uploadFile" id="cover_pic"
                                                            placeholder="enter pic" name="cover_pic">
                        <div class="arrow"><em></em></div>
                    </div>
                </div>
                <label style="display:none;color:red;"
                       id="cover_pic_error"><?php echo $this->lang->line("Please_upload_cover_pic"); ?></label>
                <p>190px * 240px </p>
            </div>
            <div class="div_images">

                <div class="input-box"><label><?php // echo $this->lang->line('Cover_Photo')    ?></label>
                    <div class="drop-banner"><span></span>
                        <div class="file-type-container"><input type="file" class="uploadFile" id="cover_pic1"
                                                                placeholder="enter pic" name="cover_pic1">
                            <div class="arrow"><em></em></div>
                        </div>
                    </div>
                    <p>Android- ( 480 * 800 )px</p>
                </div>

                <div class="input-box"><label><?php //echo $this->lang->line('Cover_Photo')    ?></label>
                    <div class="drop-banner"><span></span>
                        <div class="file-type-container"><input type="file" class="uploadFile" id="cover_pic2"
                                                                placeholder="enter pic" name="cover_pic2">
                            <div class="arrow"><em></em></div>
                        </div>
                    </div>
                    <p>Android- ( 720 * 1280 )px </p>
                </div>

                <div class="input-box"><label><?php // echo $this->lang->line('Cover_Photo')    ?></label>
                    <div class="drop-banner"><span></span>
                        <div class="file-type-container"><input type="file" class="uploadFile" id="cover_pic3"
                                                                placeholder="enter pic" name="cover_pic3">
                            <div class="arrow"><em></em></div>
                        </div>
                    </div>
                    <p>Android- ( 1080 * 1920 )px</p>
                </div>
                <div class="input-box"><label><?php // echo $this->lang->line('Cover_Photo')    ?></label>
                    <div class="drop-banner"><span></span>
                        <div class="file-type-container"><input type="file" class="uploadFile" id="cover_pic4"
                                                                placeholder="enter pic" name="cover_pic4">
                            <div class="arrow"><em></em></div>
                        </div>
                    </div>
                    <p>Android- ( 600 * 1024 )px </p>
                </div>
                <div class="input-box"><label><?php //echo $this->lang->line('Cover_Photo')    ?></label>
                    <div class="drop-banner"><span></span>
                        <div class="file-type-container"><input type="file" class="uploadFile" id="cover_pic5"
                                                                placeholder="enter pic" name="cover_pic5">
                            <div class="arrow"><em></em></div>
                        </div>
                    </div>
                    <p>Android- ( 1024 * 600 )px</p>
                </div>
                <div class="input-box"><label><?php // echo $this->lang->line('Cover_Photo')    ?></label>
                    <div class="drop-banner"><span></span>
                        <div class="file-type-container"><input type="file" class="uploadFile" id="cover_pic6"
                                                                placeholder="enter pic" name="cover_pic6">
                            <div class="arrow"><em></em></div>
                        </div>
                    </div>
                    <p>Android- ( 800 * 1280 )px </p>
                </div>
            </div>
        </div>

        <div class="right-colm">

            <div class="input-box">
                <label><?php echo $this->lang->line('reader_inclusion') ?></label>
                <select name="codeType" id="codeType" onchange="showHideUsersInput(this.value);">
                    <option value="1">Individual code</option>
                    <option value="2">Unique code</option>
                </select>
            </div>

            <div class="input-box edit_no_of_user">
                <label><?php echo $this->lang->line('No_of_Users') ?></label>
                <input type="text" placeholder="1000" id="no_user" name="no_user">
                <label class="error_msg_user" style="display:none;color:red;">Please input value</label>
            </div>

            <div class="input-box">
                <label><?php echo $this->lang->line('Customer_Logo') ?></label>
                <div class="drop-banner"><span></span>
                    <div class="file-type-container"><input type="file" class="uploadFile" id="customer_logo"
                                                            placeholder="customer logo" name="customer_logo">
                        <div class="arrow"><em></em></div>
                    </div>
                    <p>140px * 40px </p>
                </div>
            </div>

            <div class="div_images">

                <div class="input-box coverContainer">
                    <label><?php echo $this->lang->line('Cover_Photo') ?></label>
                    <div class="input-box"><label><?php // echo $this->lang->line('Cover_Photo')    ?></label>
                        <div class="drop-banner"><span></span>
                            <div class="file-type-container"><input type="file" class="uploadFile" id="cover_pic7"
                                                                    placeholder="enter pic" name="cover_pic7">
                                <div class="arrow"><em></em></div>
                            </div>
                        </div>
                        <p>Android- ( 1280 * 800 )px</p>
                    </div>
                </div>
                <div class="input-box"><label><?php //echo $this->lang->line('Cover_Photo')    ?></label>
                    <div class="drop-banner"><span></span>
                        <div class="file-type-container"><input type="file" class="uploadFile" id="cover_pic8"
                                                                placeholder="enter pic" name="cover_pic8">
                            <div class="arrow"><em></em></div>
                        </div>
                    </div>
                    <p>Iphones- ( 320 * 568 )px</p>
                </div>

                <div class="input-box"><label><?php // echo $this->lang->line('Cover_Photo')    ?></label>
                    <div class="drop-banner"><span></span>
                        <div class="file-type-container"><input type="file" class="uploadFile" id="cover_pic9"
                                                                placeholder="enter pic" name="cover_pic9">
                            <div class="arrow"><em></em></div>
                        </div>
                    </div>
                    <p>Iphones- ( 1024 * 768 )px</p>
                </div>

                <div class="input-box"><label><?php //echo $this->lang->line('Cover_Photo')    ?></label>
                    <div class="drop-banner"><span></span>
                        <div class="file-type-container"><input type="file" class="uploadFile" id="cover_pic10"
                                                                placeholder="enter pic" name="cover_pic10">
                            <div class="arrow"><em></em></div>
                        </div>
                    </div>
                    <p>Iphones- ( 768 * 1024 )px</p>
                </div>

            </div>

            <div class="input-box customer"><label><?php echo $this->lang->line('Customer') ?></label>
                <input type="text" name="username" id="username1" value="" onkeyup="getUsers(this.value);"
                       autocomplete="off"/>
                <div id="showDisableMsg"></div>
            </div>

            <input type="hidden" name="usernameHid" id="usernameHid">

            <div class="emp-search-div">

            </div>

            <div>

                <div class="selectAll" style="text-align: left;">
                    <input type="checkbox" name='selectall' id='selectall' onClick="toggleLocation(this)"/> Select All
                </div>

                <div class="multiselect-box clearfix fullbox selectCategory" style="text-align: left; width: 100%;">
                    <?php
                    if (!empty($locations)) {
                        foreach ($locations as $value) {
                            $id = $value['country'];
                            $magazine_name = $value['country'];
                            echo '<div class="cbox"><input type="checkbox" class="catagory_class" id="location[]" name="location[]" value="' . $id . '" />' . $magazine_name . '</div>';
                        }
                    }
                    ?>
                </div>

                <label for="location" style="display:none;" generated="true" class="error category_class_error">Por favor,
                    selecione uma localidade.</label>

            </div>

            <br />

            <!--            <label>--><?php //echo "Grupos com Acesso"; ?><!--</label>-->
            <!---->
            <!--            <select name="access_group[]" id="access_group" multiple style="height: 150px;">-->
            <!--                --><?php //foreach ($roles as $role): ?>
            <!--                    <option value="--><?php //echo $role['id']; ?><!--">-->
            <?php //echo $role['group']; ?><!--</option>-->
            <!--                --><?php //endforeach; ?>
            <!--            </select>-->

            <div class="input-box">
                <div class="clearfix"></div>
                <div id="cpDiv"></div>
            </div>

        </div>

        <input type="hidden" name="color" id="hiddenColor" class="hidden_color">

        <div class="input-box"><input type="submit" id="submitButton" value="Save" name='save'
                                      onClick="return validates1()"/></div>
    </div>
    <input type="hidden" id="magazine_id" name="magazine_id" value="">
    <?php echo form_close(); ?>
</div>


<div class="layer-bg" id="editMagazime"></div>
<!--         Load jQuery and the validate plugin   -->
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<!--        <div class="popup-container clearfix create-magazine-popup">-->


<script>
    $(document).ready(function () {
        //$('#cpDiv').colorpicker({color: '#31859b'});
        $('#cpDiv2').colorpicker();

        $('.evo-palette td').click(function () {
            var getcolor = rgb2hex($(this).css("background-color"));
            //alert(getcolor);
            $('.hidden_color').val(getcolor);
        });
    });

    //            function validates() {//alert($('.chooseColor').html());
    //                //$('.hidden_color').val($('.chooseColor').html());
    //                return true;
    //            }
    function rgb2hex(rgb) {
        rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        return "#" +
            ("0" + parseInt(rgb[1], 10).toString(16)).slice(-2) +
            ("0" + parseInt(rgb[2], 10).toString(16)).slice(-2) +
            ("0" + parseInt(rgb[3], 10).toString(16)).slice(-2);
    }


</script>
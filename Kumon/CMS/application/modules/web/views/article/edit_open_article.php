<!doctype html>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="<?php echo $this->config->base_url() . 'js/ckeditor/ckeditor.js'; ?>"></script>

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

<script language="JavaScript">

    $(document).ready(function () {
        if ($('#media_type_v:checked').length > 0) {
            $('#media_type_s').removeAttr('checked');
            $('#video_div').show();
            $('#embed_div').show();
            $('#video_div_thumb').show();
        }
        if ($('#media_type_s:checked').length > 0) {
            $('#media_type_v').removeAttr('checked');
            $('#media_type_s').attr('checked', 'checked');
            $('#embed_div').hide();
            $('#video_div').hide();
            $('#video_div_thumb').hide();


        }
    });

    function toggle(source) {
        checkboxes = document.getElementsByName('catagory[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    function toggleMag(source) {
        checkboxes = document.getElementsByName('magazine[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    function toggleGroup(source) {
        checkboxes = document.getElementsByName('group[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    function toggleLocation(source) {
        checkboxes = document.getElementsByName('location[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    function toggleDiscipline(source) {
        checkboxes = document.getElementsByName('discipline[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    function toggleBranch(source) {
        checkboxes = document.getElementsByName('branch[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    /* for uncheck check box select all option */
    function removeall() { //alert('hello');
        document.getElementById("selectall").checked = false;
    }
</script>


<body>
<div class="fix-container clearfix">
    <div class="add-article">
        <?php
        //echo "<pre>";print_r($article_data);die;
        $attributes = array('name' => 'add_article', 'id' => 'add_article', 'class' => 'form-horizontal');
        echo form_open_multipart($this->config->base_url() . 'index.php/editopenarticle', $attributes);
        ?>
        <?php //echo"<pre>"; print_r($article_data);?>
        <div style="color: red"><p><?php echo $msg;
                echo $this->session->flashdata("msg");
                ?>   </p>
            <?php echo validation_errors(); ?></div>

        <div class="add-article-form show_form">
            <?php
            if ($article_data['media_type'] == '') {
                $media_type = '0';
            } else {
                $media_type = $article_data['media_type'];
            }
            ?>

            <div class="left-grid">
                <div class="input-box">
                    <label><?php echo $this->lang->line("Title"); ?>:</label>
                    <input id="title" type="text" name="title" value="<?php echo $article_data['title']; ?>">
                </div>

                <div class="input-box">
                    <label><?php echo $this->lang->line("Upload_Image"); ?>:</label>
                    <div class="add-photo"><em><input type="file" name="cover_pic" id="cover_pic"/></em>
                        <?php
                        $img = explode(':', $article_data['image']);
                        //echo"<pre>"; print_r($img); die;
                        if ($img[0] == "http") {
                            ?>
                            <img src=" <?php echo $article_data['image']; ?>">
                        <?php } elseif ($img[0] != "http") { ?>
                            <img src=" <?php echo $this->config->base_url() . 'assets/Article/' . $article_data['image']; ?>">
                        <?php } ?>
                        <div id="preview1" style="display:none; position: absolute; top: 0; left: 0; z-index: 1;"
                             class="imgPlaceholder imgPlaceholderBottom">
                            <img id="previewimg1" src=""/>
                        </div>
                    </div>
                </div>

                <div class="input-box">
                    <label><?php echo $this->lang->line('Article_Language') ?></label>
                    <select class="" name='articlelang' id="articlelang">
                        <?php $articlelang = $article_data['article_language']; ?>
                        <?php foreach ($language_result as $state) { ?>
                            <option value="<?php echo $state['id']; ?>" <?php if ($state['id'] == $articlelang) { ?> selected="selected" <?php } ?>><?php echo $state['language']; ?></option>
                        <?php }
                        ?>

                    </select>

                </div>

                <?php
                if ($article_data['magazine_id'] != '' && $article_data['magazine_id'] != 0) {
                    $selected_MagazineIdArray = explode(',', $article_data['magazine_id']);
                }
                ?>

                <div class="input-box">
                    <label class="full-width-label"><?php echo $this->lang->line('magazines_list') ?></label>
                    <div class="selectAll">
                        <input type="checkbox" name='selectall' id='selectall' onClick="toggleMag(this)"/> Select All
                    </div>
                    <div class="multiselect-box selectCategory">
                        <?php
                        if ($customer_magazine != '') {
                            foreach ($customer_magazine as $value) {
                                $id = $value['id'];
                                $magazine_name = $value['title'];
                                if (!empty($selected_MagazineIdArray)) {
                                    ?>
                                    <div class="cbox"><input type="checkbox" class="catagory_class" id="magazine[]"
                                                             name="magazine[]"
                                                             value="<?php echo $id ?>" <?php if (in_array($id, $selected_MagazineIdArray)) { ?> checked="checked" <?php } ?> /><?php echo $magazine_name; ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="cbox"><input type="checkbox" class="catagory_class" id="magazine[]"
                                                             name="magazine[]"
                                                             value="<?php echo $id ?>"/><?php echo $magazine_name; ?>
                                    </div>
                                    <?php
                                }
                            }
                        }
                        $selected_MagazineIdArray = array();
                        ?>
                    </div>
                    <label for="catagory" style="display:none;" generated="true"
                           class="error category_class_error"><?php echo $this->lang->line("Please_select_catagory") ?>
                        .</label>
                </div>

            </div>

            <div class="right-grid">

                <div class="input-box">

                    <label class="full-width-label">Tipo de usuário</label>

                    <div class="selectAll">
                        <input type="checkbox" name='selectall' id='selectall' onClick="toggleGroup(this)"/> Select All
                    </div>

                    <div class="multiselect-box clearfix fullbox selectCategory">
                        <?php
//                        echo "<pre>"; print_r($articleGroups);
                        if (!empty($groups)) {
                            foreach ($groups as $value) {
                                $id = $value['id'];
                                $magazine_name = $value['name'];
                                ?>
                                <div class="cbox">
                                    <input type="checkbox" <?php echo in_array($id, $articleGroups) ? "checked" : ""; ?> class="catagory_class" id="group[]" name="group[]" value="<?php echo $id; ?>" />
                                    <?php echo $magazine_name; ?>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>

                    <label for="catagory" style="display:none;" generated="true" class="error category_class_error"><?php echo $this->lang->line("Please_select_magazine") ?>.</label>

                </div>

                <div class="input-box">

                    <label class="full-width-label">Localidade</label>

                    <div class="selectAll">
                        <input type="checkbox" name='selectall' id='selectall' onClick="toggleLocation(this)"/> Select All
                    </div>

                    <div class="multiselect-box clearfix fullbox selectCategory">
                        <?php
                        if (!empty($locations)) {
                            foreach ($locations as $value) {
                                $id = $value['id'];
                                $magazine_name = $value['city'];
                                ?>
                                <div class="cbox">
                                    <input type="checkbox" <?php echo in_array($id, $articleLocation) ? "checked" : ""; ?> class="catagory_class" id="location[]" name="location[]" value="<?php echo $id; ?>" />
                                    <?php echo $magazine_name; ?>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>

                    <label for="location" style="display:none;" generated="true" class="error category_class_error">Por favor, selecione uma localidade.</label>

                </div>

                <div class="input-box">

                    <label class="full-width-label">Matéria</label>

                    <div class="selectAll">
                        <input type="checkbox" name='selectall' id='selectall' onClick="toggleDiscipline(this)"/> Select All
                    </div>

                    <div class="multiselect-box clearfix fullbox selectCategory">
                        <?php
                        if (!empty($disciplines)) {
                            foreach ($disciplines as $value) {
                                $id = $value['id'];
                                $magazine_name = $value['name'];
                            ?>
                                <div class="cbox">
                                    <input type="checkbox" <?php echo in_array($id, $articleDiscipline) ? "checked" : ""; ?> class="catagory_class" id="discipline[]" name="discipline[]" value="<?php echo $id; ?>" />
                                    <?php echo $magazine_name; ?>
                                </div>

                        <?php
                            }
                        }
                        ?>
                    </div>

                    <label for="catagory" style="display:none;" generated="true" class="error category_class_error">Por favor, selecione uma matéria.</label>

                </div>

                <div class="input-box">

                    <label class="full-width-label">Filial</label>

                    <div class="selectAll">
                        <input type="checkbox" name='selectall' id='selectall' onClick="toggleBranch(this)"/> Select All
                    </div>

                    <div class="multiselect-box clearfix fullbox selectCategory">
                        <?php
                        if (!empty($branches)) {
                            foreach ($branches as $value) {
                                $id = $value['branch'];
                                $magazine_name = $value['branch'];
                                ?>
                                <div class="cbox">
                                    <input type="checkbox" <?php echo in_array($id, $articleBranch) ? "checked" : ""; ?> class="catagory_class" id="branch[]" name="branch[]" value="<?php echo $id; ?>" />
                                    <?php echo $magazine_name; ?>
                                </div>

                                <?php
                            }
                        }
                        ?>
                    </div>

                    <label for="catagory" style="display:none;" generated="true" class="error category_class_error">Por favor, selecione uma filial.</label>

                </div>

            </div>


            <h3>Format<?php //echo $this->lang->line('Advertisement_Format') ?></h3>
            <div class="advertisement-type clearfix">
                <div class="checkbox-container"><input type="checkbox" name="media_type" id="media_type_s"
                                                       value="0" <?php if ($media_type == 0) { ?> checked='checked' <?php } ?> >
                    Standard
                </div>
                <div class="checkbox-container"><input type="checkbox" name="media_type" id="media_type_v"
                                                       value="1" <?php if ($media_type == 1) { ?> checked='checked' <?php } ?>>
                    video
                </div>
            </div>
            <?php
            if ($article_data['via_url'] != '' && $article_data['via_url'] != '0') {
                ?>
                <div class="input-box clearfix">
                    <label>URL :</label>
                    <input type="text" name="url" id="url" class="input-url"
                           value="<?php echo $article_data['via_url']; ?>">
                    <label class="url_error error" style="display:none;">
                        Please enter url </label>
                </div>
            <?php }

            if ($article_data['status'] == '1') {
                ?>
                <div class="input-box clearfix">
                    <label>URL :</label>
                    <input type="text" name="url" id="url" class="input-url"
                           value="<?php echo $article_data['article_link']; ?>">
                    <label class="url_error error" style="display:none;">
                        Please enter url </label>
                </div>


                <div class='input-box clearfix' id="embed_div" style="display:none;">
                    <label><?php echo $this->lang->line('embed_video') ?> :</label>
                    <input id='embed' type='text' name='embed' value='<?php echo $article_data['embed_video'] ?>'>
                </div>

                <!--                    <div class="input-box" id='video_div_thumb' style="display:none;">
                        <label><?php echo $this->lang->line("Upload_thumb_embeded"); ?>:</label>
                        <div class="add-video"><em></em>
                            <input type="file" name="thumb_embeded" id="thumb_embeded" value="<?php echo base_url() . $article_data['embed_video_thumb'] ?>" />
                        </div>
                    </div>-->


                <div class="input-box" style='display:none;' id='video_div_thumb'>
                    <label><?php echo $this->lang->line("Upload_thumb_embeded"); ?>:</label>
                    <div class="add-photo"><em><input type="file" name="thumb_embeded" id="thumb_embeded"/></em>
                        <?php if ($article_data['embed_video_thumb'] != '') { ?>
                            <img src="<?php echo base_url() . $article_data['embed_video_thumb']; ?>"
                                 id="currentImage"/>
                        <?php } ?>
                        <div id="preview1Embed" style="display:none;" class="imgPlaceholder imgPlaceholderBottom">
                            <img id="previewimg1Embed" src=""/>
                        </div>
                    </div>
                </div>

            <?php } ?>


            <div class="input-box" id='video_div' style="display:none;">
                <label><?php echo $this->lang->line("Upload_video"); ?>:</label>
                <div class="add-video"><em></em><input type="file" name="video" id="video"
                                                       value="<?php echo $article_data['video_path'] ?>"/></div>
            </div>
            <label id="video_error" style="display:none; color: red;">Upload or embed video</label>
            <div class="input-box">
                <label><?php echo $this->lang->line("Tags"); ?> :</label>
                <input id="title" type="text" name="tags" value="<?php echo $article_data['tags']; ?>">
            </div>


            <!--div class="input-box" style='display:none;' id='video_div'-->


            <!--                <script>
                $(function() {
                    $("#datefrom").datepicker({yearRange: "1900:3000", dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, minDate: 'today', inline: true});
                });
            </script>-->
            <div class="input-box">
                <label><?php echo $this->lang->line('Publish_Date_From') ?></label>
                <input type="text" id="dt1" placeholder="enter date" name="datefrom"
                       value="<?php if ($article_data['publish_from'] != '0000-00-00') echo $article_data['publish_from']; ?>"
                       readonly="true"></div>
            <!--                <script>
                                $(function() {
                                    $("#dateto").datepicker({yearRange: "1900:3000", dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, minDate: 'today', inline: true});
                                });
                            </script>-->
            <div class="input-box"><label><?php echo $this->lang->line('Publish_Date_To') ?></label>
                <input type="text" id="dt2" placeholder="enter date" name="dateto"
                       value="<?php if ($article_data['publish_to'] != '0000-00-00') echo $article_data['publish_to']; ?>"
                       readonly="true"></div>
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
            <div class="input-box" id="content_h">
                <textarea id="html_content" rows="5" cols="5"
                          name="description"> <?php echo $article_data['description']; ?></textarea>
            </div>
            <input type='hidden' name='article_id' value='<?php echo $article_data['id']; ?>'>
            <div class="input-box">
                <div class="input-container clearfix">
                    <input type="submit" value="Save in Draft" name="save"/>
                    <input type="submit" value="Publish" name="publish"/>
                </div>

            </div>

        </div>
        <input type="hidden" name="crawlerData" value="<?php echo $article_data['created_by'] ?>"/>
        <script type="text/javascript">
            var editor = CKEDITOR.replace('html_content');
            CKFinder.setupCKEditor(editor, '/ckfinder/');
        </script>


        <?php echo form_close(); ?>


        <script>

            // When the browser is ready...
            $(function () {

                // Setup form validation on the #register-form element
                $("#add_article").validate({
                    // Specify the validation rules
                    rules: {
                        title: "required",
                        catagory: "checked",
                        language: "required",
                        datefrom: {
                            required: true
                        },
                        dateto: {
                            required: true
                        }
                    },
                    // Specify the validation error messages
                    messages: {
                        title: "<?php echo $this->lang->line("Please_enter_your_title") ?>",
                        catagory: "<?php echo $this->lang->line("Please_select_catagory") ?>",
                        datefrom: "<?php echo $this->lang->line("Please_select_date_from") ?>",
                        dateto: "<?php echo $this->lang->line("Please_select_date_to") ?>",
                    },
                    submitHandler: function (form) {
                        if ($(".catagory_class:checked").length == 0) {
                            $('.category_class_error').show();
                            return false;
                        }

                        if ($('#media_type_v:checked').length > 0) {

                            var embed = $("#embed").val();
                            var embed_thumb = $("#thumb_embeded").val();
                            var video = $("#video").val();
                            if (embed == '' && embed_thumb == '' && video == '') {
                                $('#video_error').show();
                                return false;
                            }
                            if (video == '' && embed == '') {
                                $('#video_error').show();
                                return false;
                            }
//                                if(embed!='' && embed_thumb==''){
//                                    $('#video_error').show();
//                                    return false;
//                                }
                        }

                        form.submit();
                    }
                });

            });


            /*for language ajax*/
            $('#languageval').change(function () {
                if ($(this).val() != '') {
                    $.ajax({
                        url: "<?php echo base_url() ?>index.php/getCategoryFromLanguage",
                        type: "POST",
                        data: {'id': $(this).val(), 'selec_cat': '<?php echo $selected_catagory; ?>'},
                        success: function (res) {
                            $('.selectCategory').html(res);
                        }
                    });
                } else {
                    $('.selectCategory').html('');
                }
            });

        </script>


        <script>
            <?php if ($media_type == '0') { ?>
            //$('#video_div').hide();
            <?php } else { ?>
            //$('#content_h').hide();
            <?php } ?>
            $(document).ready(function () {
                $('#media_type_v').click(function () {
                    $('#media_type_s').removeAttr('checked');
                    $('#video_div').show();
                    $('#embed_div').show();
                    $('#video_div_thumb').show();
                });
                $('#media_type_s').click(function () {//alert('hey');
                    $('#media_type_v').removeAttr('checked');
                    $('#media_type_s').attr('checked', 'checked');
                    $('#embed_div').hide();
                    $('#video_div').hide();
                    $('#video_div_thumb').hide();


                });
            });

        </script>


    </div>

</div>


<script>

    $("#cover_pic").change(function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = imageIsLoaded1;
            reader.readAsDataURL(this.files[0]);
        }
    })


    function imageIsLoaded1(e) {
        $('#message').css("display", "none");
        $('#preview1').css("display", "block");
        $('#previewimg1').attr('src', e.target.result);
    }
    ;

    // Function for Deleting Preview Image.
    $("#deleteimg1").click(function () {
        $('#preview1').css("display", "none");
        $('#cover_pic').val("");
    });


    //thumbnail
    $("#thumb_embeded").change(function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = imageIsLoaded1Embed;
            reader.readAsDataURL(this.files[0]);
        }
    })


    function imageIsLoaded1Embed(e) {
        $('#message').css("display", "none");
        $('#preview1Embed').css("display", "block");
        $('#currentImage').hide();
        $('#previewimg1Embed').attr('src', e.target.result);
    }
</script>

</body>
</html>
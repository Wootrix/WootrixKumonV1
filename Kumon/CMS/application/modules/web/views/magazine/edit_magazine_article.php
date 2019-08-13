<!doctype html>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="<?php echo $this->config->base_url() . 'js/ckeditor/ckeditor.js'; ?>"></script>

<!-- Load jQuery and the validate plugin -->  
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>


<!-- Define some CSS -->
<style type="text/css">
    .label {width:100px;text-align:right;float:left;padding-right:10px;font-weight:bold;}
    add_subadmin.error, .output {color:#FFFFFF;font-weight:bold;}
</style>
<script>
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
</script>
<body>
    <?php
    //echo"<pre>"; print_r($magazine_info); die; 
    $todays_date = date("Y-m-d");
    $mag_min_date1 = date('Y-m-d', strtotime($magazine_info['publish_date_from']));
    $mag_max_date = date('Y-m-d', strtotime($magazine_info['publish_date_to']));

    if ($mag_min_date1 > $todays_date) {
        $mag_min_date = date('Y-m-d', strtotime($magazine_info['publish_date_from']));
    } else {
        $mag_min_date = $todays_date = date("Y-m-d");
    }
    ?>
    <div class="fix-container clearfix">
        <div class="add-article">
            <?php
            $attributes = array('name' => 'add_article', 'id' => 'add_article', 'class' => 'form-horizontal');
            echo form_open_multipart($this->config->base_url() . 'index.php/editMagazinearticle', $attributes);
            ?>   
            <?php //echo"<pre>"; print_r($article_data);?>

            <div style="color: red"><p><?php echo $msg;
            echo $this->session->flashdata("msg"); ?>   </p>
                <div style="color: green"><p><?php echo $msg;
            echo $this->session->flashdata("sus_msg"); ?>   </p></div>
<?php echo validation_errors(); ?></div>

            <div class="add-article-form show_form">

                <div class="left-grid">
                    <div class="input-box">
                        <label><th><?php echo $this->lang->line('Title') ?></th> :</label>            
                        <input id="title"  type="text" name="title" value="<?php echo $article_data['title']; ?>">
                    </div>

                    <div class="input-box">
                        <label><?php echo $this->lang->line('Upload_Image') ?>:</label>
                        <div class="add-photo"><em><input type="file" name="cover_pic" id="cover_pic" /></em>
                            <img src =" <?php echo $this->config->base_url() . 'assets/Article/' . $article_data['image']; ?>">
                            <div id="preview1" style="display:none; position: absolute; top: 0; left: 0; z-index: 1;"class="imgPlaceholder imgPlaceholderBottom">
                                <img id="previewimg1" src=""/>                        
                            </div>
                        </div>

                    </div>

                </div>

                <div class="right-grid">



                    <div class="input-box">
                        <label class="full-width-label"><?php echo $this->lang->line('Magazine') ?></label>

                        <br>
                        <input type="checkbox" name='selectall' id='selectall' onClick="toggle(this)" /> Select All
                        <div class="multiselect-box clearfix fullbox selectCategory">                     
                            <?php
                            $magazineId=  explode(",",$article_data['magazine_id']);
                           // print_r($magazineId);die;
                            foreach ($magazine_result as $value) {
                                $catagory_id = $value['id'];
                                $catagory_name = $value['title'];
                                ?>
                                       <!--div class="cbox"><input type="checkbox" id="catagory[]" name="catagory[]" value="' . $catagory_id . '" />' . $catagory_name.'</div-->
                            <div class="cbox"> <input type="checkbox" id="magazine[]" name="magazine[]" class="catagory_class" value="<?php echo $catagory_id ?>" <?php if(in_array($catagory_id,$magazineId)){ ?>checked<?php }?>  /> <?php echo $catagory_name ?></div>
                            <?php } ?>
                        </div>

                        <label for="catagory" style="display:none;" generated="true" class="error category_class_error"><?php echo $this->lang->line("Please_select_catagory") ?>.</label>

                    </div>
<!--                    <div class="input-box">
                        <label><?php echo $this->lang->line('Article_Language') ?></label>
                        <select class="" name ='articlelang' id="articlelang">   
                            <?php $articlelang = $article_data['article_language']; ?>
                            <?php foreach ($language_result as $state) { ?>
                                <option value="<?php echo $state['id']; ?>" <?php if ($state['id'] == $articlelang) { ?> selected="selected" <?php } ?>><?php echo $state['language']; ?></option>
                            <?php }
                                ?>
                        </select>

                    </div>-->

                </div>


                <h3>Format<?php //echo $this->lang->line('Advertisement_Format')  ?></h3>
                <div class="advertisement-type clearfix">
                    <div class="checkbox-container"><input type="checkbox" name="media_type" id="media_type_s" value="0" <?php if ($article_data['media_type'] == 0) { ?> checked='checked' <?php } ?> > Standard </div>
                    <div class="checkbox-container"><input type="checkbox" name="media_type" id="media_type_v"  value="1" <?php if ($article_data['media_type'] == 1) { ?> checked='checked' <?php } ?>> video </div>
                </div>
                <?php
if ($article_data['viaUrl'] != '' && $article_data['viaUrl'] != '0') {
    ?>
                    <div class="input-box clearfix">
                        <label>URL :</label>
                        <input type="text" name="url" id="url" class="input-url" value="<?php echo $article_data['viaUrl']; ?>">
                        <label class="url_error error" style="display:none;">
                            Please enter url                    </label>
                    </div>
<?php }?>
                
                <div class="input-box">
                    <label><?php echo $this->lang->line('Tags') ?> :</label>            
                    <input id="title"  type="text" name="tags" value="<?php echo $article_data['tags']; ?>">
                </div>

                    
                <div class="advertisement-type clearfix">
                    <div class="checkbox-container"><input type="checkbox" name="comments" id="media_type" value="1" <?php if ($article_data['allow_comment'] == 1) { ?> checked='checked' <?php } ?>> Allow Comments </div>
                    <div class="checkbox-container"><input type="checkbox" name="share" id="media_type"  value="1" <?php if ($article_data['allow_share'] == 1) { ?> checked='checked' <?php } ?>> Allow Share </div>
                </div>
<?php 
                if($article_data['embed_video']!=''){
                ?>
                <div class='input-box clearfix' id="embed_div">
                    <label><?php echo $this->lang->line('embed_video') ?> :</label>
                    <input id='embed'  type='text' name='embed' value='<?php echo $article_data['embed_video']; ?>'>
                </div>
                
               
<!--                <div class="input-box" id='video_div_thumb'>
                    <label><?php echo $this->lang->line("Upload_thumb_embeded"); ?>:</label>
                    <div class="add-video"><em></em><input type="file" name="thumb_embeded" id="thumb_embeded" /></div>
                </div>-->
                
                
                <div class="input-box" style='display:none;' id='video_div_thumb'>
                        <label><?php echo $this->lang->line("Upload_thumb_embeded"); ?>:</label>
                        <div class="add-photo"><em><input type="file" name="thumb_embeded" id="thumb_embeded" /></em>
                            <img src="<?php echo base_url().$article_data['embed_video_thumb'] ?>" id="currentImg" />
                            <div id="preview1Embed" style="display:none;"class="imgPlaceholder imgPlaceholderBottom">
                                <img id="previewimg1Embed" src=""/>                        
                            </div>
                        </div>                    
                    </div>
                
                
                <?php }?>
                <!--div class="input-box" style='display:none;' id='video_div'-->
                <div class="input-box" id='video_div'>
                    <label><?php echo $this->lang->line('Upload_video') ?>:</label>
                    <div class="add-video"><em></em><input type="file" name="video" id="video" /></div>
                </div>
                

<!--                <script>
    $(function() {
        $("#datefrom").datepicker({yearRange: "1900:3000", dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, minDate: 'today', inline: true});
    });
</script>-->
                <div class="input-box"> 
                    <label><?php echo $this->lang->line('Publish_Date_From') ?></label>
                    <input type="text" id ="dt1" placeholder="enter date" name="datefrom" value="<?php echo $article_data['publish_from']; ?>" readonly="true"></div>
<!--                <script>
                    $(function() {
                        $("#dateto").datepicker({yearRange: "1900:3000", dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, minDate: 'today', inline: true});
                    });
                </script>-->
                <script>
                    $(document).ready(function() {

                        $("#dt1").datepicker({
                            dateFormat: "yy-mm-dd",
                            minDate: "<?php echo $mag_min_date; ?>",
                            onSelect: function(date) {
                                var date2 = $('#dt1').datepicker('getDate');
                                date2.setDate(date2.getDate() + 1);
                                $('#dt2').datepicker('setDate', date2);
                                //sets minDate to dt1 date + 1
                                $('#dt2').datepicker('option', 'minDate', date2);
                            }
                        });
                        $('#dt2').datepicker({
                            dateFormat: "yy-mm-dd",
                            //maxDate:"<?php echo $mag_max_date; ?>",        
                            onClose: function() {
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

                <div class="input-box"><label><?php echo $this->lang->line('Publish_Date_To') ?></label>
                    <input type="text" id ="dt2" placeholder="enter date" name="dateto" value="<?php echo $article_data['publish_to']; ?>" readonly="true"></div>

                <div class="input-box" id="content_h">                           
                    <textarea id="html_content" rows="5" cols="5" name="description"> <?php echo $article_data['description']; ?></textarea>
                </div>            
                <input type='hidden' name='article_id' value='<?php echo$article_data['id']; ?>'>
                <input type="hidden" name="magazine_id" value="<?php echo $_REQUEST['rowId']; ?>">
                <div class="input-box">
                    <div class="input-container clearfix"> 
                        <input type="submit" value="cancel" name ="cancel" />
                        <input type="submit" value="publish" name ="publish" />
                    </div>          
                </div>

            </div>

            <script type="text/javascript">
                var editor = CKEDITOR.replace('html_content');
                CKFinder.setupCKEditor(editor, '/ckfinder/');
            </script>



<?php echo form_close(); ?>  


            <script>

                /*When the browser is ready...*/
                $(function() {

                    // Setup form validation on the #register-form element
                    $("#add_article").validate({
                        // Specify the validation rules
                        rules: {
                            title: {
                                required: true
                            },
                            datefrom: {
                                required: true
                            },
                            dateto: {
                                required: true
                            }
                        },
                        // Specify the validation error messages
                        messages: {
                            title: '<?php echo $this->lang->line("Please_enter_your_title") ?>',
                            catagory: '<?php echo $this->lang->line("Please_select_catagory") ?>',
                            datefrom: '<?php echo $this->lang->line("Please_select_date_from") ?>',
                            dateto: '<?php echo $this->lang->line("Please_select_date_to") ?>',
                        },
                        submitHandler: function(form) {
                            if ($(".catagory_class:checked").length == 0) {
                                $('.category_class_error').show();
                                return false;
                            }
                            form.submit();
                        }
                    });

                });


                /*for language ajax*/
                /*for language ajax*/
                $('#languageval').change(function() {
                    if ($(this).val() != '') {
                        $.ajax({
                            url: "<?php echo base_url() ?>index.php/getCategoryFromLanguage",
                            type: "POST",
                            data: {'id': $(this).val(), 'selec_cat': '<?php echo $selected_catagory; ?>'},
                            success: function(res) {
                                $('.selectCategory').html(res);
                            }
                        });
                    } else {
                        $('.selectCategory').html('');
                    }
                });

            </script>


            <script>

<?php if ($article_data['media_type'] == '0') { ?>
                    $('#video_div').hide();
<?php } else { ?>
                    //$('#content_h').hide();
<?php } ?>

                $(document).ready(function() {//alert('y');
                    $('#media_type_v').click(function() {//alert('hey');
                        $('#media_type_s').removeAttr('checked');
                        $('#video_div').show();
                        $('#embed_div').show();
                        $('#video_div_thumb').show();
                        //$('#media_type_v').attr('checked','checked');

                    });
                    $('#media_type_s').click(function() {//alert('hey');
                        $('#media_type_v').removeAttr('checked');
                        $('#media_type_s').attr('checked', 'checked');
                        //$('#content_h').show();
                        $('#video_div').hide();
                        $('#embed_div').hide();
                        $('#video_div_thumb').hide();

                    });
                });

            </script>


        </div>

    </div>
    <script>

        $("#cover_pic").change(function() {
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
        $("#deleteimg1").click(function() {
            $('#preview1').css("display", "none");
            $('#cover_pic').val("");
        });
        
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
            $("#currentImg").hide();
            $('#previewimg1Embed').attr('src', e.target.result);
        }
        
        
        function toggle(source) {
        checkboxes = document.getElementsByName('magazine[]');
        for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
        }
        }
        
        /* for uncheck check box select all option */
        function removeall(){ //alert('hello');
            document.getElementById("selectall").checked = false;
        }
    </script>

</body>
</html>
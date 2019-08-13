<!doctype html>

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
<body>
    <div class="fix-container clearfix">
    <div class="add-new-customer clearfix">
        <?php //echo"<pre>"; print_r($data_result); ?>
        <?php
        $attributes = array('name' => 'add_subadmin', 'id' => 'add_subadmin', 'class' => 'form-horizontal');
        echo form_open_multipart($this->config->base_url() . 'index.php/editsubadmin?rowId='.$_GET['rowId'], $attributes);
        ?>   

        <div style="color: red"><p><?php echo $msg;  echo $this->session->flashdata("msg");?>   </p>
            <?php echo validation_errors(); ?></div>

        <div>
            <div class="long-strip">
                    <div class="image-cont-box"> 
                        <?php if ($data_result['image'] == "") { ?>
                            <img src="<?php echo $this->config->base_url() . 'images/profile-pic.png'; ?>"/>
                        <?php } else { ?> 
                            <img src="<?php echo $this->config->base_url() . 'assets/customer_img/' .
                        $data_result['image'];
                            ?>"/>
                        <?php } ?>
                     <div id="preview1" style="display:none; position: absolute; top: 0; left: 0; z-index: 1;"class="imgPlaceholder imgPlaceholderBottom">
                        <img id="previewimg1" src=""/>                        
                     </div>
                    
                    <div class="upload-image">
                    <input type="file" name="profilepic" id="profilepic" />
                    </div>
                    </div>
                    <span><?php echo $this->lang->line('Upload_Image') ?>:</span>
                </div>
           <div class="input-field-cont">
            <label><?php echo $this->lang->line('Name') ?>:</label>            
            <input id="name"  type="text" name="name" value="<?php echo$data_result['name']; ?>" maxlength="50">
            </div>
             <div class="input-field-cont">
            <label><?php echo $this->lang->line('Gender') ?> :</label>            
            <select id="gender" name="gender" />
            <option selected><?php echo $this->lang->line('select_gender') ?></option>
            <option value="f" <?php if($data_result['gender'] == 'f') echo ' selected="selected"' ?> ><?php echo $this->lang->line('Female') ?></option>
            <option value="m" <?php if($data_result['gender'] == 'm') echo ' selected="selected"' ?>><?php echo $this->lang->line('Male') ?></option>
            </select>
            </div>

            <script>
                $(function() {
                    $("#dob").datepicker({yearRange: "1900:3000", dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, maxDate: new Date()});
                });
            </script>
            <div class="input-field-cont">
            <label><?php echo $this->lang->line('Born') ?>:</label>            
            <input id="dob"  type="text" name="dob" value="<?php if(!$data_result['dob'] == '0000-00-00'){ echo$data_result['dob']; }  ?>" readonly="true">
            </div>
             <div class="input-field-cont">
            <label><?php echo $this->lang->line('Email') ?> :</label>            
            <input id="email"  type="text" name="email" value="<?php echo$data_result['email']; ?>">
            </div>

             <div class="input-field-cont">
            <label><?php echo $this->lang->line('Company_Name') ?>:</label>            
            <input id="company_name"  type="text" name="company_name" value="<?php echo$data_result['company_name']; ?>">
            </div>
            
             <div class="input-field-cont">

            <label><?php echo $this->lang->line('Work_Phone') ?>:</label>            
            <input id="work_phone"  type="text" name="work_phone" value="<?php echo$data_result['work_phone']; ?>">
            </div>
            
             <div class="input-field-cont">

            <label><?php echo $this->lang->line('Mobile') ?>:</label>            
            <input id="mobile"  type="text" name="mobile" value="<?php echo$data_result['mobile']; ?>">
            </div>
            
             <div class="input-field-cont">

            <label><?php echo $this->lang->line('City') ?>:</label>            
            <input id="city"  type="text" name="city" value="<?php echo$data_result['city']; ?>">
            </div>
            
             <div class="input-field-cont">

            <label><?php echo $this->lang->line('Country') ?>:</label>            
            <input id="country"  type="text" name="country" value="<?php echo$data_result['country']; ?>">
            </div>
            
             <!--div class="input-field-cont">

            <label><?php echo $this->lang->line('Address') ?>:</label>            
            <input id="address"  type="text" name="address" value="<?php echo$data_result['address']; ?>">
            </div-->
             <div class="input-field-cont long-strip">
            <label><?php echo $this->lang->line('Address') ?></label>
            <textarea cols="40" rows="5" name="address" id="address"> <?php echo$data_result['address'];  ?> </textarea>            
            </div>
        </div>
        <div class="login-credential clearfix">
            <h2> <?php echo $this->lang->line('Login_Credentials') ?></h2>
             <div class="input-field-cont">
            <label><?php echo $this->lang->line('User_Name') ?>:</label>            
            <input id="user_name"  type="text" name="user_name" value="<?php echo$data_result['user_name']; ?>" maxlength="50" readonly="true">
            </div>
            
             <div class="input-field-cont">
            <label><?php echo $this->lang->line('Password') ?>:</label>            
            <input id="password"  type="password" name="password" value="">
            </div>
            <?php if($data_result['role'] != 1) {?>
            
            <div class="right-cols-nw">
            <label><?php echo $this->lang->line('Permission') ?>:</label>            
            <?php //echo"<pre>";
            //print_r($permission); ?>
            <div class="multiselect-box clearfix">    
           <div class="cbox"> <input type="checkbox" class="p_class" name="permission1" value="1" <?php if($data_result['permission1'] == '1') echo ' checked="checked"' ?>> Categories:  </div>
           <div class="cbox"><input type="checkbox"  class="p_class" name="permission2" value="1" <?php if($data_result['permission2'] == '1') echo ' checked="checked"' ?>> Manage Customers:  </div>
           <div class="cbox"><input type="checkbox" class="p_class" name="permission3" value="1" <?php if($data_result['permission3'] == '1') echo ' checked="checked"' ?>> Advertisement:</div>
           <div class="cbox"><input type="checkbox" class="p_class" name="permission4" value="1" <?php if($data_result['permission4'] == '1') echo ' checked="checked"' ?>> Magazine: </div>
           <div class="cbox"><input type="checkbox" class="p_class" name="permission5" value="1" <?php if($data_result['permission5'] == '1') echo ' checked="checked"' ?>> Open Article:  </div>
           <div class="cbox">  <input type="checkbox" class="p_class" name="permission6" value="1" <?php if($data_result['permission6'] == '1') echo ' checked="checked"' ?>> Change Language:</div>
           <div class="cbox"><input type="checkbox" class="p_class" name="permission7" value="1" <?php if($data_result['permission7'] == '1') echo ' checked="checked"' ?>>Admins </div>
           <div class="cbox"><input type="checkbox" class="p_class" name="permission8" value="1" <?php if($data_result['permission8'] == '1') echo ' checked="checked"' ?>>History </div>
            </div>
            <label for="permission" style="display:none;" generated="true" class="error error_permission"><?php echo $this->lang->line('Please_select_permission') ?></label>
            </div>
            
            <?php } ?>
        </div>


        <div class="input-container clearfix">
            <input type="hidden" value="<?php echo$data_result['id']; ?>" name ="admin_id" />
            <input type="submit" value="save" name ="save" />
        </div>
        </div>


<?php echo form_close(); ?>  


        <script>

            // When the browser is ready...
            $(function() {

                // Setup form validation on the #register-form element
                $("#add_subadmin").validate({
                    // Specify the validation rules
                    rules: {
                        name: "required",
                        email: {
                            required: true,
                            email: true
                        },
                        user_name: "required",                        
                        work_phone:{
                            number: true,
                            maxlength:15
                        },
                        mobile:{
                            number: true,
                            maxlength:15                            
                        }
                    },
                    // Specify the validation error messages
                    messages: {
                         name: "<?php echo $this->lang->line("Please_enter_your_name") ?>",
                        email: "<?php echo $this->lang->line("Please_enter_a_valid_email_address") ?>",
                        username: "<?php echo $this->lang->line("Please_enter_a_valid_username") ?>",
                        work_phone: "<?php echo $this->lang->line("Please_enter_a_valid_work_phon") ?>",
                        mobile: "<?php echo $this->lang->line("Please_enter_valid_mobile_number") ?>",                        
                     password: {
                            required: "<?php echo $this->lang->line("Please_provide_a_password") ?>",
                            minlength: "Your password must be at least 5 characters long"
                        }
                    },
                    submitHandler: function(form) {
                if($('.p_class:checked').length==0){
                            $('.error_permission').show();
                            return false;
                        }
                        form.submit();
                    }
                });

            });

        </script>


    </div>

<script>
    
$("#profilepic").change(function() {
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
};

// Function for Deleting Preview Image.
$("#deleteimg1").click(function() {
$('#preview1').css("display", "none");
$('#profilepic').val("");
});
</script>

<style>
    .error_permission{color:red;}
    
</style>
</body>
</html>
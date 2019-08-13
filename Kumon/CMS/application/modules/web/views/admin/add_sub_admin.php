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
        <?php
        $attributes = array('name' => 'add_subadmin', 'id' => 'add_subadmin', 'class' => 'form-horizontal');
        echo form_open_multipart($this->config->base_url() . 'index.php/addsubadmin', $attributes);
        ?>   

        <div style="color: red"><p><?php echo $msg;  echo $this->session->flashdata("msg");?>   </p>
            <?php echo validation_errors(); ?></div>

        <div>
            <div class="long-strip">
                    <div class="image-cont-box">
                     <div class="drop-banner"><span></span>
                         <div class="upload-image"><input type="file" name="profilepic" id="profilepic" class="uploadFile"/> </div>
                         <div id="preview1" style="display:none;"class="imgPlaceholder imgPlaceholderBottom">
                        <img id="previewimg1" src=""/>                        
                        </div>
                    
                    </div>
                    </div>
                    <span><?php echo $this->lang->line('Upload_Image') ?>:</span>
                </div>
           <div class="input-field-cont">
            <label><?php echo $this->lang->line('Name') ?> :</label>            
            <input id="name"  type="text" name="name" value="" maxlength="50">
            </div>
             <div class="input-field-cont">
            <label><?php echo $this->lang->line('Gender') ?> :</label>            
            <select id="gender" name="gender" />
            <option selected><?php echo $this->lang->line('select_gender') ?></option>
            <option value="f"><?php echo $this->lang->line('Female') ?></option>
            <option value="m"><?php echo $this->lang->line('Male') ?></option>
            </select>
            </div>

            <script>
                $(function() {
                    $("#dob").datepicker({yearRange: "1900:3000", dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, maxDate: new Date()});
                });
            </script>
            <div class="input-field-cont">
            <label><?php echo $this->lang->line('Born') ?> :</label>            
            <input id="dob"  type="text" name="dob" value="" readonly="true">
            </div>
             <div class="input-field-cont">
            <label><?php echo $this->lang->line('Email') ?> :</label>            
            <input id="email"  type="text" name="email" value="">
            </div>

             <div class="input-field-cont">
            <label><?php echo $this->lang->line('Company_Name') ?> :</label>            
            <input id="company_name"  type="text" name="company_name" value="">
            </div>
            
             <div class="input-field-cont">

            <label><?php echo $this->lang->line('Work_Phone') ?> :</label>            
            <input id="work_phone"  type="text" name="work_phone" value="" maxlength="15">
            </div>
            
             <div class="input-field-cont">

            <label><?php echo $this->lang->line('Mobile') ?>:</label>            
            <input id="mobile"  type="text" name="mobile" value="">
            </div>
            
             <div class="input-field-cont">

            <label><?php echo $this->lang->line('City') ?>:</label>            
            <input id="city"  type="text" name="city" value="">
            </div>
            
             <div class="input-field-cont">

            <label><?php echo $this->lang->line('Country') ?>:</label>            
            <input id="country"  type="text" name="country" value="">
            </div>
            
             <div class="input-field-cont">

            <label><?php echo $this->lang->line('Address') ?>:</label>
            <textarea cols="40" rows="5" name="address" id="address"> </textarea>            
            </div>
        </div>
        <div class="login-credential clearfix">
            <h2> <?php echo $this->lang->line('Login_Credentials') ?></h2>
             <div class="input-field-cont">
            <label><?php echo $this->lang->line('User_Name') ?>:</label>            
            <input id="user_name"  type="text" name="user_name" value="" maxlength="50">
            </div>
            
             <div class="input-field-cont">
            <label><?php echo $this->lang->line('Password') ?>:</label>            
            <input id="password"  type="password" name="password" value="">
            </div>
            
            <div class="right-cols-nw">
            <label><?php echo $this->lang->line('Permission') ?>:</label>        
            <?php //echo"<pre>";
            //print_r($permission); ?>
            <div class="multiselect-box clearfix">    
                <div class="cbox"><input type="checkbox" class="p_class" name="permission1" value="1" >Categories </div>
                <div class="cbox"><input type="checkbox" class="p_class" name="permission2" value="1">Manage Customers </div>
                <div class="cbox"><input type="checkbox" class="p_class" name="permission3" value="1">Advertisement</div>
                <div class="cbox"><input type="checkbox" class="p_class" name="permission4" value="1">Magazine </div>
                <div class="cbox"><input type="checkbox" class="p_class" name="permission5" value="1">Open Article </div>
                <div class="cbox"><input type="checkbox" class="p_class" name="permission6" value="1">Change Language </div>
                <div class="cbox"><input type="checkbox" class="p_class" name="permission7" value="1">Admins </div>
                <div class="cbox"><input type="checkbox" class="p_class" name="permission8" value="1">History </div>
            </div>
            <label for="permission" generated="true" style="display:none;" class="error error_permission"><?php echo $this->lang->line('Please_select_permission') ?></label>
            </div>
            
        </div>


        <div class="input-container clearfix"> 
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
                        password: {
                            required: true,
                            minlength: 5
                        },
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
                        name: "<?php echo$this->lang->line('Please_enter_your_name') ?>",
                        email: "<?php echo$this->lang->line('Please_enter_a_valid_email_address') ?>",
                        username: "<?php echo$this->lang->line('Please_enter_a_valid_username') ?>",
                        work_phone: "<?php echo$this->lang->line('Please_enter_a_valid_work_phon') ?>",
                        mobile: "<?php echo$this->lang->line('Please_enter_valid_mobile_number') ?>",
                        password: {
                            required: "<?php echo$this->lang->line('Please_provide_a_password') ?>",
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
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>::Wootrix | Category::</title>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<!-- Load jQuery and the validate plugin -->  
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
            <script>
                $(function() {
                    $("#dob").datepicker({yearRange: "1900:3000", dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, maxDate: new Date()});
                });
            </script>
    
            
            
<!-- Define some CSS -->
<style type="text/css">
    .label {width:100px;text-align:right;float:left;padding-right:10px;font-weight:bold;}
    add_subadmin.error, .output {color:#FFFFFF;font-weight:bold;}
</style>
    
    
    </head>
<body>

    
    
    <div class="fix-container clearfix">
    <div class="add-new-customer clearfix">
        <?php
        $attributes = array('name' => 'add_customer', 'id' => 'add_customer', 'class' => 'form-horizontal');
        echo form_open_multipart($this->config->base_url() . 'index.php/addcustomer', $attributes);
        ?>   

        <div style="color: red"><p><?php echo $msg;  echo $this->session->flashdata("msg");?>   </p>
            <?php echo validation_errors(); ?></div>

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
                <label><?php echo $this->lang->line('Name') ?></label>            
                <input id="name"  type="text" name="name" value="" placeholder="<?php echo $this->lang->line('enter_your_name') ?>" maxlength="50">
            </div>
      
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('Born') ?> :</label>            
                <input id="dob"  type="text" name="dob" value="" placeholder="<?php echo $this->lang->line('enter_date_of_birth') ?>" readonly="true">
            </div>
        
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('Gender') ?></label>            
                <select id="gender" name="gender" >
                    <option selected><?php echo $this->lang->line('select_gender') ?></option>    
                <option value="f"><?php echo $this->lang->line('Female') ?></option>
                <option value="m"><?php echo $this->lang->line('Male') ?></option>
                </select>
            </div>    

        
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('Email') ?></label>            
                <input id="email"  type="text" name="email" value="" placeholder="enter your email">
            </div>
            
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('Company_Name') ?></label>            
                <input id="company_name"  type="text" name="company_name" value="" placeholder="<?php echo $this->lang->line('enter_company_name') ?>">
            </div>
        
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('Work_Phone') ?></label>
                <!--<input type="text" readonly value="+91" style="width: 45px;">-->
                <input id="work_phone"  type="text" maxlength="15" name="work_phone" value="" placeholder="<?php echo $this->lang->line('enter_work_phone') ?>">
            </div>
            
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('Mobile') ?></label>
                <!--<input type="text" readonly value="+91" style="width: 45px;">-->
                <input id="mobile"  type="text"  name="mobile"  maxlength="15" value="">
            </div>
                
            <div class="input-field-cont"> 
                <label><?php echo $this->lang->line('City') ?></label>            
                <input id="city"  type="text" name="city" value="" placeholder="<?php echo $this->lang->line('enter_your_city') ?>">
            </div>
            
        <div class="input-field-cont">
            <label><?php echo $this->lang->line('Country') ?></label>            
            <input id="country"  type="text" name="country" value="" placeholder="<?php echo $this->lang->line('enter_your_country') ?>">
        </div>
        
        <div class="input-field-cont long-strip">
            <label><?php echo $this->lang->line('Address') ?>:</label>
            <textarea cols="40" rows="5" name="address" id="address"> </textarea>            
            </div>

        <div class="input-field-cont long-strip">
            <label>Autorização de Artigos Automático</label>
            <input type="checkbox" style="transform: scale(1.5);" name="auto_accept_article" id="auto_accept_article" value="1"
                <?php echo $data_result['auto_accept_article'] == 1 ? "checked='checked'" : "" ?> />
        </div>
    
        <div class="login-credential clearfix">
            <h2> <?php echo $this->lang->line('Login_Credentials') ?></h2>
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('User_Name') ?></label>            
                <input id="user_name"  type="text" name="user_name" value=""  maxlength="50">
            </div>
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('Password') ?></label>            
                <input id="password"  type="password" name="password" value="">           
            </div>


        <div class="input-container clearfix"> 
            <input type="submit" value="save" name ="save" />
        </div>
        </div>

<?php echo form_close(); ?>  

    </div>


</div>
    
    
    
        <script>

            // When the browser is ready...
            $(function() {

                // Setup form validation on the #register-form element
                $("#add_customer").validate({
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
                        form.submit();
                    }
                });

            });

        </script>
    
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
</body>
</html>
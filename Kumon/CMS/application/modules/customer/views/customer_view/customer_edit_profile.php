<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>::Wootrix | Category::</title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

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
        echo form_open_multipart($this->config->base_url() . 'index.php/customereditprofile', $attributes);
        ?>  
        <?php //echo"<pre>"; print_r($data_result); ?>
        <div style="color: red"><p><?php echo $msg;  echo $this->session->flashdata("msg");?>   </p>
            <div style="color: green" ><?php echo $this->session->flashdata("susmsg");?>   </div>
            <?php echo validation_errors(); ?></div>

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
                <label><?php echo $this->lang->line('Name') ?></label>            
                <input id="name"  type="text" name="name" value="<?php echo$data_result['name'];  ?>" maxlength="50" >
            </div>
      
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('Born') ?>:</label>            
                <input id="dob"  type="text" name="dob" value="<?php if($data_result['dob'] != '0000-00-00'){ echo$data_result['dob']; }  ?>" >
            </div>
        
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('Gender') ?></label>            
                <select id="gender" name="gender" >
                    <option selected><?php echo $this->lang->line('select_gender') ?></option>    
                <option value="f" <?php if($data_result['gender'] == 'f') echo ' selected="selected"' ?> ><?php echo $this->lang->line('Female') ?></option>
                <option value="m" <?php if($data_result['gender'] == 'm') echo ' selected="selected"' ?>><?php echo $this->lang->line('Male') ?></option>
                </select>
            </div>    

        
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('Email') ?></label>            
                <input id="email"  type="text" name="email" value="<?php echo$data_result['email'];  ?>" >
            </div>
            
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('Company_Name') ?></label>            
                <input id="company_name"  type="text" name="company_name" value="<?php echo$data_result['company_name'];  ?>" >
            </div>
        
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('Work_Phone') ?></label>            

                <input id="work_phone"  type="text" name="work_phone" maxlength="15" value="<?php echo$data_result['work_phone'];?>" maxlength="15">

            </div>
            
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('Mobile') ?></label>            

                <input id="mobile"  type="text" name="mobile" maxlength="15" value="<?php echo$data_result['mobile'];  ?>" maxlength="15">

            </div>
                
            <div class="input-field-cont"> 
                <label><?php echo $this->lang->line('City') ?></label>            
                <input id="city"  type="text" name="city" value="<?php echo$data_result['city'];  ?>" >
            </div>
            
        <div class="input-field-cont">
            <label><?php echo $this->lang->line('Country') ?></label>            
            <input id="country"  type="text" name="country" value="<?php echo$data_result['country'];  ?>" >
        </div>       
        
        <div class="input-field-cont long-strip">
            <label><?php echo $this->lang->line('Address') ?></label>
            <textarea cols="40" rows="5" name="address" id="address"><?php echo $data_result['address'];  ?></textarea>            
        </div>
        
        <div class="input-container clearfix"> 
            <input type="submit" value="save" name ="save" />
            <input type="hidden" value="<?php echo$data_result['id'];  ?>" name ="customer_id" />
        </div>
    <?php echo form_close(); ?> 
        
        <div class="login-credential clearfix">
            <h2><?php echo $this->lang->line('Login_Credentials') ?></h2>
            
           <?php $attributes_form_second = array('name' => 'change_password_form_second', 'id' => 'change_password_form_second', 'class' => 'form-horizontal');
           echo form_open_multipart($this->config->base_url() . 'index.php/customerchangePassword', $attributes_form_second);
           ?>  
        <?php //echo"<pre>"; print_r($data_result); ?>
        <div style="color: red"><p><?php //echo $msg;  echo $this->session->flashdata("msg");?>   </p>
            <?php echo validation_errors(); ?></div>
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('User_Name') ?></label>            
                <input id="user_name"  type="text" name="user_name" value="<?php echo$data_result['user_name'];  ?>"  maxlength="50" readonly="true">
            </div>
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('Password') ?></label>            
                <input id="password"  type="password" name="password" value="" placeholder="<?php echo $this->lang->line('old_password') ?>" >           
            </div>
            <div class="input-field-cont">
                <label><?php echo $this->lang->line('New_Password') ?></label>            
                <input id="new_password"  type="password" name="new_password" value="" placeholder="<?php echo $this->lang->line('new_password') ?>" >           
            </div>

            <div class="input-field-cont">
                <label><?php echo $this->lang->line('Conform_Password') ?></label>            
                <input class="margin-top" id="password_confirm"  type="password" name="password_confirm" value="" placeholder="<?php echo $this->lang->line('confirm_password') ?>">           
            </div>
             <div class="input-container clearfix"> 
            <input type="submit" value="save" name ="save" />
            <input type="hidden" value="<?php echo$data_result['id'];  ?>" name ="customer_id" />
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
                        password:"required",
                        new_password:"required",
                        password_confirm:"required",
                        work_phone:{
                            number: true,
                            maxlength:15
                        },
                        mobile:{
                            number: true,
                            maxlength:13                            
                        }
                    },
                    // Specify the validation error messages
                    messages: {
                        name: "<?php echo $this->lang->line("Please_enter_your_name") ?>",
                        email: "<?php echo $this->lang->line("Please_enter_a_valid_email_address") ?>",
                        username: "<?php echo $this->lang->line("Please_enter_a_valid_username") ?>",
                        work_phone: "<?php echo $this->lang->line("Please_enter_a_valid_work_phon") ?>",
                        mobile: "<?php echo $this->lang->line("Please_enter_valid_mobile_number") ?>",                        
                    },
                    submitHandler: function(form) {
                        form.submit();
                    }
                });

            });
            
         /* CHANGE PASSWORD*/
         
          $(function() {

                // Setup form validation on the #register-form element
                $("#change_password_form_second").validate({
                    // Specify the validation rules
                    rules: {                       
                        user_name: "required",
                        password:"required",
                        new_password:"required",
                        password_confirm: {
                        required: true,
                        equalTo: "#new_password"
                        }
                    },
                    // Specify the validation error messages
                    messages: {                        
                        username: "<?php echo $this->lang->line("Please_enter_a_valid_username") ?>",                        
                        password: {
                            required: "<?php echo $this->lang->line("Please_provide_a_password") ?>",
                            minlength: "Your password must be at least 5 characters long",
                            password_confirm :" Enter Confirm Password Same as Password",
                        },
                        password_confirm:{
                            equalTo :" Enter Confirm Password Same as Password",
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
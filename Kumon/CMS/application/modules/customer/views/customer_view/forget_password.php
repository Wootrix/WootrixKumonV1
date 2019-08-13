<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Admin panel developed with the Bootstrap from Twitter.">
<meta name="author" content="dev" >
    
<title>:: Wootrix Leadership ::</title>
<link href="<?php echo $this->config->base_url();?>css/style.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="http://jquery.bassistance.de/validate/demo/site-demos.css">
<script src="http://jquery.bassistance.de/validate/jquery.validate.js"></script>
<script src="http://jquery.bassistance.de/validate/additional-methods.js"></script>
        
<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="<?php echo $this->config->base_url();?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $this->config->base_url();?>jss/jquery.latest.js"></script>

</head>

<body>    
    <div class="logo-container"><a href="#"><img src="<?php echo $this->config->base_url();?>images/logo.png" alt=""/></a></div>
    
    <div class="login-container clearfix">
        <?php //echo"<pre>"; print_r($this->session); ?>
        
         <?php 
           $attributes = array('name'=>'forgot_password', 'id'=>'login','class'=>'form-horizontal');
           echo form_open($this->config->base_url().'index.php/customerforgotpassword',$attributes);?>   
        
        <div style="color: red"><p><?php echo $msg;echo $this->session->flashdata("msg"); ?>  </p>
        <?php echo validation_errors(); ?></div>
                                                            
                                                       
            
        <!--div class="input-container">
            <label><?php echo $this->lang->line('username'); ?>:</label>            
                <input id="username"  type="text" name="username" value="">                			
        </div-->
        
        <div class="input-container clearfix">
            <label><?php echo $this->lang->line('Email'); ?>:</label>            
            <input id="email" type="email" name="email">
        </div>
        
        <div class="input-container clearfix">        
            <input type="submit" value="Submit" name ="Submit" />
        </div>
      
         
  <?php echo form_close(); ?>  
        
        
        <script>
// validation
jQuery.validator.setDefaults({
  debug: false,
  success: "valid"
});

$( "#forgot_password" ).validate({
  rules: {
    email: {
      required: true,
      min: 1
    }
  }
 
});
</script> 
   </div>
    
    <footer>
        <div class="fix-container clearfix">
            <div class="bttm-logo"><a href="#"><img src="<?php echo $this->config->base_url();?>images/logo.png" alt=""/></a></div>
            <div class="bttm-links"><span>© 2014 wootrix, Inc.</span> | <a href="#">Terms of Service</a> | <a href="#">Privacy Policy</a></div>
        </div>
    
    </footer>
    
</body>
</html>
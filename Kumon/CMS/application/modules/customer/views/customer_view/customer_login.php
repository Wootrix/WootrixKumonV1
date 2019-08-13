<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Admin panel developed with the Bootstrap from Twitter.">
<meta name="author" content="dev" >
    
<title>:: Wootrix ::</title>
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
           $attributes = array('name'=>'login', 'id'=>'login','class'=>'form-horizontal');
           echo form_open($this->config->base_url().'index.php/customer_login',$attributes);?>   
        
        <div style="color: red"><p><?php echo $msg;echo $this->session->flashdata("msg"); ?>  </p>
        <?php echo validation_errors(); ?></div>
                                                            
                                                       
            
        <div class="input-container">
            <label><?php echo $this->lang->line('username'); ?>:</label>            
                <input id="email"  type="text" name="username" value="<?php echo $_COOKIE['remember_me_customer']; ?>">                			
        </div>
        
        <div class="input-container clearfix">
            <label><?php echo $this->lang->line('Password'); ?>:</label>            
            <input id="password" type="password" name="password">
        </div>
        
        <div class="input-container clearfix">
        <input type="checkbox" name="remembermewoo" value="1" <?php if(isset($_COOKIE['remember_me_customer'])) { ?>checked="checked" <?php } ?>/>
            <span><?php echo $this->lang->line('Remember_me'); ?></span>
            <input type="submit" value="Login" name ="login" />
        </div>
        <div class="input-container clearfix">
            <a href="customerforgotpassword">Lost your password ?</a>
        </div>   
        
         
  <?php echo form_close(); ?>  
        
        
        <script>
// validation
jQuery.validator.setDefaults({
  debug: false,
  success: "valid"
});

$( "#login" ).validate({
  rules: {
    email: {
      required: true,
      min: 1
    },    
    password: {
      required: true
      
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
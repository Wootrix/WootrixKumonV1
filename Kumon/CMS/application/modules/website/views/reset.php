

<!--Content Starts Here-->
      <div class="container clearfix">
        
        <div class="bottom_info clearfix">
        	<!--div class="ti_address">
            	<h3><?php echo $this->lang->line("reset_password"); ?></h3>
                 
                <ul>
                	<li class="clearfix">
                    	<span class="location"></span>
                       <h4></h4>
                        <div class="clear"></div>
                    </li>
                    <li class="clearfix">
                    	<span class="phone"></span>
                        <h4>8888888888<br>
                        	
                        </h4>
                        <div class="clear"></div>
                    </li>
                    <li class="clearfix">
                    	<span class="email"></span>
                        <a href="javascript:void(0);">support@wootrix.com</a>
                        <div class="clear"></div>
                    </li>
                    <li class="clearfix">
                    	<span class="website"></span>
                        <a target="_blank" href="http://103.25.130.197/wootrix">https://www.wootrix.com</a>
                        <div class="clear"></div>
                    </li>
                    
                </ul>
            </div-->
            
  
             <div class="">
                 <h3 style="font-size: 18px; text-align: center; color: #333; display:block;"><?php echo $this->lang->line("reset_password"); ?>
                 
            	<!--iframe width="850" height="250" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src='https://maps.google.co.in/?ie=UTF8&amp;ll=//28.5929914,77.3051591&amp;spn=69.865667,135.263672&amp;t=m&amp;z=4&amp;output=embed'></iframe-->
            </div>
         <div class="clear"></div>
        </div>
       
      	<div class="form_cont clearfix">
		
            <span id="validResContact"><?php if($this->session->flashdata("success_msg")) { echo $this->session->flashdata("success_msg"); ?>   <a class="submitBttn" href="<?php echo base_url(); ?>" type="button" id="id" placeholder="">Go to Login </a> <?php } ?></span>
      	<h3><?php echo $this->lang->line("reset_password"); ?></h3>
        <form id="contact_form" name="contact_form" method="post" action="<?php echo $this->config->base_url(); ?>index.php/resetpost">
        <ul>
        	<li>
            	<label><?php echo $this->lang->line("re_enter_password"); ?></label>
                <input type="text" id="repassword" name="password" placeholder="<?php echo $this->lang->line("re_enter_password"); ?>">
                <input type="hidden" id="id" value="<?php echo $id; ?>" name="id" placeholder="<?php echo $id; ?>">
                <div id="first_name_error"></div>
				  <input type="submit" id="id" placeholder="">
            </li>
		
          
        </ul>
        </form>
        <div class="clear"></div>
      </div>
    </div>
    <script>
                          
// just for the demos, avoids form submit
$.validator.setDefaults({    
  debug: false,
  success: "valid"
});
$( "#contact_form" ).validate({    
 
  rules: {
     repassword: {        
      required: true
    }
    }
  }
});
</script> 
    

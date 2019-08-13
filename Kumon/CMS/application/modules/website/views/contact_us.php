
  
<!--Content Starts Here-->  
      <div class="container clearfix">
        
        <div class="bottom_info clearfix">
        	<!--div class="ti_address">
            	<h3><?php echo $this->lang->line("contact_details_web"); ?></h3>
                 
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
                 <h3 style="font-size: 18px; text-align: center; color: #333; display:block;"><?php echo $this->lang->line("contact_us"); ?>
                 
            	<!--iframe width="850" height="250" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src='https://maps.google.co.in/?ie=UTF8&amp;ll=//28.5929914,77.3051591&amp;spn=69.865667,135.263672&amp;t=m&amp;z=4&amp;output=embed'></iframe-->
            </div>
         <div class="clear"></div>
        </div>
       
      	<div class="form_cont clearfix">
            <span id="validResContact"><?php echo $this->session->flashdata("contactMsg"); ?></span>
      	<h3><?php echo $this->lang->line("fill_below_web"); ?></h3>
        <form id="contact_form" name="contact_form" method="post" action="<?php echo $this->config->base_url(); ?>index.php/wootrix-contact-us">
        <ul>
        	<li>
            	<label><?php echo $this->lang->line("first_name_web"); ?></label>
                <input type="text" id="first_name" name="first_name" placeholder="<?php echo $this->lang->line("enter_first_name_web"); ?>">
                <div id="first_name_error"></div>
            </li>
            
            <li>
            	<label><?php echo $this->lang->line("last_name_web"); ?></label>
                <input type="text" id="last_name" name="last_name" placeholder="<?php echo $this->lang->line("enter_last_name_web"); ?>">
                 <div id="last_name_error"></div>
            </li>
            
            <li class="clearfix">
            	<label><?php echo $this->lang->line("email_id_web"); ?></label>
                <input type="email" id="email" name="email" placeholder="<?php echo $this->lang->line("enter_email_id_web"); ?>">
                <div id="email_error"></div>
            </li>
            
            <li>
            	<label><?php echo $this->lang->line("phone_no_web"); ?></label>
                <input type="text" id="contact_no" name="contact_no" placeholder="<?php echo $this->lang->line("enter_phone_no_web"); ?>" maxlength="15" >
                <div id="contact_no_error"></div>
            </li>
            
             <li class="long">
            	<label><?php echo $this->lang->line("comment_web"); ?></label>
                <textarea id="comment" name="comment" rows="4"></textarea>
                <div id="comment_error"></div>
            </li>
            <li class="long">
                <input type="hidden" name="contactHidden" value="saveHidden" />
            	<input type="submit" name="save" value="<?php echo $this->lang->line("Save_web"); ?>">
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
     first_name: {        
      required: true
    },
    last_name: {        
      required: true
    },
    email: {        
      required: true,
      email:true
    },
    contact_no: {
       required: true,
       number: true
    },
    comment: {
       required: true 
    }
  }
});
</script> 
    

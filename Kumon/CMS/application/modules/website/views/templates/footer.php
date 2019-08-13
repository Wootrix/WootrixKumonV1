<footer class="clearfix">
        <div class="container clearfix">
            <div class="span4">
                <div class="about-bottom">
                    <figure><img src="<?php echo $this->config->base_url(); ?>images/website_images/logo.png" alt="wootrix logo"></figure>
                    <p><?php echo $this->lang->line("footer_text_one"); ?> <br/> <br/> <?php echo $this->lang->line("footer_text_two"); ?> </p>

                </div>

            </div>

        </div>

    </footer>

    <div class="copyright clearfix">
        <div class="container clearfix">
            <div class="span6">
                <p><?php echo $this->lang->line("reserved_line_footer"); ?></p>
            </div>

            <div class="span6 right">
                <ul class="right"> 
                    <li>
                        <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-contact-us"><?php echo $this->lang->line("contact_us"); ?></a> </li>  
                    <li>
                        <?php if($this->session->userdata('languagesWeb')=='1'){ ?>
                        <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-about-us/en">
                        <?php }?><?php if($this->session->userdata('languagesWeb')=='2'){ ?>
                        <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-about-us/pt">
                        <?php }?><?php if($this->session->userdata('languagesWeb')=='3'){ ?>
                        <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-about-us/es">
                        <?php }?>
                            <?php echo $this->lang->line("about_us"); ?>
                        </a>
                    </li> 
                    <li><a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-terms-services"><?php echo $this->lang->line("terms_of_service"); ?></a> </li>     
                    <li>
                        <?php if($this->session->userdata('languagesWeb')=='1'){ ?>
                        <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-privacy-policy/en">
                        <?php }?>
                            <?php if($this->session->userdata('languagesWeb')=='2'){ ?>
                            <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-privacy-policy/pt">
                            <?php }?>
                            <?php if($this->session->userdata('languagesWeb')=='3'){ ?>
                                <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-privacy-policy/es">
                            <?php }?>
                            <?php echo $this->lang->line("privacy_policy"); ?>
                        </a> 
                    </li>

                </ul>    
            </div>

        </div>

    </div>


    <!--Pop up-->
 <script>
    $(document).ready(function () {
        $(".my-account").click(function (e) {  
            e.stopPropagation();
            $("#content-box").toggle();
            console.log(checkedBox);
            $('.scroll-box').scrollTop( $('.scrollC'+scrollId).position().top - 96 );
        });
        
         $(".account-detail-box").on("click", function (event) {
        event.stopPropagation();
    });
    
    $(document).on("click", function () {
    $(".account-detail-box").hide();
    });
        
        

        $('.register-bttn').click(function () {
            $('.register-form, .signin-social').slideToggle();
        });

        $('#setting-bttn').click(function () {
            $('#content-box').hide();
        });

        $('#setting-bttn').click(function () {
            $('#my-account').show();
        });

        $('.my-account').click(function () {
            $('#my-account, #language-options, #change-password-options, #change-email-options').hide();
        });

        $('#change-language').click(function () {
            $('#language-options').show();
        });

        $('#change-language').click(function () {
            $('#my-account').hide();
        });
        
         $('#add-account').click(function () {
            $('#add-account-wrap').show();
        });

        $('#change-language').click(function () {
            $('#my-account').hide();
        });
        
        

        $('#change-password').click(function () {
            $('#change-password-options').show();
        });

        $('#change-email').click(function () {
            $('#change-email-options').show();
        });

        $(".add-magazine").click(function () {
            $(".layer-bg").fadeIn();
        });

        <?php if( isset( $_GET["showInputDialog"] ) && $_GET["showInputDialog"] == "1" ): ?>
            $(".layer-bg").fadeIn();
        <?php endif; ?>

        $(".close-bttn, .layer-bg-popupOverlay").click(function () {
            $(".layer-bg").fadeOut();
        });

        $(".more-btn").click(function () {
            $(".more-options").toggle();
        });

        $('#my-account, #language-options, #change-password-options, #change-email-options, #add-account-wrap').click(function(event){
            event.stopPropagation();
        });
        
        var msg = "<?php echo $this->session->flashdata('error'); ?>";
        if(msg!=''){
        $('#my-account').show();
         $('#change-email-options').show();
    }
    
        var msg1 = "<?php echo $this->session->flashdata('errorPassword'); ?>";
        if(msg1!=''){
        $('#my-account').show();
         $('#change-password-options').show();
    }
    
    var msg2 = "<?php echo $this->session->flashdata('topicShowBox'); ?>";
    var msg3 = "<?php echo $this->session->flashdata('languageBox'); ?>";
    
    if(msg2!='' || msg3!=''){
        $("#content-box").toggle();
        console.log(checkedBox);
        $('.scroll-box').scrollTop( $('.scrollC'+scrollId).position().top - 96 );
    }
    
var loginError = "<?php echo $this->session->flashdata("errorVal"); ?>";
 if(loginError == "11"){
     //$("#content-box").toggle();
     $(".layer-bg").fadeIn();
     
     
 }
 
 
    $(".container").click(function () {            
            $("#searchListArticle").hide();
        });
    });
</script>

    <div class="layer-bg">
        <div class="layer-bg-popupOverlay"></div>
        <div class="popup-container clearfix">
            <div class="close-bttn"></div>
            <h4><?php echo $this->lang->line("subscription_password"); ?></h4>
            <span id="errorValue"><?php echo $this->session->flashdata("subsMsgError"); ?></span>
            <div class="popup-inner clearfix">
                <div class="select-container">
                    <div class="select-arrow">
                        <div class="arrow-down"></div>
                    </div>    
                </div> 
            </div>
            <form name="subsPass" id="subsPass" action="<?php echo $this->config->base_url(); ?>index.php/wootrix-subscribe-password" method="POST" >
            <div class="popup-inner clearfix">
                <input type="password" placeholder="<?php echo $this->lang->line("enter_password"); ?>" name="subsPassword">
            </div>
            <div class="popup-inner clearfix">
                <input type="hidden" name="segValue" value="<?php echo $this->uri->segment(1); ?>" />
                <input type="submit" value="Save" />
            </div>
            </form>
        </div>

    </div>



</body>
</html>
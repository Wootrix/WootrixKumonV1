<!DOCTYPE HTML>
<html>
<head>
    <title>Kumon</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="apple-itunes-app" content="app-id=1034925784"/>
    <!--[if lte IE 8]>
    <script src="<?php echo $this->config->base_url();?>js/ie/html5shiv.js"></script><![endif]-->
    <!-- <link href="<?php echo $this->config->base_url(); ?>css/main.css" rel="stylesheet" type="text/css"> -->
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="<?php echo $this->config->base_url();?>css/ie8.css"/><![endif]-->
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="<?php echo $this->config->base_url();?>css/ie9.css"/><![endif]-->
</head>
<body class="landing">

<!-- Page Wrapper -->
<div id="page-wrapper">

    <!-- Header 1st section -->
    <header class="top clearfix">
        <div class="container clearfix">
            <div class="span6">
                <figure class="logo-container">
                    <a href="#">
                        <img class="autoHeight" src="<?php echo $this->config->base_url(); ?>images/website_images/logo.png" alt="logo"/></a>
                </figure>
            </div>

            <div class="span6 right" onclick="">
                <div class="login-box clearfix">
                    <p class="msgError"><?php echo $this->session->flashdata('errorLoginMsg') . $this->session->flashdata('errorLoginMsg1') . $this->session->flashdata('errorLoginMsg2'); ?></p>
                    <span class="sign-in"><?php echo $this->lang->line("sign_in_website"); ?></span>
                    <form name="userLogin" id="userLogin" action="<?php echo $this->config->base_url(); ?>index.php/wootrix-user-login" method="POST">
                        <input type="hidden" name="userEmailLogin" id="userEmailLogin" />
                        <input type="hidden" name="loginHidden" value="saveLoginHidden" />
                    </form>

                </div>

            </div>

        </div>

    </header>

    <!-- Banner -->
    <section id="banner">

        <div class="container clearfix">
            <div class="">
                <figure class="main-home-image">
<!--                    <img src="--><?php //echo $this->config->base_url(); ?><!--images/website_images/home-screen.png" alt=""/>-->
                </figure>
            </div>
        </div>

    </section>

</div>

<!-- Scripts -->
<script src="<?php echo $this->config->base_url(); ?>js/jquery.min.js"></script>
<script src="<?php echo $this->config->base_url(); ?>js/jquery.scrollex.min.js"></script>
<script src="<?php echo $this->config->base_url(); ?>js/jquery.scrolly.min.js"></script>
<script src="<?php echo $this->config->base_url(); ?>js/skel.min.js"></script>
<script src="<?php echo $this->config->base_url(); ?>js/util.js"></script>
<!--[if lte IE 8]>
<script src="<script src="<?php echo $this->config->base_url();?>js/ie/respond.min.js"></script><![endif]-->
<script src="<?php echo $this->config->base_url(); ?>js/main.js"></script>

<script>
    var q = jQuery.noConflict();
    // just for the demos, avoids form submit
    q.validator.setDefaults({
        debug: false,
        success: "valid"
    });
    q("#userReg").validate({
        rules: {
            userName: {
                required: true
            },
            userEmail: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            },
            confirmPass: {
                required: true,
                equalTo: "#password"
            }
        }
    });
</script>
<script>
    var j = jQuery.noConflict();
    j(document).ready(function () {

        j(".sign-in").click(function () {
            j("#login-detail-box").toggle();
            j('#forgotBox').hide();
        });

        j('.register-bttn').click(function () {
            j('.register-form, .signin-social').slideToggle();
        });

        j('.register-bttn').click(function () {
            j('.register-bttn').hide();

        });

        j('#forgotPass').click(function () {
            j('#forgotBox').show();
            j('#login-detail-box').hide();
        });


        var msg = "<?php echo $this->session->flashdata('errorLoginMsg'); ?>";
        var msg1 = "<?php echo $this->session->flashdata('errorLoginMsg1'); ?>";
        var msg2 = "<?php echo $this->session->flashdata('errorLoginMsg2'); ?>";
        if (msg != '') {
            j(".login-detail-box").toggle();
            j("#forgotBox").hide();
        }
        if (msg1 != '') {
            j(".login-detail-box").hide();
            j('#forgotBox').hide();
        }
        if (msg2 != '') {
            j(".login-detail-box").hide();
            j('#forgotBox').show();
        }
    });
</script>
<script>
    j(document).ready(function () {
        var loginError = "<?php echo $this->session->flashdata("error"); ?>";
        var msgValid = "<?php echo $this->session->flashdata("error"); ?>";
        if (loginError == "1") {

            j('.register-form, .signin-social').slideToggle();
            j('.register-bttn').hide();
        }
        if (msgValid == "2") {

            j('.register-form, .signin-social').slideToggle();
            j('.register-bttn').hide();
        }

    });

</script>

<div class="register-form condition">
    <div class="input-box">
        <small><?php echo $this->lang->line("signup_button_text_web"); ?></small>
    </div>
</div>

</div>
</div>

</div>

</body>
</html>
<!DOCTYPE HTML>
<!--
	Wootrix
-->
<html>
	<head>
		<title>wootrix</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="<?php echo $this->config->base_url();?>js/ie/html5shiv.js"></script><![endif]-->
		<!-- <link href="<?php echo $this->config->base_url();?>css/main.css" rel="stylesheet" type="text/css"> -->
		<!--[if lte IE 8]><link rel="stylesheet" href="<?php echo $this->config->base_url();?>css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="<?php echo $this->config->base_url();?>css/ie9.css" /><![endif]-->
	</head>
	<body class="landing">

		<!-- Page Wrapper -->
	<div id="page-wrapper">
				
    <!-- Header 1st section -->
    <header class="top clearfix">
        <div class="container clearfix">
            <div class="span6">
                <figure class="logo-container">
                    <a href="#"> <img class="autoHeight" src="<?php echo $this->config->base_url(); ?>images/website_images/logo.png" alt="logo" /></a>
                </figure>
            </div>

            <div class="span6 right">
                <div class="login-box clearfix">
                    <p class="msgError"><?php echo $this->session->flashdata('errorLoginMsg') . $this->session->flashdata('errorLoginMsg1') . $this->session->flashdata('errorLoginMsg2'); ?></p>
                    <span class="sign-in"><?php echo $this->lang->line("sign_in_website"); ?></span>
                    <form name="userLogin" id="userLogin" action="<?php echo $this->config->base_url(); ?>index.php/wootrix-user-login" method="POST" >
                        <div id="login-detail-box" class="login-detail-box">
                            <div class="input-box">
                                <input type="email" placeholder="<?php echo $this->lang->line("Email"); ?>" name="userEmailLogin"/>
                            </div>
                            <div class="input-box">
                                <input type="password" placeholder="<?php echo $this->lang->line("Password"); ?>" name="passwordLogin"/>
                                <a id="forgotPass" class="forgot-password" href="#"></a>
                                <input type="hidden" name="loginHidden" value="saveLoginHidden" />
                                <input type="submit" value="OK"/>
                            </div>

                            <div class="arrow-up"></div>
                        </div>
                    </form>

                    <div id="forgotBox" class="login-detail-box">
                        <div class="input-box">
                            <form action="<?php echo base_url(); ?>index.php/forgotPassword" method="POST">
                                <input class="forgotEmail" type="email" placeholder="<?php echo $this->lang->line("Email"); ?>" name="forgetEmail"/>

                                <input type="hidden" name="loginHidden" value="websiteForm" />
                                <input type="submit" value="OK" />
                            </form>
                        </div>
                        <div class="arrow-up"></div>
                    </div>


                    <script>
                        var jq = jQuery.noConflict();
						// just for the demos, avoids form submit
                        jq.validator.setDefaults({
                            debug: false,
                            success: "valid"
                        });
                        jq("#userLogin").validate({
                            rules: {
                                userEmailLogin: {
                                    required: true,
                                    email: true
                                },
                                passwordLogin: {
                                    required: true
                                }
                            }
                        });
                    </script>
                </div>

            </div>

        </div>

    </header>  

				<!-- Banner -->
					<section id="banner">
						
							<!-- login code Begin -->
							<div class="container clearfix">							        	
							<div class="span7 left">
								<figure class="main-home-image">
									<img src="<?php echo $this -> config -> base_url(); ?>images/website_images/home-screen.png" alt=""/>
								</figure>				
							</div>
											
							<div class="span5 right">
								<div class="social-login-cont">
								<div class="signin-social">
									<h3><?php echo $this -> lang -> line("sign_in_with"); ?></h3>
									<div class="social-icons clearfix">
										<ul>
											<li class="fb"><a href="<?php echo $fbLogin; ?>">Facebook</a></li>
											<li class="twitter"><a href="<?php echo $this->config->base_url(); ?>index.php/website/login_twitter/index">Twitter</a></li>
											<li class="linkedin"><a href="<?php echo $this->config->base_url(); ?>index.php/website/loginWithLinkedIn">linkedin</a></li>
											<li class="google"><a href="<?php echo $googleLogin; ?>">google</a></li>
										</ul>																						
									</div>									
									<div class="login-divider">
										<span><?php echo $this -> lang -> line("or"); ?></span> 
									</div>
								</div>

							   <form name="userReg" id="userReg" action="<?php echo $this -> config -> base_url(); ?>index.php/wootrix-user-registration" method="POST">                                
								<div class="register-form">
									<h4><?php echo $this -> lang -> line("welcome_web"); ?></h4>
									<span id="errorValue"><?php echo $this -> session -> flashdata("loginError"); ?></span>
									<span style="text-align: center;display: block;color: green;"><?php echo $this -> session -> flashdata("regMsg"); ?></span>
									<div class="input-box">
										<input type="text" placeholder="<?php echo $this -> lang -> line("Name"); ?>" name="userName" value="<?php echo $this -> session -> flashdata("userName"); ?>"/>
									</div>					
									<div class="input-box">
										<input type="email" placeholder="<?php echo $this -> lang -> line("Email"); ?>" name="userEmail" value="<?php echo $this -> session -> flashdata("userEmail"); ?>"/>
									</div>					
									<div class="input-box">
										<input type="password" placeholder="<?php echo $this -> lang -> line("Password"); ?>" name="password" id="password" />
									</div>					
									<div class="input-box">
										<input type="password" placeholder="<?php echo $this -> lang -> line("confirm_password_web"); ?>" name="confirmPass"/>
									</div>						
								</div>
												
									<div class="btn-box clearfix">
										<input type="hidden" name="userRegHidden" value="saveReg" />
										<input type="submit" value="<?php echo $this -> lang -> line("sign_up_web"); ?>">
										<a class="register-bttn" href="#"><?php echo $this -> lang -> line("web_register"); ?></a>
									</div>		
								</form>	
								
								<div class="register-form condition">
                                     <div class="input-box">
                                     	<small><?php echo $this -> lang -> line("signup_button_text_web"); ?></small>
                                     </div>
                                </div>
										
							<!-- login code End -->
							
					      <!-- <a href="#one" class="more scrolly">Learn More</a>	-->		
					</section>

				<!-- One -->
					<section id="one" class="wrapper style1 special">
						<div class="inner">
							<header class="major">
								<h2>Agregador e curador de conteúdo voltado ao segmento técnico.</h2>
							</header>
						</div>
					</section>

				<!-- Two -->
					<section id="two" class="wrapper alt style2">
						<section class="spotlight">
							<div class="image"><img src="<?php echo $this -> config -> base_url(); ?>images/pic01.jpg" alt="" /></div><div class="content">
								<h2>Customize<br />
								 sua publicação</h2>
								<p>Determine os tópicos e os idiomas de seu interesse e nossos algoritmos montam uma revista digital sob medida para você.</p>
							</div>
						</section>
						<section class="spotlight">
							<div class="image"><img src="<?php echo $this -> config -> base_url(); ?>images/pic02.jpg" alt="" /></div><div class="content">
								<h2>Plataforma de curadoria,<br />
								com aparência amigável ao usuário.</h2>
								<p>Através de um layout moderno, bonito e de fácil leitura, a plataforma direciona você a conteúdos técnicos relevantes minuciosamente selecionados.</p>
							</div>
						</section>
						<section class="spotlight">
							<div class="image"><img src="<?php echo $this -> config -> base_url(); ?>images/pic03.jpg" alt="" /></div><div class="content">
								<h2>Revistas Customizadas<br />
								</h2>
								<p>Você poderá ser convidado a ler revistas de empresas, universidades,entidades e etc, ter acesso a um canal 24x7 contendo notícias, cases de sucesso, catálogos de produtos, lançamentos e muito mais.
 								</p>
							</div>
						</section>
					</section>

				<!-- Three -->
					<section id="three" class="wrapper style3 special">
						<div class="inner">
							<header class="major">
								<h2>O caminho mais curto para as principais novidades do setor!</h2>
								<!-- <p>Aliquam ut ex ut augue consectetur interdum. Donec amet imperdiet eleifend<br />
								fringilla tincidunt. Nullam dui leo Aenean mi ligula, rhoncus ullamcorper.</p>-->
							</header>
							<ul class="features">
								<li class="icon fa-filter">
									<h3>Determine Temas e Idiomas do conteúdo</h3>
									<p> Dentre os 12 tópicos disponíveis selecione os de seu interesse, bem como defina os idiomas (Português, Inglês, Espanhol) em que deseja receber conteúdo. </p>
								</li>
								<li class="icon fa-dashboard">
									<h3>Altere Idioma do sistema</h3>
									<p>É possível também, escolher a lingua da plataforma (Português, Inglês, Espanhol) de acordo com sua fluência ou preferência.</p>
								</li>
								<li class="icon fa-download">
									<h3>Baixe uma Revista Customizada</h3>
									<p>Ao receber um convite para uma revista customizada o usuário deve inserir o código de segurança informado no Painel de Controle.</p>
								</li>
								<li class="icon fa-users">
									<h3>Acesse usando suas Redes Sociais</h3>
									<p>Ao associar suas contas no painel de controle, você informa ao sistema quem você é, possibilitando logar com quaisquer redes sociais compatíveis sem perder suas configurações.</p>
								</li>
							</ul>
						</div>
					</section>

				<!-- CTA -->
					<section id="cta" class="wrapper style4">
						<div class="inner">
							<header>
								<a href="https://itunes.apple.com/lc/app/wootrix/id1034925784?mt=8"> <img class="img" src="<?php echo $this->config->base_url(); ?>images/website_images/App_Store_Badge_PTBR.png" alt="App Store"/>
                                </a>
							</header>
							<header>
								<a href="https://play.google.com/store/apps/details?id=com.ta.wootrix"> <img class="img" src="<?php echo $this->config->base_url(); ?>images/website_images/google-play-badge-PTBR.png" alt="Google Play"/>
								</a>
							</header>
							<header>
								<a href="#"> <img class="img" src="<?php echo $this->config->base_url(); ?>images/website_images/Web_Badge_PTBR.png" alt="Internet"/>
								</a>
							</header>
						</div>
					</section>

				<!-- Footer -->
					<footer id="footer">
						<ul class="icons">
							<li><a href="https://twitter.com/wootrixoficial" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="https://www.facebook.com/wootrix/" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
							<li><a href="https://plus.google.com/109310151849035195482" class="icon fa-google"><span class="label">G+</span></a></li>
							<li><a href="https://www.linkedin.com/company/wootrix/" class="icon fa-linkedin"><span class="label">linkedin</span></a></li>
							<li><a href="mailto:wootrix@wootrix.com" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
						</ul>
						<ul class="copyright">
							<li>&copy;<a href="#">WOOTRIX</a></li>
						</ul>
					</footer>

			</div>

		<!-- Scripts -->
			<script src="<?php echo $this -> config -> base_url(); ?>js/jquery.min.js"></script>
			<script src="<?php echo $this -> config -> base_url(); ?>js/jquery.scrollex.min.js"></script>
			<script src="<?php echo $this -> config -> base_url(); ?>js/jquery.scrolly.min.js"></script>
			<script src="<?php echo $this -> config -> base_url(); ?>js/skel.min.js"></script>
			<script src="<?php echo $this -> config -> base_url(); ?>js/util.js"></script>
			<!--[if lte IE 8]><script src="<script src="<?php echo $this->config->base_url();?>js/ie/respond.min.js"></script><![endif]-->
			<script src="<?php echo $this -> config -> base_url(); ?>js/main.js"></script>

			<script>
				var q = jQuery.noConflict();
				// just for the demos, avoids form submit
				q.validator.setDefaults({
					debug : false,
					success : "valid"
				});
				q("#userReg").validate({
					rules : {
						userName : {
							required : true
						},
						userEmail : {
							required : true,
							email : true
						},
						password : {
							required : true,
							minlength : 6
						},
						confirmPass : {
							required : true,
							equalTo : "#password"
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
                        <small><?php echo $this -> lang -> line("signup_button_text_web"); ?></small>
                    </div>
                </div>
                
                </div>
            </div>
                   
        </div>

	</body>
</html>
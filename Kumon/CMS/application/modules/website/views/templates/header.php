<!doctype html>
<html class="body-background">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>:: <?php echo $this->lang->line("title_name"); ?> ::</title>

    <!-- <link href="<?php echo $this->config->base_url(); ?>css/main.css" rel="stylesheet" type="text/css" /> -->

    <!--	<?php $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>
		<?php if (strpos($url, '/index.php') !== false) { ?>
			<link href="<?php echo $this->config->base_url(); ?>css/website_style_new.css" rel="stylesheet" type="text/css" />
		<?php } else { ?>
    		<link href="<?php echo $this->config->base_url(); ?>css/website_style.css" rel="stylesheet" type="text/css" />
   		<?php } ?> -->

    <?php if (empty($this->session->userdata("user_id"))) { ?>
        <link href="<?php echo $this->config->base_url(); ?>css/website_style_new.css" rel="stylesheet"
              type="text/css"/>
    <?php } else { ?>
        <link href="<?php echo $this->config->base_url(); ?>css/website_style.css" rel="stylesheet" type="text/css"/>
    <?php } ?>

    <link rel="icon" href="<?php echo base_url(); ?>favicon.png" type="image/png">
    <script src="<?php echo $this->config->base_url(); ?>js/website_jquery.js"></script>
    <script src="<?php echo $this->config->base_url(); ?>js/jquery.validate.js"></script>
    <script>

        var base_url = "<?php echo base_url();?>";

        function logoutUser() {
            location.href = base_url + "index.php/wootrix-user-logout";
        }

        function checkEmailValidation() {
            var patt = /^.+@.+[.].{2,}$/i;
            if ($('#oldEmail').val() == '' || $('#newEmail').val() == '' || $('#confirmEmail').val() == '') {
                $("#oldEmail").css({"border-color": "red"});
                $("#newEmail").css({"border-color": "red"});
                $("#confirmEmail").css({"border-color": "red"});
                return false;
            }
            if ($('#oldEmail').val() != '' || $('#newEmail').val() != '' || $('#confirmEmail').val() != '') {
                if (!patt.test($('#oldEmail').val())) {
                    $("#oldEmail").css({"border-color": "red"});
                    return false;
                } else {
                    $("#oldEmail").css({"border-color": "#fff"});
                }
                if (!patt.test($('#newEmail').val())) {
                    $("#newEmail").css({"border-color": "red"});
                    return false;
                } else {
                    $("#newEmail").css({"border-color": "#fff"});
                }
                if (!patt.test($('#oldEmail').val())) {
                    $("#confirmEmail").css({"border-color": "red"});
                    return false;
                } else {
                    $("#confirmEmail").css({"border-color": "#fff"});
                }
                if ($('#newEmail').val() != $('#confirmEmail').val()) {
                    $("#oldEmail").css({"border-color": "#fff"});
                    $("#newEmail").css({"border-color": "red"});
                    $("#confirmEmail").css({"border-color": "red"});
                    return false;
                }
                window.location.href = "<?php echo $this->config->base_url(); ?>index.php/website/article_detail/changeEmailOfUser?email=" + $('#oldEmail').val() + "&newEmail=" + $('#newEmail').val();

            } else {
                window.location.href = "<?php echo $this->config->base_url(); ?>index.php/website/article_detail/changeEmailOfUser";
            }
        }


        function checkPasswordValidation() {
            if ($('#oldPassword').val() == '' || $('#newPassword').val() == '' || $('#confirmPassword').val() == '') {
                $("#oldPassword").css({"border-color": "red"});
                $("#newPassword").css({"border-color": "red"});
                $("#confirmPassword").css({"border-color": "red"});
                return false;
            }
            if ($('#oldPassword').val() != '' || $('#newPassword').val() != '' || $('#confirmPassword').val() != '') {
                if ($('#oldPassword').val() < 6) {
                    $("#oldPassword").css({"border-color": "red"});
                    return false;
                } else {
                    $("#oldPassword").css({"border-color": "#fff"});
                }
                if ($('#newPassword').val() < 6) {
                    $("#newPassword").css({"border-color": "red"});
                    return false;
                } else {
                    $("#newPassword").css({"border-color": "#fff"});
                }
                if ($('#confirmPassword').val() < 6) {
                    $("#confirmPassword").css({"border-color": "red"});
                    return false;
                } else {
                    $("#confirmPassword").css({"border-color": "#fff"});
                }
                if ($('#newPassword').val() != $('#confirmPassword').val()) {
                    console.log("here");
                    $("#oldPassword").css({"border-color": "#fff"});
                    $("#newPassword").css({"border-color": "red"});
                    $("#confirmPassword").css({"border-color": "red"});
                    return false;
                }
                window.location.href = "<?php echo $this->config->base_url(); ?>index.php/website/article_detail/changePasswordOfUser?password=" + $('#oldPassword').val() + "&newpassword=" + $('#newPassword').val();

            }
        }

        (function (b, r, a, n, c, h, _, s, d, k) {
            if (!b[n] || !b[n]._q) {
                for (; s < _.length;) c(h, _[s++]);
                d = r.createElement(a);
                d.async = 1;
                d.src = "https://cdn.branch.io/branch-latest.min.js";
                k = r.getElementsByTagName(a)[0];
                k.parentNode.insertBefore(d, k);
                b[n] = h
            }
        })(window, document, "script", "branch", function (b, r) {
            b[r] = function () {
                b._q.push([r, arguments])
            }
        }, {
            _q: [],
            _v: 1
        }, "addListener applyCode autoAppIndex banner closeBanner closeJourney creditHistory credits data deepview deepviewCta first getCode init link logout redeem referrals removeListener sendSMS setBranchViewData setIdentity track validateCode trackCommerceEvent".split(" "), 0);

        <?php

        $deepMagazineCode = $this->session->userdata('deepMagazineCode');
        $deepMagazineId = $this->session->userdata('deepMagazineId');
        $deepArticleId = $this->session->userdata('deepArticleId');
        $deepAdId = $this->session->userdata('deepAdId');
        $deepShowInputDialog = $this->session->userdata('deepShowInputDialog');

        $this->session->unset_userdata('deepMagazineCode');
        $this->session->unset_userdata('deepMagazineId');
        $this->session->unset_userdata('deepArticleId');
        $this->session->unset_userdata('deepAdId');
        $this->session->unset_userdata('deepShowInputDialog');

        ?>

        <?php if( !empty($deepMagazineCode) || !empty($deepMagazineId) || !empty($deepArticleId) || !empty($deepAdId) ): ?>

        $(document).ready(function () {

            document.getElementById("deepMagazineCode").value = "<?php echo $deepMagazineCode; ?>";
            document.getElementById("deepMagazineId").value = "<?php echo $deepMagazineId; ?>";
            document.getElementById("deepArticleId").value = "<?php echo $deepArticleId; ?>";
            document.getElementById("deepAdId").value = "<?php echo $deepAdId; ?>";
            document.getElementById("deepShowInputDialog").value = "<?php echo $deepShowInputDialog; ?>";

        });

        <?php else: ?>

        $(document).ready(function () {

//                        branch.init('key_test_hmvb20wNC3vNtVhFcz6zqobkAxaho4Qc', function(err, data) {
            branch.init('key_live_deEOxg2Pl9gSzwQRFckC2kclrta5xX9c', function (err, data) {

                var parameters = JSON.parse(data.data);

                console.log(parameters);

                var articleId = "";
                var magazineId = "";
                var magazineCode = "";
                var adId = "";
                var showInputDialog = "";

                if (parameters.$article_id) {
                    articleId = parameters.$article_id;
                }

                if (parameters.$magazine_id) {
                    magazineId = parameters.$magazine_id;
                }

                if (parameters.$magazine_code) {
                    magazineCode = parameters.$magazine_code;
                }

                if (parameters.$ad_id) {
                    adId = parameters.$ad_id;
                }

                if (parameters.$show_input_code_dlg) {
                    showInputDialog = parameters.$show_input_code_dlg;
                }

                <?php

                $resetData = $this->session->userdata('resetdata');
                $this->session->unset_userdata('resetdata');

                ?>

                <?php if( !$resetData ): ?>

                if (err === null && parameters !== null) {

                    <?php if (!empty($this->session->userdata("user_id"))): ?>
                    loggedDeepLink(magazineCode, magazineId, articleId, adId, showInputDialog);
                    <?php else: ?>

                    $.ajax({
                        type: "POST",
                        data: {
                            deepMagazineCode: magazineCode,
                            deepMagazineId: magazineId,
                            deepArticleId: articleId,
                            adId: adId,
                            showInputDialog: showInputDialog
                        },
                        url: "<?php echo $this->config->base_url(); ?>index.php/website/website_login/saveDeepData",
                        success: function (data) {
                            window.location.href = "<?php echo $this->config->base_url(); ?>index.php";
                        }
                    });

                    <?php endif; ?>

                }

                <?php endif; ?>

            });

        });

        <?php endif; ?>

        <?php

        $loggedDeepMagazineCode = $this->session->userdata('loggedDeepMagazineCode');
        $loggedDeepMagazineId = $this->session->userdata('loggedDeepMagazineId');
        $loggedDeepArticleId = $this->session->userdata('loggedDeepArticleId');
        $loggedDeepAdId = $this->session->userdata('loggedDeepAdId');
        $loggedShowInputDialog = $this->session->userdata('loggedShowInputDialog');

        $this->session->unset_userdata('loggedDeepMagazineCode');
        $this->session->unset_userdata('loggedDeepMagazineId');
        $this->session->unset_userdata('loggedDeepArticleId');
        $this->session->unset_userdata('loggedDeepAdId');
        $this->session->unset_userdata('loggedShowInputDialog');

        $this->session->unset_userdata('socialMagazineCode');
        $this->session->unset_userdata('socialMagazineId');
        $this->session->unset_userdata('socialArticleId');
        $this->session->unset_userdata('socialAdId');
        $this->session->unset_userdata('socialShowInputDialog');

        ?>

        <?php if( !empty($loggedDeepMagazineCode) || !empty($loggedDeepMagazineId) || !empty($loggedDeepArticleId) || !empty($loggedDeepAdId) || !empty($loggedShowInputDialog) ): ?>
        loggedDeepLink("<?php echo $loggedDeepMagazineCode; ?>", "<?php echo $loggedDeepMagazineId; ?>", "<?php echo $loggedDeepArticleId; ?>", "<?php echo $loggedDeepAdId; ?>", "<?php echo $loggedShowInputDialog; ?>");
        <?php endif; ?>

        function loggedDeepLink(magazineCode, magazineId, articleId, adId, showInputDialog) {

            if (magazineCode !== "") {

                $.ajax({
                    type: "POST",
                    data: {subsPassword: magazineCode, segValue: "wootrix-list-magazines", deepLink: true},
                    url: "<?php echo $this->config->base_url(); ?>index.php/wootrix-subscribe-password",
                    success: function (data) {

                        console.log(data);

                        var jsonReturn = JSON.parse(data);

                        if (jsonReturn.success) {

                            if (articleId !== "") {
                                goToDeepArticle(magazineId, articleId);
                            } else if (adId !== "") {
                                goToDeepAd(adId);
                            } else if (showInputDialog == "1") {
                                window.location.href = "<?php echo $this->config->base_url(); ?>index.php/wootrix-articles?showInputDialog=1";
                            } else {
                                window.location.href = "<?php echo $this->config->base_url(); ?>index.php/wootrix-article-list-layout?magazineId=" + jsonReturn.magazineId;
                            }

                        } else {

                            if (jsonReturn.hasMagazine) {

                                if (articleId !== "") {
                                    goToDeepArticle(magazineId, articleId);
                                } else if (adId !== "") {
                                    goToDeepAd(adId);
                                } else if (showInputDialog == "1") {
                                    window.location.href = "<?php echo $this->config->base_url(); ?>index.php/wootrix-articles?showInputDialog=1";
                                } else {
                                    window.location.href = "<?php echo $this->config->base_url(); ?>index.php/wootrix-article-list-layout?magazineId=" + jsonReturn.magazineId;
                                }

                            } else {
                                alert("Falha na adição da revista");
                                window.location.href = "<?php echo $this->config->base_url(); ?>index.php";
                            }

                        }

                    }
                });

            } else if (adId !== "") {
                goToDeepAd(adId);
            } else if (showInputDialog == "1") {
                window.location.href = "<?php echo $this->config->base_url(); ?>index.php/wootrix-articles?showInputDialog=1";
            } else {
                goToDeepArticle(magazineId, articleId);
            }

            branch.logout(
                function (err) {

                }
            );

        }

        function goToDeepAd(adId) {

            $.ajax({
                type: "POST",
                data: {adId: adId},
                url: "<?php echo $this->config->base_url(); ?>index.php/webservices/landing_screen/getInternalAdvDetail",
                success: function (data) {

                    console.log(data);

                    var jsonReturn = JSON.parse(data);

                    if (jsonReturn.success) {
                        window.location.href = jsonReturn.data.link;
                    }

                }
            });

        }

        function goToDeepArticle(magazineId, articleId) {

            $.ajax({
                type: "POST",
                data: {magazineId: magazineId, articleId: articleId},
                url: "<?php echo $this->config->base_url(); ?>index.php/webservices/landing_screen/getInternalArticleDetail",
                success: function (data) {

                    var jsonReturn = JSON.parse(data);

                    var fullLink = jsonReturn.data.openArticles.fullSoruce;

                    var newLocation = "";

                    if (fullLink === "") {
                        var createdSource = jsonReturn.data.openArticles.createdSource;
                        newLocation = "<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=" + createdSource + "&articleId=" + articleId + "&magazineId=" + magazineId;
                    } else {
                        newLocation = "<?php echo $this->config->base_url() ?>index.php/registerAccess?url=" + encodeURIComponent(fullLink) + "&magazineId=" + magazineId + "&articleId=" + articleId;
                    }

                    window.location.href = newLocation;

                }
            });

        }

        function getCheckedBoxes(pageName, magzineId, magArtId, artId) {

            //$('#getCheckedBoxes').click(function(event) {

            var checkboxValues = [];
            $('input[type="checkbox"]:checked').each(function (index, elem) {
                checkboxValues.push($(elem).val());
            });
            window.location.href = "<?php echo $this->config->base_url(); ?>index.php/wootrix-save-search-criteria?sessionData=" + checkboxValues + "&pageName=" + pageName + "&magazineId=" + magzineId + "&magArtId=" + magArtId + "&articleId=" + artId;
            //});

        }

        function loadPageOnce() {
            location.reload();
        }

        function getCheckedValue(data) {
            if (data == "english") {
                $("#english").attr("checked", true);
                $("#spanish").attr("checked", false);
                $("#portuguese").attr("checked", false);
            }
            if (data == "spanish") {
                $("#english").attr("checked", false);
                $("#spanish").attr("checked", true);
                $("#portuguese").attr("checked", false);
            }
            if (data == "portuguese") {
                $("#english").attr("checked", false);
                $("#spanish").attr("checked", false);
                $("#portuguese").attr("checked", true);
            }
        }


    </script>
    <?php

    if ($this->session->userdata("user_id") != '') {
    ?>

    <?php
    $categoryObj = $this->load->model("webservices/category_model");
    $categoryObj = new category_model();
    if ($this->session->userdata('langSelect') != '') {
        if ($this->session->userdata('langSelect') == 'english') {
            $languageId = '1';
        } else if ($this->session->userdata('langSelect') == 'spanish') {
            $languageId = '3';
        } else if ($this->session->userdata('langSelect') == 'portuguese') {
            $languageId = '2';
        }
        $categoryObj->set_language($languageId);
    } else {
        $categoryObj->set_language('1');
    }
    if ($this->session->userdata("langSelect") == 'english') {
        $categoryObj->set_language_name('en');
    } else if ($this->session->userdata("langSelect") == 'portuguese') {
        $categoryObj->set_language_name('pt');
    } else if ($this->session->userdata("langSelect") == 'spanish') {
        $categoryObj->set_language_name('es');
    }
    $topics = $categoryObj->getTopics();


    $articleObj = $this->load->model("webservices/new_articles_model");
    $articleObj = new new_articles_model();
    $articleObj->set_token($this->session->userdata("user_id"));
    $articleObj->set_language_name("en");
    $getLangId = $articleObj->getLanguageId();
    $articleObj->set_language_id($getLangId['id']);
    $threeMagazines = $articleObj->getThreeMagazineOnly();

    /* end */

    /*
     * twitter
     */


    $query = $this->db->select('*')
        ->from('tbl_users')
        ->where('id', $this->session->userdata('user_id'))
        ->get();
    //echo "_L".$this->db->last_query();
    $socialLoginRow = $query->row_array();
    $row = $query->row_array();
    //echo "<pre>";print_r($magzineDetailsChanges);die;

    if ($magzineDetailsChanges['bar_color'] != '') { ?>
    <header class="clearfix" style="background:<?php echo $magzineDetailsChanges['bar_color']; ?>">
        <?php } else {
        ?>
        <header class="clearfix">
            <?php } ?>
            <div class="container clearfix">
                <div class="span6">
                    <figure class="logo-container">
                        <?php if ($magzineDetailsChanges['customer_logo'] != '') { ?>
                            <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-landing-screen"> <img
                                        src="<?php echo $this->config->base_url(); ?>assets/Magazine_cover/customer_logo/<?php echo $magzineDetailsChanges['customer_logo']; ?>"
                                        alt="logo"/></a>

                        <?php } else {
                            ?>
                            <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-landing-screen"> <img
                                        class="autoHeight"
                                        src="<?php echo $this->config->base_url(); ?>images/website_images/logo.png"
                                        alt="logo"/></a>
                        <?php } ?>
                    </figure>
                    <?php if ($magzineDetailsChanges['customer_logo'] != '') { ?>
                        <div class="logo_text">
                            <?php //echo $magzineDetailsChanges['title'];  ?>
                        </div>
                    <?php } ?>
                </div>
                <span id="validRes"><?php echo $this->session->flashdata("subsMsg"); ?></span>
                <div class="span6 right">
                    <div class="login-box clearfix">
                        <?php
                        if (($this->uri->segment(1) == 'wootrix-contact-us') || ($this->uri->segment(1) == 'wootrix-about-us') || ($this->uri->segment(1) == 'wootrix-terms-services') || ($this->uri->segment(1) == 'wootrix-privacy-policy')) {

                        } else {
                            ?>
                            <span class="my-account">
                                    <?php
                                    $exp = explode(":", $row['photoUrl']);

                                    if ($exp[0] == "http" || $exp[0] == "https") {
                                        $url = $row['photoUrl'];
                                        $url_back = $row['photoUrl'];
                                    } else {
                                        if ($row['photoUrl'] != '') {
                                            $url = $this->config->base_url() . "assets/user_image/" . $row['photoUrl'];
                                            $url_back = $this->config->base_url() . "assets/user_image/" . $row['photoUrl'];
                                        } else {
                                            $url = $this->config->base_url() . "images/website_images/profile-pic.png";
                                            $url_back = '';
                                        }
                                    }
                                    ?>
                                <img src="<?php echo $url; ?>" alt="Profile image"/>

                                </span>
                            <?php
                        }

                        if ($this->session->userdata("langSelect") == "english") {
                            $this->lang->load('en');
                        } elseif ($this->session->userdata("langSelect") == "spanish") {
                            $this->lang->load('sp');
                        } elseif ($this->session->userdata("langSelect") == "portuguese") {
                            $this->lang->load('po');
                        }
                        if ($this->session->userdata("langSelect") == '') {
                            $this->session->set_userdata("langSelect", "english");
                            $this->lang->load('en');
                        }
                        ?>

                        <div class="account-detail-box" id="content-box">
                            <div class="arrow-up"></div>
                            <h4><?php echo $this->lang->line("content_web"); ?>
                                <!--                                    <input class="cancelBttn SocialIcons" type="button" value="<?php echo $this->lang->line("add_account"); ?>"  />-->
                                <input class="submitBttn" type="button"
                                       value="<?php echo $this->lang->line("done_button"); ?>" id="getCheckedBoxes"
                                       name="getCheckedBoxes"
                                       onclick="getCheckedBoxes('<?php echo $this->uri->segment(1); ?>', '<?php echo $_GET['magazineId']; ?>', '<?php echo $_GET['magArtId']; ?>', '<?php echo $_GET['articleId']; ?>');"/>
                                <input class="cancelBttn" type="button"
                                       value="<?php echo $this->lang->line("cancel_button"); ?>"
                                       onclick="loadPageOnce();"/>
                            </h4>


                            <div class="drop-down-options">
                                <div class="clearfix"></div>

                                <div class="setting-box"><span class="setting" id="setting-bttn"><img
                                                src="<?php echo $this->config->base_url(); ?>images/website_images/setting-icon.png"
                                                alt=""/></span>
                                    <strong><?php echo $this->lang->line("topics_web"); ?></strong>

                                </div>


                            </div>


                            <div class="drop-down-options">
                                <div class="setting-box"><a
                                            href="<?php echo $this->config->base_url(); ?>index.php/wootrix-list-magazines"><?php echo $this->lang->line("see_all_web"); ?></a>
                                    <strong><?php echo $this->lang->line("Magazines"); ?></strong></div>
                                <ol class="clearfix">
                                    <?php foreach ($threeMagazines as $m) { ?>
                                        <li>
                                            <a href="<?php echo $this->config->base_url(); ?>index.php/wootrix-article-list-layout?magazineId=<?php echo $m['id']; ?>"><img
                                                        src="<?php echo $this->config->base_url(); ?>assets/Magazine_cover/<?php echo $m['cover_image']; ?>"
                                                        alt=""/></a></li>
                                    <?php } ?>
                                </ol>

                            </div>

                            <a class="logout-btn" href="javascript:;"
                               onclick="logoutUser();"><?php echo $this->lang->line("log_out_web"); ?></a>

                        </div>


                        <div class="account-detail-box" id="my-account">
                            <div class="arrow-up"></div>
                            <h4 class="no-margin"><?php echo $this->lang->line("my_account_web"); ?></h4>

                            <div id="drag-and-drop-zone" class="account-picture">

                                <img src="<?php echo $url_back; ?>" class="fixWidth" alt=""/>
                                <div class="profile-picture"><img src="<?php echo $url; ?>" alt=""/></div>

                                <div class="image-upload">
                                    <label for="file-input">
                                        <div class="camera-icon"/>
                                    </label>
                                    <input id="file-input" type="file" style="display: none;"/>
                                </div>
                            </div>
                            <div class="nameBox">
                                <h5><?php echo $this->session->userdata('user_name'); ?></h5>
                                <small><?php echo $this->session->userdata('user_email'); ?></small>
                            </div>

                        </div>

                        <div class="drop-down-options">
                            <ul>
                                <li><span id="change-language"><?php echo $this->lang->line("change_language"); ?>
                                        <em></em></span></li>
                            </ul>
                        </div>
                    </div>


                    <div class="account-detail-box" id="language-options">
                        <div class="arrow-up"></div>
                        <h4 class="no-margin"><?php echo $this->lang->line("change_language"); ?></h4>

                        <div class="drop-down-options">
                            <form method="POST" name="langChange" id="langChange"
                                  action="<?php echo $this->config->base_url() . 'index.php/website/website_login/changeWebLanguage'; ?>">
                                <ul>
                                    <li class="heightIncrease"><?php echo $this->lang->line("english_web"); ?>
                                        <div class="checkbox-container"><input type="checkbox" name="lang" id="english"
                                                                               value="english"
                                                                               onclick="getCheckedValue(this.value);" <?php if ($this->session->userdata("langSelect") == "english") { ?> checked="checked" <?php } ?>/><label></label>
                                        </div>
                                    </li>
                                    <li class="heightIncrease"><?php echo $this->lang->line("spanish_web"); ?>
                                        <div class="checkbox-container"><input type="checkbox" name="lang" id="spanish"
                                                                               value="spanish"
                                                                               onclick="getCheckedValue(this.value);" <?php if ($this->session->userdata("langSelect") == "spanish") { ?> checked="checked" <?php } ?>/><label></label>
                                        </div>
                                    </li>
                                    <li class="heightIncrease"><?php echo $this->lang->line("portuguese_web"); ?>
                                        <div class="checkbox-container"><input type="checkbox" name="lang"
                                                                               value="portuguese" id="portuguese"
                                                                               onclick="getCheckedValue(this.value);" <?php if ($this->session->userdata("langSelect") == "portuguese") { ?> checked="checked" <?php } ?>/><label></label>
                                        </div>
                                    </li>
                                </ul>
                                <input type="hidden" name="langPage" value="<?php echo $this->uri->segment(1); ?>"/>
                                <input type="submit" name="submitLang" class="ok-btn"
                                       value="<?php echo $this->lang->line("ok_web"); ?>"/>
                                <!--                                <a class="ok-btn" href="#"><?php //echo $this->lang->line("ok_web");       ?></a>-->
                            </form>
                        </div>

                    </div>


                    <div class="account-detail-box" id="change-password-options">
                        <div class="arrow-up"></div>
                        <h4 class="no-margin"><?php echo $this->lang->line("change_password_web"); ?></h4>

                        <div class="drop-down-options">
                            <div class="input-container">
                                <input type="password"
                                       placeholder="<?php echo $this->lang->line("old_password_web"); ?>"
                                       id="oldPassword" name="oldPassword"/>
                            </div>
                            <div class="input-container">
                                <input type="password" placeholder="<?php echo $this->lang->line("New_Password"); ?>"
                                       id="newPassword" name="newPassword"/>
                            </div>
                            <div class="input-container">
                                <input type="password"
                                       placeholder="<?php echo $this->lang->line("confirm_password_web"); ?>"
                                       id="confirmPassword" name="confirmPassword"/>
                            </div>
                            <?php
                            if ($this->session->flashdata('errorPassword') != '') {
                                ?>
                                <p id="fadeOut1"><?php echo $this->session->flashdata('errorPassword'); ?></p>
                                <script>
                                    $('#fadeOut1').fadeOut(2000);
                                </script>
                            <?php } ?>
                            <a class="ok-btn" onclick="checkPasswordValidation();"
                               href="javascript:void(0)"><?php echo $this->lang->line("ok_web"); ?></a>
                        </div>

                    </div>
                    <?php
                    //echo "<pre>";print_r($this->session->userdata);die;
                    //                    if ($this->session->userdata('saved') == '1') {
                    //
                    //                    } else {
                    //                        if ($this->session->userdata('user_login_social_id') == '') {
                    //
                    //                            $this->load->library('facebook');
                    //                            $facebook = new Facebook(array(
                    //                                'appId' => '1627701464153107', // Facebook App ID
                    //                                'secret' => '235c16754f74c0cd8042937ab1cb890e', // Facebook App Secret
                    //                                'cookie' => true,
                    //                            ));
                    //                            $user = $facebook->getUser();
                    //
                    //                            if ($user) {
                    //
                    //                                $user_profile = $facebook->api('/me');
                    //                                //echo "<pre>";print_r($user_profile);die;
                    //                                $userObj = $this->load->model("webservices/users_model");
                    //                                $userObj = new users_model();
                    //                                $userObj->set_facebook_id($user_profile['id']);
                    //                                $userObj->set_email($user_profile['email']);
                    //                                $userObj->set_image($user_profile['id']);
                    //                                $userObj->addAccountWeb();
                    //                            }
                    //                            $loginUrl = $facebook->getLoginUrl(array(
                    //                                'scope' => 'email', // Permissions to request from the user
                    //                            ));
                    //                        }
                    //                    }

                    ?>
                    <div class="account-detail-box" id="add-account-wrap">
                        <div class="addAccount">
                            <ul>
                                <li>
                                    <div class="social-link">

                                        <samp class="clearfix">
                                            <figure class="fb">
                                                <?php if ($row['facebook_id'] != '') { ?>
                                                    <a href="#"></a>
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php echo $loginUrl; ?>"></a>
                                                <?php } ?>

                                            </figure>
                                            Facebook<em><?php if ($row['facebook_id'] != '') { ?>Connected<?php
                                                } else {
                                                    echo "Connect";
                                                }
                                                ?></em></samp>

                                        <samp class="clearfix">
                                            <figure class="twitter">
                                                <?php if ($row['twitter_id'] != '') { ?>
                                                    <a href="#"></a>
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php echo $this->config->base_url(); ?>index.php/website/login_twitter/index?add=1"></a>
                                                <?php } ?>
                                            </figure>
                                            Twitter<em><?php
                                                if ($row['twitter_id'] != '') {
                                                    echo "Connected";
                                                } else {
                                                    echo "Connect";
                                                }
                                                ?></em></samp>

                                        <samp class="clearfix">
                                            <figure class="linkedin">
                                                <a href="<?php echo $this->config->base_url(); ?>index.php/website/loginWithLinkedIn?add=1"></a>

                                            </figure>
                                            Linkedin<em><?php if ($row['linkedin_id'] != '') { ?>Connected<?php
                                                } else {
                                                    echo "Connect";
                                                }
                                                ?></em></samp>

                                        <samp class="clearfix">
                                            <figure class="google">
                                                <?php if ($row['google_id'] != '') { ?>
                                                    <a href="#"></a>
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php echo $googleLogin; ?>"></a>
                                                <?php } ?>
                                            </figure>
                                            Google +<em><?php
                                                if ($row['google_id'] != '') {
                                                    echo "Connected";
                                                } else {
                                                    echo "Connect";
                                                }
                                                ?></em>
                                        </samp>

                                    </div>
                                </li>
                            </ul>

                        </div>

                    </div>


                </div>

            </div>

            </div>

        </header>


        <?php } ?>

        <script src="<?php echo base_url() ?>js/ajaxupload.3.6.js"></script>


        <script type="text/javascript">
            $(function () {
                jQuery(".SocialIcons").click(function () {
                    if (!$(".addAccount").hasClass("openIcon")) {
                        $(".addAccount").stop().slideDown().addClass("openIcon").siblings().hide();
                    }
                    else {
                        $(".addAccount").stop().slideUp().removeClass("openIcon").siblings().show();
                    }

                });

                $("#drag-and-drop-zone").dmUploader({
                    url: "<?php echo base_url() ?>index.php/upload-img",
                    allowedTypes: "image/*",
                    extFilter: ["jpg", "jpeg", "png"],
                    onUploadComplete: function () {
                        location.reload();
                    }
                });

            });

            function getSearchKeyword(data, seg) {
                var segValue = $("#segValue").val();
                var magValue = $("#magazineId").val();
                //alert(magValue);
                $("#searchRes").show();
                $(document).ready(function () {
                    $.ajax(
                        {
                            url: "<?php echo $this->config->base_url(); ?>index.php/wootrix-search-magzine",
                            type: "POST",
                            data: {keyValue: data, keySeg: segValue, keyMagId: magValue},
                            success: function (data) {
                                //alert(data);
                                $("#searchRes").html(data);
                                //$("#getValue").val(data);
                            }


                        });
                });
            }

            function getResult(title, ID, magID, source) {


                var getValues = $("#getValue").val(title);

                magID = 1;
                $("#searchRes").hide();
                window.location.href = "<?php echo $this->config->base_url(); ?>index.php/wootrix-article-detail?source=" + source + "&articleId=" + ID + "&searchId=search&magazineId=" + magID;
            }
        </script>
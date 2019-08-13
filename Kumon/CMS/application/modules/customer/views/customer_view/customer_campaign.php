<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>::Wootrix | Category::</title>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

    <!-- Load jQuery and the validate plugin -->
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
    <script>

        $(function () {

            $("#dob").datepicker({
                yearRange: "1900:3000",
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                maxDate: new Date()
            });

            $("#codeForm").validate({
                rules: {
                    name: "required",
                    layout: "checked"
                },
                messages: {
                    name: "Campo 'Nome da Campanha' é obrigatório",
                    layout: "Campo 'Tipo de Layout' é obrigatório"
                },
                errorElement: 'div',
                errorLabelContainer: '.errorTxt',
                submitHandler: function (form) {
                    form.submit();
                }
            });

            hideBanners(<?php echo $campaign["layout"] != 0 && $campaign["layout"] != 5 ? "false" : "true"?>, <?php echo $campaign["layout"]; ?>);

        });

        var removeMagazine = function (counter) {

            var magazineCounter = $("#magazine_content").children().length;

            if (magazineCounter > 1) {
                $("#magazine_" + counter).remove();
            }

        }

        var removeArticle = function (magazineCounter, articleCounter) {

            var magazineArticleCounter = $("#magazine_article_content_" + magazineCounter).children().length;

            if (magazineArticleCounter > 1) {
                $("#magazine_article_" + magazineCounter + "_" + articleCounter).remove();
            }

        }

        var addMagazineContent = function () {

            var magazineCounter = $("#magazineCounter").val();

            $.ajax({
                type: "POST",
                data: {counter: magazineCounter},
                url: "<?php echo $this->config->base_url(); ?>index.php/getMagazineContent",
                success: function (data) {
                    $("#magazine_content").append(data);
                    addMagazineArticleContent(magazineCounter, true);
                    magazineCounter++;
                    $("#magazineCounter").val(magazineCounter);
                }

            });

        }

        var addMagazineArticleContent = function (magazineCounter, reset) {

            var magazineId = $("#magazine_" + magazineCounter).find(":selected").val();
            var articleCounter = $("#magazineArticleCounter_" + magazineCounter).val();

            $.ajax({
                type: "POST",
                data: {
                    magazineId: magazineId,
                    counterMagazine: magazineCounter,
                    counterMagazineArticle: articleCounter
                },
                url: "<?php echo $this->config->base_url(); ?>index.php/getMagazineArticleContent",
                success: function (data) {
                    if (reset) {
                        $("#magazine_article_content_" + magazineCounter).html(data);
                    } else {
                        $("#magazine_article_content_" + magazineCounter).append(data);
                    }

                    articleCounter++;
                    $("#magazineArticleCounter_" + magazineCounter).val(articleCounter);

                }

            });

        };

        var hideBanners = function (hide, layout) {

            var divBanner = $("#banners");
            var leftRightBanner = $("#banner1");
            var topBottomBanner = $("#banner2");

            switch (layout) {

                case 1: {
                    leftRightBanner.attr("placeholder", "Banner esquerdo");
                    topBottomBanner.attr("placeholder", "Banner rodapé");
                    break;
                }

                case 2: {
                    leftRightBanner.attr("placeholder", "Banner esquerdo");
                    topBottomBanner.attr("placeholder", "Banner topo");
                    break;
                }

                case 3: {
                    leftRightBanner.attr("placeholder", "Banner direito");
                    topBottomBanner.attr("placeholder", "Banner rodapé");
                    break;
                }

                case 4: {
                    leftRightBanner.attr("placeholder", "Banner direito");
                    topBottomBanner.attr("placeholder", "Banner topo");
                    break;
                }

                default: {
                    break;
                }

            }

            if (hide) {
                divBanner.hide();
            } else {
                divBanner.show();
            }

        }



    </script>

    <!-- Define some CSS -->
    <style type="text/css">
        .label {
            width: 100px;
            text-align: right;
            float: left;
            padding-right: 10px;
            font-weight: bold;
        }

        add_subadmin.error, .output {
            color: #FFFFFF;
            font-weight: bold;
        }

        .error {
            color: #F00;
        }

    </style>


</head>
<body>


<div class="fix-container clearfix">

    <div class="add-new-customer clearfix">

        <div style="color: red">
            <p><?php echo $msg;
                echo $this->session->flashdata("msg"); ?></p>
            <div style="color: green"><?php echo $this->session->flashdata("susmsg"); ?></div>
            <?php echo validation_errors(); ?>
        </div>

        <div class="errorTxt"></div>
        <br/>

        <form name="codeForm" id="codeForm"
              action="<?php echo $this->config->base_url(); ?>index.php/customerCampaign?id=<?php echo $campaignId; ?>"
              method="post">

            <label><b>Tipo de Layout</b></label>

            <br/><br/>

            <div style="float: left;">
                <input onclick="hideBanners(false, 1);" type="radio" required name="layout"
                       value="1" <?php echo $campaign["layout"] == 1 ? "checked" : ""; ?> /> <br/>
                <img src="<?php echo $this->config->base_url(); ?>images/layout1.png" width="160px" height="90px"/>
            </div>

            <div style="float: left; margin-left: 20px;">
                <input onclick="hideBanners(false, 2);" type="radio" name="layout"
                       value="2" <?php echo $campaign["layout"] == 2 ? "checked" : ""; ?> />
                <br/>
                <img src="<?php echo $this->config->base_url(); ?>images/layout2.png" width="160px" height="90px"/>
            </div>

            <div style="float: left; margin-left: 20px;">
                <input onclick="hideBanners(false, 3);" type="radio" name="layout"
                       value="3" <?php echo $campaign["layout"] == 3 ? "checked" : ""; ?> />
                <br/>
                <img src="<?php echo $this->config->base_url(); ?>images/layout3.png" width="160px" height="90px"/>
            </div>

            <div style="float: left; margin-left: 20px;">
                <input onclick="hideBanners(false, 4);" type="radio" name="layout"
                       value="4" <?php echo $campaign["layout"] == 4 ? "checked" : ""; ?> />
                <br/>
                <img src="<?php echo $this->config->base_url(); ?>images/layout4.png" width="160px" height="90px"/>
            </div>

            <div style="float: left; margin-left: 20px;">
                <input onclick="hideBanners(true, 0);" type="radio" name="layout"
                       value="5" <?php echo $campaign["layout"] == 5 ? "checked" : ""; ?> />
                <br/>
                <img src="<?php echo $this->config->base_url(); ?>images/layout5.png" width="160px" height="90px"/>
            </div>

            <div id="banners"
                 style="display: <?php echo $campaign["layout"] != 0 && $campaign["layout"] != 5 ? "block" : "none"; ?>">

                <br clear="all"/><br clear="all"/>

                <label><b>Banners</b></label>

                <br/><br/>

                <input type="text" name="banner1" id="banner1" value="<?php echo $campaign["banner1"]; ?>"
                />

                <br/><br/>

                <input type="text" name="banner2" id="banner2" value="<?php echo $campaign["banner2"]; ?>"
                />

            </div>

            <br clear="all"/>

            <div class="placeholder"></div>

            <br clear="all"/>

            <label><b>Conteúdo</b></label>

            <br/><br/>

            <a href="javascript:;" onclick="addMagazineContent();">+ Revista</a>

            <br/><br/>

            <div id="magazine_content">

                <?php $i = 0;; ?>

                <?php if (count($campaignContent) > 0): ?>

                    <?php

                    $this->load->model("customer/customer_magazine_model");
                    $obj = new customer_magazine_model();

                    $lastIdMagazine = 0;

                    ?>

                    <?php foreach ($campaignContent as $content): ?>

                        <?php $idMagazine = $content["id_magazine"]; ?>

                        <?php if ($lastIdMagazine != $idMagazine): ?>

                            <?php

                            $data["counter"] = $i;

                            $data_result = $obj->getCustomerMagazine();

                            foreach ($data_result as &$magazine) {

                                if ($idMagazine == $magazine["id"]) {
                                    $magazine["checked"] = true;
                                } else {
                                    $magazine["checked"] = false;
                                }

                            }

                            $data["magazines"] = $data_result;
                            $data["idMagazine"] = $idMagazine;
                            $data["campaignContent"] = $campaignContent;

                            $this->load->view('select_magazine_content', $data);

                            $i++;

                            ?>

                        <?php endif; ?>

                        <?php $lastIdMagazine = $idMagazine; ?>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

            <br/><br/>

            <div id="open_article_content"></div>

            <br/><br/>

            <div class="input-container clearfix">
                <input type="submit" value="save" name="save"/>
                <input type="button" value="back" style="margin-right: 10px;" onclick="window.history.back();"/>
                <input type="hidden" name="action" value="save"/>
            </div>

            <input type="hidden" id="magazineCounter" value="<?php echo $i; ?>"/>
            <input type="hidden" id="openArticleCounter" value="0"/>

        </form>

    </div>

</div>


<script>

    // When the browser is ready...
    $(function () {

        // Setup form validation on the #register-form element
        $("#codeForm").validate({
            // Specify the validation rules
            rules: {
                password: "required"
            },
            // Specify the validation error messages
            messages: {
                password: "Código obrigatório",
            },
            submitHandler: function (form) {
                form.submit();
            }
        });

    });

</script>

</body>
</html>
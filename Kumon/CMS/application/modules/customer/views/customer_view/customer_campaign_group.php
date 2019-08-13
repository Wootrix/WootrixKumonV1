<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>::Wootrix | Agrupamento de Campanhas::</title>
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

//            $("#codeForm").validate({
//                rules: {
//                    name: "required",
//                    layout: "checked"
//                },
//                messages: {
//                    name: "Campo 'Nome da Campanha' é obrigatório",
//                    layout: "Campo 'Tipo de Layout' é obrigatório"
//                },
//                errorElement : 'div',
//                errorLabelContainer: '.errorTxt',
//                submitHandler: function (form) {
//                    form.submit();
//                }
//            });

        });

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

        <div class="errorTxt"></div> <br />

        <form name="codeForm" id="codeForm"
              action="<?php echo $this->config->base_url(); ?>index.php/campaignGroup?id=<?php echo $group["id"]; ?>"
              method="post">

            <label><b>Nome do Agrupamento</b></label>

            <br/><br />

            <input type="text" name="name" id="name" required value="<?php echo $group["name"]; ?>" />

            <br/><br />

            <label><b>Campanhas</b></label>

            <br/><br />

            <select name="campaigns[]" multiple size="10">
                <?php foreach($campaigns as $k => $campaign): ?>
                    <?php $nrDevice = $k + 1; ?>
                    <option value="<?php echo $campaign["id"];?>" <?php echo in_array($campaign["id"], $groupCampaigns) ? "selected" : ""; ?>>Televisão <?php echo $nrDevice; ?></option>
                <?php endforeach;?>
            </select>

            <br/><br />

            <label><b>Copiar o conteúdo da campanha:</b></label>
            <select name="copyCampaign">
                <option value="">Não copiar</option>
                <?php foreach($campaigns as $k => $campaign): ?>
                    <?php $nrDevice = $k + 1; ?>
                    <option value="<?php echo $campaign["id"];?>">Televisão <?php echo $nrDevice; ?></option>
                <?php endforeach;?>
            </select>

            <br/><br />

            <div class="input-container clearfix">
                <input type="submit" value="save" name="save"/>
                <input type="button" value="back" style="margin-right: 10px;" onclick="window.history.back();"/>
                <input type="hidden" name="action" value="save" />
            </div>

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
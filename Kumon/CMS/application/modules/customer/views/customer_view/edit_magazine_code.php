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

        <form name="codeForm" id="codeForm" action="<?php echo $this->config->base_url(); ?>index.php/edit_magazine_code?rowId=<?php echo $_GET["rowId"]; ?>" method="post">

            <div class="input-field-cont">
                <label>Código</label>
                <input id="password" type="text" name="password" value="<?php echo $code; ?>" maxlength="50">
            </div>

            <div class="input-container clearfix">
                <input type="submit" value="save" name="save"/>
                <input type="button" value="back" style="margin-right: 10px;" onclick="window.history.back();" />
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
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

</head>

<body>

<div class="fix-container clearfix">

    <div class="add-new-customer clearfix">

        <?php
        $attributes = array('name' => 'edit_group', 'id' => 'edit_group', 'class' => 'form-horizontal');
        echo form_open($this->config->base_url() . 'index.php/editGroup?rowId=' . $_GET['rowId'], $attributes);
        ?>

        <div style="color: red">

            <p><?php echo $msg;
                echo $this->session->flashdata("msg"); ?>
            </p>

            <?php echo validation_errors(); ?>

        </div>

        <div class="login-credential clearfix">

            <h2>Edição de Grupo de Usuários</h2>

            <div class="input-field-cont">
                <label><?php echo "Nome"; ?></label>
                <input id="group_name" type="text" name="group_name" maxlength="50" value="<?php echo $data_result["group"]; ?>">
            </div>

            <div class="input-field-cont">

                <label><?php echo "Localidade"; ?></label>

                <select name="location[]" id="location" multiple style="height: 150px;">
                    <?php foreach ($locations as $location): ?>
                        <option value="<?php echo $location['location']; ?>" <?php echo in_array($location, $groupLocations) ? "selected" : ""; ?>><?php echo $location['location']; ?></option>
                    <?php endforeach; ?>
                </select>

            </div>

            <div class="input-container clearfix">
                <input type="submit" value="save" name="save"/>
            </div>

            <input type="hidden" name="groupId" id="groupId" value="<?php echo $data_result["id"]; ?>" />

        </div>

        <?php echo form_close(); ?>

    </div>

    <script>

        $(function() {

            $("#edit_group").validate({
                rules: {
                    group_name: "required"
                },
                messages: {
                    group_name: "Por favor, insira um nome para o grupo."
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

        });

    </script>

</div>

</body>

</html>
<script src="js/jquery.js"></script>

<script>

    function DELETE(rowId) {

        var disable = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_delete_this_record"); ?>");

        if (disable === true) {

            var msg = '';

            $.ajax({
                type: "POST",
                data: {val: rowId},
                dataType: "html",
                url: "<?php echo $this->config->base_url(); ?>index.php/deleteGroup",
                success: function(data)
                {
                    location.reload();
                }
            });

        }

    }

</script>

</head>

<div class="subheader">

    <div class="fix-container clearfix">
        <h4>Grupo de Usuário</h4>
        <a class="add-new" href="<?php echo $this->config->base_url();?>index.php/newGroup"><?php echo $this->lang->line('Add_New') ?></a>
    </div>

</div>

<div class="fix-container clearfix">

    <div class="category-list">

        <div class="table-container">

            <div style="color: green">

                <p>
                    <?php
                    echo $msg;
                    echo $this->session->flashdata("suc_msg");
                    ?>
                </p>

                <div style="color: red">
                    <p><?php
                        echo $msg;
                        echo $this->session->flashdata("msg");
                        ?>
                    </p>
                </div>

                <table id="category" border="0" cellpadding="0" cellspacing="0">

                    <thead>
                        <tr>
                            <th>Grupo</th>
                            <th><?php echo $this->lang->line('Action') ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if ($data_result != "") {
                            foreach ($data_result as $value) {
                                ?>
                                <tr>
                                    <td><?php echo $value['group']; ?></td>
                                    <td>
                                        <a class="edit-icon" href="<?php echo $this->config->base_url();?>index.php/editGroup?rowId=<?php echo $value['id']; ?>">Edit</a>
                                        <a class="delete-bttn" href="#" onclick="DELETE(<?php echo $value['id']; ?>);"></a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>

            </div>

        </div>

    </div>

</div>

</body>

</html>
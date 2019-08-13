

</head>

<div class="subheader">
    <div class="fix-container clearfix">
        <h4>Layout de Campanha de Cliente</h4>
        <a class="add-new" href="<?php echo $this->config->base_url() . 'index.php/campaignLayoutDetail' ?>"><?php echo $this->lang->line('Add_New') ?></a>
    </div>
</div>

<div class="fix-container clearfix">

    <div id="divMessages" style="color: red">
        <div style="color: green"><?php echo $this->session->flashdata("msg"); ?></div>
    </div>

    <div class="category-list">

        <div class="table-container">

            <table border="0" cellpadding="0" cellspacing="0">

                <tr>
                    <th>Cliente</th>
                    <th>Ações</th>
                </tr>

                <?php if( is_array($customers) ): ?>

                    <?php foreach( $customers as $customer ): ?>

                        <tr>
                            <td><?php echo $customer["name"]; ?></td>
                            <td><a class="edit-icon" href="<?php echo $this->config->base_url();?>index.php/campaignLayoutDetail?id=<?php echo $customer['id']; ?>">Edit</a></td>
                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </table>

        </div>

    </div>

    <div id="pagination-codes"></div>

</div>

<!--Pop up-->
<div class="layer-bg">
    <div class="popup-container info-popup clearfix">
        <div class="close-bttn"></div>
        <div class="displayData popUpDiv">

        </div>
    </div>
</div>

</body>
</html>
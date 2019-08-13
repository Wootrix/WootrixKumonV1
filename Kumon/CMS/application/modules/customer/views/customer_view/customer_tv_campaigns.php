

</head>

<div class="subheader">
    <div class="fix-container clearfix">
        <h4>Campanhas</h4>
        <a class="add-new" href="<?php echo $this->config->base_url() . 'index.php/campaignGroup' ?>"><?php echo "Novo Agrupamento" ?></a>
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
                    <th>Nome</th>
                    <th>Agrupamento</th>
                    <th>Ações</th>
                </tr>

                <?php foreach( $campaigns as $k => $campaign ): ?>

                    <?php $nrDevice = $k + 1;?>

                    <tr>
                        <td><?php echo "Televisão " . $nrDevice; ?></td>
                        <td><a href="<?php echo $this->config->base_url() . 'index.php/campaignGroup?id=' . $campaign["id_group"] ?>"><?php echo $campaign["name"]; ?></a></td>
                        <td><a class="edit-icon" href="<?php echo $this->config->base_url();?>index.php/customerCampaign?id=<?php echo $campaign['id']; ?>">Edit</a></td>
                    </tr>

                <?php endforeach; ?>

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
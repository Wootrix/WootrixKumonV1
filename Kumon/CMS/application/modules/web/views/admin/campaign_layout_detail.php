
    <script>

        var addDevices = function(){

            var nrDevices = $("#nrDevices").val();
            var customerId = $("#customer_id").find(":selected").val();

            $.ajax({
                type: "POST",
                data: {nrDevices: nrDevices, idCustomer: customerId},
                url: "<?php echo $this->config->base_url(); ?>index.php/getTextDevice",
                success: function(data)
                {
                    $("#devices").html(data);
                }

            });

        }

    </script>

</head>

<?php
/*SESSION VARIABLE*/
$selcted_permission = $_GET['perId'];
$searchString = '';
$searchString = $this->session->userdata('searchUser');
?>

<div class="subheader">
    <div class="fix-container clearfix">
        <h4>Layout de Campanha</h4>
    </div>
</div>

<div class="fix-container clearfix">

    <div class="add-new-customer clearfix">

        <form action="<?php echo $this->config->base_url(); ?>index.php/campaignLayoutDetail" method="post" class="form-horizontal">

            <div class="login-credential clearfix">

                <div id="permission_data" class="table-container clearfix">

                    <label><b>Cliente</b></label><br />
                    <select name="customer_id" id="customer_id" <?php echo isset( $_GET["id"] ) ? "readonly" : ""; ?>>
                        <?php foreach($customers as $customer): ?>
                            <option value="<?php echo $customer["id"]; ?>" <?php echo $_GET["id"] == $customer["id"] ? "selected" : ""; ?>><?php echo $customer["title"]; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <br /><br />

                    <label><b>Número de Devices</b></label><br />
                    <input type="text" name="nrDevices" id="nrDevices" />

                    <br />

                    <div class="input-container clearfix">
                        <input type="button" value="Gerar" onclick="addDevices();" />
                    </div>

                    <br />

                    <div id="devices">

                        <?php

                        if( is_array($campaigns) ){
                            $data["nrDevices"] = count($campaigns);
                            $data["campaigns"] = $campaigns;
                            $this->load->view('text_device', $data);
                        }

                        ?>

                    </div>
<!---->
<!--                    <b>1)</b> <img src="--><?php //echo $this->config->base_url(); ?><!--images/layout1.png" width="160px" height="90px"/>-->
<!---->
<!--                    <br /><br />-->
<!---->
<!--                    <label><b>URL Layout 1</b></label><br />-->
<!--                    <input type="text" name="campaign_url_1" value="--><?php //echo $urlLayout1; ?><!--" />-->
<!---->
<!--                    <br /><br />-->
<!---->
<!--                    <label><b>URL Signage:</b></label>-->
<!--                    <input type="text" value="--><?php //echo !empty($idCampaign1) ? $contentUrl1 : "" ?><!--" disabled />-->
<!---->
<!--                    <br /><br />-->
<!---->
<!--                    <b>2)</b> <img src="--><?php //echo $this->config->base_url(); ?><!--images/layout2.png" width="160px" height="90px"/>-->
<!---->
<!--                    <br /><br />-->
<!---->
<!--                    <label><b>URL Layout 2</b></label><br />-->
<!--                    <input type="text" name="campaign_url_2" value="--><?php //echo $urlLayout2; ?><!--" />-->
<!---->
<!--                    <br /><br />-->
<!---->
<!--                    <label><b>URL Signage:</b></label>-->
<!--                    <input type="text" value="--><?php //echo !empty($idCampaign2) ? $contentUrl2 : "" ?><!--" disabled />-->
<!---->
<!--                    <br /><br />-->
<!---->
<!--                    <b>3)</b> <img src="--><?php //echo $this->config->base_url(); ?><!--images/layout3.png" width="160px" height="90px"/>-->
<!---->
<!--                    <br /><br />-->
<!---->
<!--                    <label><b>URL Layout 3</b></label><br />-->
<!--                    <input type="text" name="campaign_url_3" value="--><?php //echo $urlLayout3; ?><!--" />-->
<!---->
<!--                    <br /><br />-->
<!---->
<!--                    <label><b>URL Signage:</b></label>-->
<!--                    <input type="text" value="--><?php //echo !empty($idCampaign3) ? $contentUrl3 : "" ?><!--" disabled />-->
<!---->
<!--                    <br /><br />-->
<!---->
<!--                    <b>4)</b> <img src="--><?php //echo $this->config->base_url(); ?><!--images/layout4.png" width="160px" height="90px"/>-->
<!---->
<!--                    <br /><br />-->
<!---->
<!--                    <label><b>URL Layout 4</b></label>-->
<!--                    <input type="text" name="campaign_url_4" value="--><?php //echo $urlLayout4; ?><!--" />-->
<!---->
<!--                    <br /><br />-->
<!---->
<!--                    <label><b>URL Signage:</b></label>-->
<!--                    <input type="text" value="--><?php //echo !empty($idCampaign4) ? $contentUrl4 : "" ?><!--" disabled />-->

                </div>

                <div class="input-container clearfix">
                    <input type="button" value="cancelar" onclick="javascript: window.history.back();" />
                    <input type="submit" value="save" />
                    <input type="hidden" name="action" value="save" />
                </div>

            </div>

        </form>

    </div>

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
<?php

$metric1Checked = in_array(1, $metrics) ? "checked" : "";
$metric2Checked = in_array(2, $metrics) ? "checked" : "";
$metric3Checked = in_array(3, $metrics) ? "checked" : "";
$metric4Checked = in_array(4, $metrics) ? "checked" : "";
$metric5Checked = in_array(5, $metrics) ? "checked" : "";
$metric6Checked = in_array(6, $metrics) ? "checked" : "";

?>

<div class="multiselect-box clearfix">

    <div class="cbox">
        <input type="checkbox" name="metric_1" id="cb_so" value="1" <?php echo $metric1Checked; ?> />
        Usuário
    </div>

    <div class="cbox">
        <input type="checkbox" name="metric_2" id="cb_so" value="1" <?php echo $metric2Checked; ?> />
        Sistema Operacional
    </div>

    <div class="cbox">
        <input type="checkbox" name="metric_3" id="cb_device" value="1" <?php echo $metric3Checked; ?> />
        Dispositivo
    </div>

    <div class="cbox">
        <input type="checkbox" name="metric_4" id="cb_location" value="1" <?php echo $metric4Checked; ?> />
        Localização
    </div>

    <div class="cbox">
        <input type="checkbox" name="metric_5" id="cb_company" value="1" <?php echo $metric5Checked; ?> />
        Empresa
    </div>

    <div class="cbox">
        <input type="checkbox" name="metric_6" id="cb_article" value="1" <?php echo $metric6Checked; ?> />
        Artigos
    </div>

</div>




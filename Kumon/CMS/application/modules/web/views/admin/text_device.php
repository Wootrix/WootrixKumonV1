<?php for( $i = 1; $i <= $nrDevices; $i++ ): ?>

    <?php

    $contentUrl = $this->config->base_url() . 'index.php/getSignageContent?campaign=' . $campaigns[$i - 1]["id"];

    ?>

    <label>URL Campanha <?php echo $i;?></label>
    <input type="text" name="url_signage[]" id="url_signage_<?php echo $i; ?>" value="<?php echo $campaigns[$i - 1]["url"]; ?>" />

    <br />

    <label><b>URL Conteúdo <?php echo $i;?>:</b></label>
    <input type="text" value="<?php echo !empty($campaigns[$i - 1]["id"]) ? $contentUrl : "" ?>" disabled />

    <br /><br />

<?php endfor; ?>


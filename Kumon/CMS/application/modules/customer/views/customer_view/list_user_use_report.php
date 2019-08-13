<?php

$userMetric = false;
$soMetric = false;
$typeMetric = false;
$locationMetric = false;
$companyMetric = false;
$articleMetric = false;

foreach( $metrics as $metric ){

    if( $metric["metric"] == 1 ){
        $userMetric = true;
    }

    if( $metric["metric"] == 2 ){
        $soMetric = true;
    }

    if( $metric["metric"] == 3 ){
        $typeMetric = true;
    }

    if( $metric["metric"] == 4 ){
        $locationMetric = true;
    }

    if( $metric["metric"] == 5 ){
        $companyMetric = true;
    }

    if( $metric["metric"] == 6 ){
        $articleMetric = true;
    }

}

?>

<table border="0" cellpadding="0" cellspacing="0">

    <tr>

        <?php if($userMetric): ?>
            <th>Nome do Usuário</th>
        <?php endif; ?>

        <?php if($soMetric): ?>
            <th>Sistema Operacional</th>
        <?php endif; ?>

        <?php if($typeMetric): ?>
            <th>Tipo de dispositivo</th>
        <?php endif; ?>

        <?php if($locationMetric): ?>
            <th>País</th>
            <th>Estado</th>
            <th>Cidade</th>
        <?php endif; ?>

        <!--        <th>Empresa</th>-->

    </tr>

    <?php foreach( $result as $r ): ?>

        <tr>

            <?php if($userMetric): ?>
                <td><?php echo $r["name"]; ?></td>
            <?php endif; ?>

            <?php if($soMetric): ?>
                <td><?php echo $r["so_access"]; ?></td>
            <?php endif; ?>

            <?php if($typeMetric): ?>
                <td><?php echo $r["type_device_access"]; ?></td>
            <?php endif; ?>

            <?php if($locationMetric): ?>
                <td><?php echo $r["country"]; ?></td>
                <td><?php echo $r["state"]; ?></td>
                <td><?php echo $r["city"]; ?></td>
            <?php endif; ?>

            <!--            <td>--><?php //echo $r["company"]; ?><!--</td>-->

        </tr>

    <?php endforeach; ?>

</table>
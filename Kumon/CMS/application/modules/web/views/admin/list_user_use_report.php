<table border="0" cellpadding="0" cellspacing="0">

    <tr>
        <th>Nome do Usuário</th>
        <th>Sistema Operacional</th>
        <th>Tipo de dispositivo</th>
        <th>País</th>
        <th>Cidade</th>
    </tr>

    <?php foreach( $result as $r ): ?>

        <tr>
            <td><?php echo $r["name"]; ?></td>
            <td><?php echo $r["so_access"]; ?></td>
            <td><?php echo $r["type_device_access"]; ?></td>
            <td><?php echo $r["country"]; ?></td>
            <td><?php echo $r["city"]; ?></td>
        </tr>

    <?php endforeach; ?>

</table>
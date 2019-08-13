

<table border="0" cellpadding="0" cellspacing="0">

    <tr>
        <th>Revista</th>
        <th>Código</th>
        <th>Utilizado</th>
        <th>Opções</th>
    </tr>

    <?php if( count($result) > 0 ): ?>

        <?php foreach( $result as $r ): ?>

            <tr>
                <td><?php echo $r["title"]; ?></td>
                <td><?php echo $r["password"]; ?></td>
                <td><?php echo $r["read_status"] == 0 ? "Não" : "Sim"; ?></td>
                <td>
                    <a class="edit-icon" href="<?php echo $this->config->base_url();?>index.php/edit_magazine_code?rowId=<?php echo $r['id']; ?>">Edit</a>
                </td>
            </tr>

        <?php endforeach; ?>

    <?php else: ?>

        <tr >
            <td colspan="4">Registros não encontrado.</td>
        </tr>

    <?php endif; ?>

    <input type="hidden" id="totalCodes" value="<?php echo $totalValues; ?>" />

</table>
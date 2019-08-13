<p>Aguarde, estamos redirecionando o acesso...</p>

<form name="form_new">
    <input type="hidden" id="new_page" name="new_page" value="0" />
</form>

<script>

    var new_page = document.getElementById('new_page');

    if (new_page.value == '1') {
        window.location = "<?php echo $this->config->base_url(); ?>index.php";
    }

    function mark_new_page() {
        new_page.value = '1';
    }

    mark_new_page();

</script>
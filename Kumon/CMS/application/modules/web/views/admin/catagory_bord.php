<script src="js/jquery.js"></script>

<script>
    $(document).ready(function () {
        $(".add-new").click(function () {
            $(".layer-bg").fadeIn();
        });

        $(".close-bttn").click(function () {
            $(".layer-bg").fadeOut();
        });
    });

    /* DELETE A ADMIN*/

    /*conform box*/

    function ConfirmDel() {
        var x = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_delete_this_record"); ?>")
        if (x) {
            return true;
        } else {
            return false;
        }
    }

    /*Delete a catagory*/
    function DELETE(rowId) {
        var disable = confirm('<?php echo $this->lang->line("Are_you_sure_want_to_delete_this_record"); ?>');
        if (disable === true) {
            var msg = '';
            $.ajax({
                type: "POST",
                data: {val: rowId},
                dataType: "html",
                url: "<?php echo $this->config->base_url(); ?>index.php/deletecatagory",
                success: function (data) { //alert(data);
                    location.reload();
                }
            });

        }
    }

    /* GET LANGUAGE ID AND FETCH LANGUAGE DATA*/

    function LanguageData(langId) { //alert('hello');
        window.location.href = "<?php echo base_url(); ?>index.php/catagory?lang=" + langId;
    }


</script>


</head>
<?php
$lang = $_GET['lang'];
?>

<div class="subheader">
    <div class="fix-container clearfix">
        <h4><?php echo $this->lang->line('Category_Management') ?></h4>
        <a class="add-new" href="#"><?php echo $this->lang->line('Add_New') ?></a>

    </div>
</div>

<div class="fix-container clearfix">

    <div class="category-list">

        <div class="table-container">

            <div style="color: green">
                <p><?php
                    echo $msg;
                    echo $this->session->flashdata("suc_msg");
                    ?>
                </p>
                <div style="color: red"><p><?php
                        echo $msg;
                        echo $this->session->flashdata("msg");
                        ?></p></div>
                <script class="jsbin" src="https://datatables.net/download/build/jquery.dataTables.nightly.js"></script>
                <script>
                    $(document).ready(function () {
                        $('#category').dataTable({"bPaginate": false});
                    });
                </script>
                <table id="category" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th><?php echo $this->lang->line('Categories_Name') ?></th>
                        <th><?php echo $this->lang->line('Categories_Name_Portuguese') ?></th>
                        <th><?php echo $this->lang->line('Categories_Name_Spanish') ?></th>
                        <th><?php echo $this->lang->line('No_of_Articles') ?></th>
                        <th><?php echo $this->lang->line('Action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($data_result != "") {
                        foreach ($data_result as $value) {
                            ?>
                            <tr>
                                <td><?php echo $value['category_name']; ?></td>
                                <td><?php echo $value['category_name_portuguese']; ?></td>
                                <td><?php echo $value['category_name_spanish']; ?></td>
                                <td><?php echo $value['article_count']; ?></td>
                                <td><a class="delete-bttn" href="#"
                                       onclick="DELETE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');"></a>
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

        <div class="pagination clearfix">
            <ul><?php echo $this->pagination->create_links(); ?></ul>
        </div>

    </div>

    <div class="layer-bg">
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
        <div class="popup-container clearfix">
            <div class="close-bttn"></div>
            <h4><?php echo $this->lang->line('Add_Category') ?></h4>
            <?php
            $attributes = array('name' => 'add_catagory', 'id' => 'add_catagory', 'class' => 'form-horizontal');
            echo form_open($this->config->base_url() . "index.php/addcatagory?lang=" . $lang, $attributes);
            ?>
            <div style="color: red"><p><?php echo validation_errors(); ?>
                <div id="textDiv"></div>
                </p>

                <div class="popup-inner clearfix">

                </div>
                <div class="popup-inner clearfix">
                    <input type="text" id="catagory_name_english" placeholder="enter category name(English)"
                           name="catagory_name_english" maxlength="100">
                    <input type="text" id="catagory_name_portuguese" placeholder="enter category name(Portuguese)"
                           name="catagory_name_portuguese" maxlength="100">
                    <input type="text" id="catagory_name_spanish" placeholder="enter category name(Spanish)"
                           name="catagory_name_spanish" maxlength="100">
                </div>
                <div class="popup-inner clearfix">
                    <input type="submit" value="Save" name='save' onclick="return catagoryvalidate(this);"/>
                </div>
            </div>
            <?php echo form_close(); ?>
            <script>

                function catagoryvalidate() {
                    if ((document.getElementById('catagory_name_english').value == '') || (document.getElementById('catagory_name_portuguese').value == '') || (document.getElementById('catagory_name_spanish').value == '')) {
                        //alert('Enter any values for search');
                        var div = document.getElementById("textDiv");
                        div.textContent = "Please enter category is all three languages";
                        var text = div.textContent;
                        return false;
                    }
                }

                // validation
                /*  jQuery.validator.setDefaults({
                 debug: false,
                 success: "valid"
                 });

                 $("#add_catagory").validate({
                 rules: {
                 catagory_name_english: {
                 required: true
                 },
                 catagory_name_portuguese: {
                 required: true
                 },
                 catagory_name_spanish: {
                 required: true
                 },
                 }

                 }); */
            </script>


        </div>
    </div>

</div>
</body>
</html>
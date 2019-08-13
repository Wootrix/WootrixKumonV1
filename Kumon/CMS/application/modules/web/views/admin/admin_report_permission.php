<script src="js/jquery.js"></script>

<script>

    $(document).ready(function () {

        $(".view-bttn").click(function () {
            $(".layer-bg").fadeIn();
        });

        $(".close-bttn").click(function () {
            $(".layer-bg").fadeOut();
        });

        getReportPermissionData();

    });

//    var getCustomers = function(){
//
//        $.ajax({
//            type: "POST",
//            url: "<?php //echo $this->config->base_url(); ?>//index.php/get_customer_filter",
//            success: function (data) {
//                $("#customer_filter").html(data);
//                getReportPermissionData();
//            }
//        });
//
//    }
//
//    getCustomers();

    var getReportPermissionData = function(){

        var idCustomer = $("#id_customer").val();

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/get_report_permission_data?id_customer=" + idCustomer,
            success: function (data) {
                $("#permission_data").html(data);
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
        <h4><?php echo $this->lang->line('report_permissions_title') ?></h4>
    </div>
</div>

<div class="fix-container clearfix">

    <div class="add-new-customer clearfix">

        <form action="<?php echo $this->config->base_url(); ?>index.php/editmetrics" method="post" class="form-horizontal">

            <input type="hidden" name="id_customer" id="id_customer" value="<?php echo $idCustomer; ?>" />

            <div class="login-credential clearfix">

                <div id="permission_data" class="table-container clearfix"></div>

                <div class="input-container clearfix">
                    <input type="button" value="cancelar" onclick="javascript: window.history.back();" />
                    <input type="submit" value="save" />
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
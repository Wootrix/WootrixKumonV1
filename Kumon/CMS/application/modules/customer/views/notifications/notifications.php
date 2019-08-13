<script src="<?php echo $this->config->base_url(); ?>/jss/multiselect/jquery-1.9.0.min.js"></script>
<!--<msdropdown> -->
<link rel="stylesheet" type="text/css"
      href="<?php echo $this->config->base_url(); ?>/jss/multiselect/multiple-select.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->base_url(); ?>/jss/multiselect/dd.css"/>
<script src="<?php echo $this->config->base_url(); ?>/jss/multiselect/jquery.dd.min.js"></script>
<script src="<?php echo $this->config->base_url(); ?>jss/multiselect/multiple-select.js"></script>

<div class="subheader">
    <div class="fix-container clearfix">
        <h4>Push Notifications</h4>
        <?php if ($this->session->flashdata('push_notification_succ') != "") { ?>
            <h2 class="success"><?php echo $this->session->flashdata('push_notification_succ'); ?></h2>
        <?php }
        if ($this->session->flashdata('push_notification_err') != '') { ?>
            <h2 class="error"><?php echo $this->session->flashdata('push_notification_err'); ?></h2>
        <?php } ?>
    </div>
</div>

<div class="fix-container clearfix">

    <div class="notification-container">
        <form name="push_notify" action="" autocomplete="off" method="post">
            <div class="row">
                <div style="width:100%">
                    <div class="footer-bottom-push-notify-left">
                        <input type="submit" value="SEND" name="push_submit" class="blue-btn">
                        <input type="reset" value="CANCEL" name="push_reset" class="blue-btn ">
                    </div>
                    <div class="footer-bottom-push-notify-right input_fields_wrap">
                        <input type="hidden" name="mulform" id="mulformval" value="0">
                        <input type="button" value="+ADD" id="showform" class="blue-btn add-btn add_field_button">
                    </div>
                </div>
            </div>

            <div id="push_form"></div>

            <input type="hidden" id="filterCount" value="0">

        </form>
    </div>

    <div class="category-list">
        <div class="table-container">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th>Date/Time</th>
                    <th>Message</th>
                    <th>Filter Options</th>
                </tr>
                <?php
                if (count($history) > 0) {
                    foreach ($history as $his) {
                        ?>
                        <tr>
                            <td><?php echo date("Y-m-d H:i a", strtotime($his['sent_at'])); ?></td>
                            <td><?php echo $his['message']; ?></td>
                            <td><?php echo $his['options']; ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td>No Records</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <div class="pagination clearfix">
        <ul><?php echo $this->pagination->create_links(); ?></ul>
    </div>

</div>

<script>

    $(function () {
        newFilter();
    });

    var newFilter = function () {

        var filterCount = parseInt($('#filterCount').val()) + 1;

        $.ajax({
            url: '<?php echo $this->config->base_url();?>index.php/customerNewFilter',
            type: 'POST',
            data: {
                filterCount: filterCount
            },
            success: function (data) {
                $('#push_form').append(data);
                $('#filterCount').val(filterCount);
            }
        });

    }

    //Ajax for Close magazine Message Type
    $(document).on("change", ".fld_cmtype", function (e) {
        var crntid = $(this).attr('id');
        var typeval = $('#' + crntid).val();
        var res = crntid.split("_");
        var formval = res[2];
        // alert(res);

        if (typeval != "") {
            $('#div_cmtyp_' + formval).show();
            $.ajax({
                url: '<?php echo $this->config->base_url();?>index.php/customer_notify_ajax',
                type: 'POST',
                data: 'type=cmtype&msg=' + typeval + '&form=' + formval,
                success: function (data) {
                    //called when successful
                    $('#div_cmtyp_' + formval).html(data);
                }
            });
        }
        else {
            $('#div_cmtyp_' + formval).hide();
        }
    });

    //Ajax for Close magazine Message Type Inner
    $(document).on("change", ".fld_cmtypein", function (e) {
        var crntid = $(this).attr('id');
        var val = $('#' + crntid).val();
        var res = crntid.split("_");
        var formval = res[2];
        var cmtyp = $('#fld_cmtype_' + formval).val();
        $('#div_cmtypin_' + formval).show();

        if (cmtyp != 'cm') {
            $.ajax({
                url: '<?php echo $this->config->base_url();?>index.php/customer_notify_ajax',
                type: 'POST',
                data: 'type=cmtypein&id=' + val + '&form=' + formval + '&cmtyp=' + cmtyp,
                success: function (data) {
                    //called when successful
                    $('#div_cmtypin_' + formval).html(data);
                }
            });
        }
    });

    function del_form(fid) {
        $("#form_count_" + fid).remove();
    }

    //Ajax for Multiple Forms
    $(document).on("click", ".add_field_button", function (e) {
        newFilter();
    });

    function toggleGroup(source, k) {
        checkboxes = document.getElementsByName('group[' + k + '][]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    function toggleLocation(source, k) {
        checkboxes = document.getElementsByName('location[' + k + '][]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    function toggleDiscipline(source, k) {
        checkboxes = document.getElementsByName('discipline[' + k + '][]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    function toggleBranch(source, k) {
        checkboxes = document.getElementsByName('branch[' + k + '][]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

</script>
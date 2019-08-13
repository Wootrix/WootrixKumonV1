<script src="js/jquery.js"></script>

<script>
    $(document).ready(function() {
        $(".view-bttn").click(function() {
            $(".layer-bg").fadeIn();
        });

        $(".close-bttn").click(function() {
            $(".layer-bg").fadeOut();
        });
    });




    /* GET ADMIN DETAILS AJAX*/
    function getAllSupportDetails(val1) {

        var tid1 = val1;
        //alert(tid1);
        $.ajax({
            type: "POST",
            data: {Id: tid1},
            url: "<?php echo $this->config->base_url(); ?>index.php/customerdetails",
            success: function(data)
            {
                //console.log(data);
                $(".popUpDiv").html(data);

            }

        });

    }

    /* DELETE A ADMIN*/

    function ConfirmDel() {
        var x = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_delete_this_record"); ?>")
        if (x) {
            return true;
        } else {
            return false;
        }
    }



    function DELETE(rowId,stat) {
        var stat = stat;
        if(stat == 1){
            var disable = confirm("<?php echo $this->lang->line("Are_you_sure_to_block_this_customer"); ?>");
        }else{
          var disable = confirm("<?php echo $this->lang->line("Are_you_sure_to_unblock_this_customer"); ?>");
        }


        if (disable === true)
        {
            var msg = '';
            $.ajax({
                type: "POST",
                data: {val: rowId},
                dataType: "html",
                url: "<?php echo $this->config->base_url(); ?>index.php/customerdelete",
                success: function(data)
                { //alert(data);
                    location.reload();
                }
            });

        }
    }
   /* GET LANGUAGE ID AND FETCH LANGUAGE DATA*/

    function StatusData(langId) { //alert('hello');
        window.location.href="<?php echo base_url();?>index.php/customers?perId="+langId;
    }


 /*SERCH USER NAME BY SERCH BAR*/

     $('#username').blur(function(){
                $.ajax({
                    type: "POST",
                    url: "search.php",
                    data: {text:$(this).val()}
                });
            });



</script>


</head>

<?php
/*SESSION VARIABLE*/
$selcted_permission = $_GET['perId'];
$searchString='';
$searchString=$this->session->userdata('searchUser');
?>
<div class="subheader">
    <div class="fix-container clearfix">
        <h4><?php echo $this->lang->line('Customer_Management') ?></h4>
        <a class="add-new" href="<?php echo $this->config->base_url(); ?>index.php/addcustomer">Add New</a>

    </div>
</div>

<div class="fix-container clearfix">
    <div class="upper_bar clearfix filter-bar">

        <div class="search-bar">
            <div class="search-box">
                <form id="search" action="<?php echo $this->config->base_url()?>index.php/customers"  method="POST">
                    <input type="text" id="username" name="username" size="10" placeholder="<?php echo $this->lang->line('Search_by_Name') ?>" value="<?php echo$searchString; ?>"/>
                    <input type="submit" id="submit-button" name="sa" value="search" />
                </form>
            </div>
        </div>

        <div class="search-bar right-align-box">
        <div class="language-filter clearfix">
            <div class="select-container">
                <div class="select-arrow">
                    <div class="arrow-down"></div>
                </div>

                <form id="languge_form" action="<?php echo $this->config->base_url()?>index.php/customers" method="POST">
                <select class="choose-lang" onChange="StatusData(this.value,'<?php echo $_GET['lang'] ?>');" name="lang" id="lang">
                    <option selected="selected"value="" name=""><?php echo $this->lang->line('Sort_by_Status') ?></option>
                    <option value="1" <?php if($selcted_permission==1){ ?> selected ='selected' <?php }?>>Blocked</option>
                    <option value="2" <?php if($selcted_permission==2){ ?> selected ='selected' <?php }?>>Active</option>

                </select>
                    </form>
            </div>
        </div>
        </div>

    </div>
    <div style="color: green;">
        <p><?php echo $msg;  echo $this->session->flashdata("msg");?></p>
    </div>
    <div class="category-list">
        <div class="table-container">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th><?php echo $this->lang->line('Image') ?></th>
                    <th><?php echo $this->lang->line('Customer_Name') ?></th>
                    <th><?php echo $this->lang->line('Customer_magazines') ?> </th>
                    <th>Métricas</th>
                    <th><?php echo $this->lang->line('Status') ?></th>
                    <th><?php echo $this->lang->line('Action') ?></th>
                </tr>
                <?php //echo"<pre>"; print_r($data_result); die; ?>
                <?php if($data_result !=""){ foreach ($data_result as $value) { ?>
                    <tr>
                        <td><div class="rounded-image"> <?php if($value['image'] !=""){ ?>
                          <img src =" <?php echo $this->config->base_url().'assets/customer_img/'.$value['image']; ?>" style="height:80px;width:80px">
                        <?php } else{ ?>
                          <img src =" <?php echo $this->config->base_url().'images/profile-pic.png'; ?>" style="height:80px;width:80px">
                        <?php }?></div> </td>

                        <td><?php echo$value['name']; ?></td>

                        <td><a class="magazine-list-bttn" href="<?php echo $this->config->base_url();?>index.php/customermagazine?rowId=<?php echo $value['id']; ?>">Magazine List</a></td>
                        <td><a class="magazine-list-bttn" href="<?php echo $this->config->base_url();?>index.php/report_permission?rowId=<?php echo $value['id']; ?>">Configurar Métricas</a></td>
                        <td> <?php if ($value['status'] == 1) { ?>
                            <div class="active-btn">Active</div>
                            <?php } else { ?>
                            <div class="blocked-btn">Blocked </div>
                            <?php } ?>
                        </td>
                        <td><a class="view-bttn" href="#" onclick="getAllSupportDetails('<?php echo $value['id']; ?>');">view</a>
                             <a class="edit-icon" href="<?php echo $this->config->base_url();?>index.php/editcustomer?rowId=<?php echo $value['id']; ?>">Edit</a>
                            <?php if ($value['status'] == 1) { ?>
                            <a class="block-icon" href="#" onclick="DELETE(<?php echo $value['id']; ?>, '<?php echo $value['status'] ?>');"></a>
                            <?php } else { ?>
                            <a class="unblock-icon" href="#" onclick="DELETE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');"></a>
                            <?php } ?>
                             </td>

                    </tr>
                <?php } }else{ ?>
                    <div style="color: red"><p><?php echo $this->lang->line('No_RECORD_FOUND'); ?> <p> </div>
                    <?php } ?>

            </table>

        </div>

    </div>


    <div class="pagination clearfix">

         <ul><?php echo $this->pagination->create_links(); ?></ul>

    </div>


</div>



<!--Pop up-->


<div class="layer-bg">
    <div class="popup-container info-popup clearfix">
        <div class="close-bttn"></div>
        <div class ="displayData popUpDiv">

        </div>
    </div>
</div>
</body>
</html>
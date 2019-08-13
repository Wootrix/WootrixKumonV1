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
        var x = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_update_status"); ?>")
        if (x) {
            return true;
        } else {
            return false;
        }
    }
    
   

    function DELETE(rowId) {

        var disable = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_update_status"); ?>");
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
  /*------------------Sorting and serach---------------*/  
    /*Sort by status*/
    function StatusData(val){
        var rowId = val;
         $('#languge_form').submit();
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

<?php $searchString='';
$searchString=$this->session->userdata('searchUser');
?>


<div class="subheader">
    <div class="fix-container clearfix">
        <h4><?php echo $this->lang->line("Deleted_Magazine_History"); ?></h4>     

    </div>
</div>

<div class="fix-container clearfix">
    <div class="upper_bar clearfix filter-bar">
        
        <div class="search-bar">
            <div class="search-box">
                <form id="search" action="<?php echo $this->config->base_url()?>index.php/history"  method="POST">
                    <input type="text" id="username" name="title" size="10" placeholder="<?php echo $this->lang->line('Search_Magazine') ?>" value="<?php echo$searchString; ?>"/>
                    <input type="submit" id="submit-button" name="sa" value="search" />
                </form>
            </div>
        </div>
        
    </div>   

    <div class="category-list">
        <div class="table-container">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>                    
                    <th><?php echo $this->lang->line("Magazine_Name"); ?></th>
                    <th><?php echo $this->lang->line("Customer_Name"); ?></th>
                    <th><?php echo $this->lang->line("Language"); ?></th>
                    <th><?php echo $this->lang->line("Date"); ?></th>
                    <th class="action-colm"><?php echo $this->lang->line("Action"); ?></th>
                </tr>
                <?php //echo"<pre>"; print_r($data_result); die; ?>
                <?php if($data_result !=""){ foreach ($data_result as $value) { ?>
                    <tr>
                        <td><div class="rounded-image"> <img src =" <?php echo $this->config->base_url() . 'assets/Magazine_cover/' . $value['cover_image']; ?>" style="height:80px;width:80px"></div> 
                        <?php echo$value['title']; ?>
                        </td>
                        
                        <td><?php echo$value['name']; ?></td>
                        <td><?php echo$value['language']; ?></td>
                        <td> <?php echo date("Y-M-d", strtotime($value['publish_date_from'])) . ' to ' . date("Y-M-d", strtotime($value['publish_date_to'])); ?></td>
                        
                      <td><a class="view-btn" href="<?php echo $this->config->base_url();?>index.php/magizneartcle?rowId=<?php echo $value['id']; ?>"><?php echo $this->lang->line('View') ?></a></td>
                            
                    </tr>                                        
                <?php }}else{ ?>
                    <div style="color: red"><p> <?php echo $this->lang->line("No_RECORD_FOUND"); ?> </p> </div>
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
    <div class="popup-container clearfix">
        <div class="close-bttn"></div>
        <div class ="displayData popUpDiv"> 

        </div>
    </div>
</div>
</body>
</html>
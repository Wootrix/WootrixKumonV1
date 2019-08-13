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
            url: "<?php echo $this->config->base_url(); ?>index.php/admindetails",
            success: function(data)
            {
                //console.log(data);
                $(".popUpDiv").html(data);

            }

        });

    }

   

    function ConfirmDel() {
        var x = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_delete_this_record"); ?>")
        if (x) {
            return true;
        } else {
            return false;
        }
    }
     /* DELETE A ADMIN*/

    function DELETE(rowId) {

        var disable = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_delete_this_record"); ?>");
        if (disable === true)
        {   
            var msg = '';
            $.ajax({
                type: "POST",
                data: {val: rowId},
                dataType: "html",
                url: "<?php echo $this->config->base_url(); ?>index.php/admindelete",
                success: function(data)
                { //alert(data);
                    location.reload();
                }
            });

        }
    }
    
    /* GET LANGUAGE ID AND FETCH LANGUAGE DATA*/

    function LanguageData(langId) { //alert('hello');
        window.location.href="<?php echo base_url();?>index.php/admin?perId="+langId;
    }
   
    /*SERCH USER NAME BY SERCH BAR*/
    
     $('#username').blur(function(){
                $.ajax({
                    type: "POST",
                    url: "search.php",
                    data: {'text':$(this).val()}
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
        <h4><?php echo $this->lang->line('Admin_Management') ?></h4>
        <a class="add-new" href="<?php echo $this->config->base_url(); ?>index.php/addsubadmin">Add New</a>

    </div>
</div>

<div class="fix-container clearfix">
    <div class="upper_bar clearfix filter-bar">       
 
        <div class="search-bar">
            <div class="search-box">
                <form id="search" action="<?php echo $this->config->base_url()?>index.php/admin"  method="POST">
                    <input type="text" id="username" name="username" size="10" placeholder="<?php echo $this->lang->line('Search_by_Name') ?>" value="<?php echo $searchString; ?>"/>
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
                <form id="languge_form" action="<?php echo $this->config->base_url()?>index.php/admin" method="POST">
                 <select class="choose-lang" onChange="LanguageData(this.value,'<?php echo $_GET['lang'] ?>');" name="lang" id="lang">
                            <option value="" name=""><?php echo $this->lang->line('Sort_by_Permission') ?></option>
                             <?php
                            foreach ($permission as $state) { ?>
                        <option value="<?php echo $state['id'];?>" <?php if($state['id']==$selcted_permission){?> selected="selected" <?php }?>><?php echo $state['permission_type'];?></option>
                       
                   <?php }
                    ?>
                 </select>
                </form>
            </div>
        </div>
        </div>
        
    </div>
 <div style="color: green;"><p><?php echo $msg;  echo $this->session->flashdata("msg");?>   </p>
    <div class="category-list">
        <div class="table-container">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th><?php echo $this->lang->line('Image') ?></th>
                    <th><?php echo $this->lang->line('Admin_Name') ?></th>
                    <th class="big-content"><?php echo $this->lang->line('Permission') ?></th>
                    <th class="action-colm"><?php echo $this->lang->line('Action') ?></th>
                </tr>
                 
                <?php if($data_result !=""){ foreach ($data_result as $value) { ?>
                  <tr> 
                      <td><div class="rounded-image"> <?php if($value['image'] !=""){ ?>
                          <img src =" <?php echo $this->config->base_url().'assets/customer_img/'.$value['image']; ?>" style="height:80px;width:80px">  
                        <?php } else{ ?> 
                          <img src =" <?php echo $this->config->base_url().'images/profile-pic.png'; ?>" style="height:80px;width:80px"> 
                        <?php }?></div> </td>
                        
                        <td><?php echo$value['name']; ?></td>
                        <td><?php if ($value['role'] == 1) { ?>
                            <div class="super-admin-btn"> Super Admin</div>
                            <div class="clear"></div>
                            <?php } else { ?>
                            <div class="custom-btn">Custom </div>
                            <div class="clear"></div>
                                <?php $permission = "";
                                if($value['permission1'] == 1){
                                    $permission = 'Categories,';
                                }if($value['permission2'] == 1){
                                    $permission .= 'Manage Customers,';
                                    
                                }if($value['permission3'] == 1){
                                    $permission .= 'Advertisement,';
                                }if($value['permission4'] == 1){
                                    $permission .= 'Magazine,';
                                }if($value['permission5'] == 1){
                                    $permission .= 'Open Article,';
                                }if($value['permission6'] == 1){
                                    $permission .= 'Change Language,';
                                }if($value['permission7'] == 1){
                                    $permission .= 'Admins,';
                                }if($value['permission8'] == 1){
                                    $permission .= 'History,';
                                }                              
                                
                                echo rtrim($permission,',');
                                //echo $permission ; 
                                ?> 
                            

                                        <?php } ?>
                        </td>
                        <td><a class="view-bttn" href="#" onclick="getAllSupportDetails('<?php echo $value['id']; ?>');">view</a>
                            <a class="edit-icon" href="<?php echo $this->config->base_url();?>index.php/editsubadmin?rowId=<?php echo $value['id']; ?>">Edit</a>
                            <a class="delete-bttn" href="#" onclick="DELETE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');"></a></td>
                    
                                                            
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
    <div class="popup-container clearfix info-popup">
        <div class="close-bttn"></div>
        <div class ="displayData popUpDiv"> 

        </div>
    </div>
</div>
</body>
</html>
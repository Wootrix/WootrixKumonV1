<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <!--<script src="js/jquery.js"></script>-->
<link rel="stylesheet" href="<?php echo base_url()?>css/jquery-ui.css" />
<script src="<?php echo base_url()?>js/jquery-1.9.1.js"></script>
<script src="<?php echo base_url()?>js/jquery-ui.js"></script>
<script src="<?php echo base_url()?>js/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $this->config->base_url(); ?>jss/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo $this->config->base_url(); ?>jss/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->base_url(); ?>jss/evol.colorpicker.min.js"></script> 



        <!-- Define some CSS -->       
        <script>
//            $(document).ready(function() {
//                $(".add-new").click(function() {
//                    $(".layer-bg").fadeIn();
//                });
//
//                $(".close-bttn").click(function() {
//                    $(".layer-bg").fadeOut();
//                });
//            });

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
            function DELETE(rowId)
            {
                var disable = confirm("<?php echo $this->lang->line("Are_you_sure_want_to_delete_this_record"); ?>");
                if (disable === true)
                {
                    var msg = '';
                    $.ajax({
                        type: "POST",
                        data: {val: rowId},
                        dataType: "html",
                        url: "<?php echo $this->config->base_url(); ?>index.php/deletemagazine",
                        success: function(data)
                        { //alert(data);
                            location.reload();
                        }
                    });

                }
            }

        </script>
    </head>
<?php $searchString='';
$searchString=$this->session->userdata('searchUser');
?>

<!--    <div class="subheader">
        <div class="fix-container clearfix openArticlesTab">
            <div class="publish-magzine">
                <input type="button" value="Manually" id="manually">    
            </div>
            
            <div class="publish-magzine">
            <input type="button" value="Via Url" id="viaurl">
            </div>
        </div>
    </div>-->
    
    <div class="subheader">
    <div class="fix-container clearfix openArticlesTab">
        <h4><?php echo $this->lang->line('All_Magazine') ?></h4>
<!--        <a class="add-new" href="<?php echo $this->config->base_url(); ?>index.php/addarticle">Add New</a>-->
        <a class="add-new" href="<?php echo base_url()?>index.php/addCustomerArticle">Manually</a>
        <a class="add-new" href="<?php echo base_url()?>index.php/addCustomerArticle?request=1">Via URL</a>

    </div>
</div>
    
    

<!--    <div class="subheader">
        <div class="fix-container clearfix">
            <h4><?php echo $this->lang->line('All_Magazine') ?></h4>
        </div>
    </div>
    -->
    
    <div class="fix-container clearfix">
        <div style="color: red"><p><?php echo $msg;
            echo $this->session->flashdata("msg");
            ?>   </p>
                <div style="color: green" ><?php echo $this->session->flashdata("susmsg"); ?>   </div>
<?php echo validation_errors(); ?></div>
        <div class="search-bar">
            <div class="search-box">
                <form id="search" action="<?php echo $this->config->base_url()?>index.php/magazinelist"  method="POST">
                    <input type="text" id="username" name="title" size="10" placeholder="<?php echo $this->lang->line('Search_Magazine') ?>" value="<?php echo$searchString; ?>"/>
                  <!--<input type="submit" id="submit-button" name="sa" value="search" />-->
                </form>
            </div>
        </div>
        
        <div class="category-list">
            <div class="magzine-container">
                <h2><?php echo $this->lang->line('Magazines') ?></h2>
                <div style="color: red"><p><?php
                        echo $msg;
                        echo $this->session->flashdata("msg");
                        ?>   </p>
                    <?php //echo validation_errors();  ?></div>
                <ul>    <?php $todays_date = date("Y-m-d") ?>                
                    <?php //echo"<pre>"; print_r($data_result); die;
                    if ($data_result != "") {
                        foreach ($data_result as $value) {
                            ?>
                            <li>
                                <div class="magzine"> <a href="<?php echo $this->config->base_url(); ?>index.php/customer_articlelist?rowId=<?php echo $value['id']; ?>">
                                        <img src =" <?php echo $this->config->base_url() . 'assets/Magazine_cover/' . $value['cover_image']; ?>" > </a></div>

                                <h4><?php echo $value['title']; ?>(<?php echo $value['article_count']." "; ?>Articles)</h4>
                                <h5><?php echo date("Y-M-d", strtotime($value['publish_date_from'])) . ' to ' . date("Y-M-d", strtotime($value['publish_date_to'])); ?></h5>
                                <div class="publish-magzine"><?php if($todays_date < $value['publish_date_from']){
                                     echo $this->lang->line("Scheduled"); 
                                    }else{
                                     echo $this->lang->line("Published");
                                    }
                                    
                                    ?> 
                                <!--a class="delete-bttn" href="#" onclick="DELETE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');"></a-->
                                </div>
                                <?php if($value['new_article_count'] > 0 ) { ?>
                                <span><?php echo $value['new_article_count']; ?></span>
                                <?php }?>
                            </li>                                 
                        <?php }
                            } else{?>
                            <div style="color: red"><p><?php echo $this->lang->line('No_RECORD_FOUND'); ?> <p> </div>
                            <?php } ?>
                </ul>

            </div>

        </div>


        <div class="pagination clearfix">
            <ul><?php echo $this->pagination->create_links(); ?></ul> 
        </div>


    </div>    


        

    </div>
</body>
</html>
<script>
$('#manually').click(function(){
   window.location.href="<?php echo base_url()?>index.php/addCustomerArticle"; 
});
$('#viaurl').click(function(){
   window.location.href="<?php echo base_url()?>index.php/addCustomerArticle?request=1"; 
});
</script>
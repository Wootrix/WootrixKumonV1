<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <!--<script src="js/jquery.js"></script>-->
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>

        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
        <!--script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js" type="text/javascript"></script-->
        <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>jss/jquery.min.js"></script> 
        <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>jss/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>jss/evol.colorpicker.min.js"></script> 



        <!-- Define some CSS -->       
        <script>
            $(document).ready(function() {
                $(".add-new").click(function() {
                    $(".layer-bg").fadeIn();
                });

                $(".close-bttn").click(function() {
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


    <div class="subheader">
        <div class="fix-container clearfix">
            <h4></h4>
        </div>
    </div>

    <div class="fix-container clearfix">
        <div class="upper_bar clearfix">
            <div class="language-filter clearfix">
                <div class="select-container">
                    <div class="select-arrow">
                        <div class="arrow-down"></div>
                    </div>
                </div>
            </div>
        </div>



        <div class="category-list">
            <div class="magzine-container">
                <h2><?php echo $this->lang->line('Magazines') ?></h2>
                <div style="color: red"><p><?php
                        echo $msg;
                        echo $this->session->flashdata("msg");
                        ?>   </p>                   
                <ul>                    
                    <?php //echo"<pre>"; print_r($data_result); 
                    if ($data_result != "") {
                        foreach ($data_result as $value) {
                            ?>
                            <li>
                                <div class="magzine"> <a href="<?php echo $this->config->base_url(); ?>index.php/magazinearticlelist?rowId=<?php echo $value['id']; ?>"><img src =" <?php echo $this->config->base_url() . 'assets/Magazine_cover/' . $value['cover_image']; ?>" > </a></div>

                                <h4><?php echo$value['title']; ?></h4>
                                <h5><?php echo date("Y-M-d", strtotime($value['publish_date_from'])) . ' to ' . date("Y-M-d", strtotime($value['publish_date_to'])); ?></h5>
                                <div class="publish-magzine publish-status">Publish</div>
                            <a class="delete-bttn align" href="#" onclick="DELETE(<?php echo $value['id']; ?>, '<?php echo "delete"; ?>');"></a>
                                
                                <?php if($value['new_article_count'] > 0){ ?>
                                <span><?php  echo $value['new_article_count'];  ?></span>
                                <?php } ?>
                            </li>                                 
                        <?php }
                            } ?>
                   
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
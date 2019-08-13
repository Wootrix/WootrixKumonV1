<script src="js/jquery.js"></script>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<!-- Load jQuery and the validate plugin -->  
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

</head>

<div class="subheader">
    <div class="fix-container clearfix">
        <h4><?php echo $this->lang->line('Magazine').":".$magazine_title['title']; ?></h4>
        <h4><?php echo $this->lang->line('Magazine_Code'); ?></h4>        
    </div>
</div>

<nav class="clearfix">
    <div class="fix-container clearfix"><?php //echo"<pre>"; print_r($_REQUEST); ?>      
    </div>
</nav>


<div class="fix-container clearfix">
    <div class="upper_bar clearfix filter-bar"> 
    </div>

    <div class="category-list">
        <div class="table-container">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <!--th><input type="checkbox" /></th-->
                    <th><?php echo $this->lang->line('S_NO') ?></th>
                    <th><?php echo $this->lang->line('CODE') ?></th>
                    <th><?php echo $this->lang->line('Status') ?></th>
                </tr>
                <div style="color: red"><p><?php echo $msg;  echo $this->session->flashdata("msg");?>   </p>
                    <?php echo validation_errors(); ?></div>
                <?php //echo"<pre>"; print_r($data_result); die; ?>
                <tr> <?php
                    if ($data_result != "") { $i= 1;
                        foreach ($data_result as $value) { ?> 
                            <td><?php echo $i; ?></td>                           
                            <td><?php echo$value['password']; ?></td>
                         <td> <?php if($value['read_status'] == '0'){ echo"unread";} else{
                             echo"read";
                         } ?>
                             
                         </td>
                         </tr>
                        
                        <?php $i++; } } ?>
                        
            </table>

        </div>

    </div>


    <div class="pagination clearfix">
        <ul><?php echo $this->pagination->create_links(); ?></ul> 
    </div>



</div>    


<!--Pop up-->


<div class="layer-bg">
    <div class="layer-bg-popupOverlay"></div>
    <div class="popup-container clearfix">
        <div class="close-bttn"></div>       
        
        <div class="popup-inner clearfix">
            <div class="select-container">
                <div class="select-arrow">
                    <div class="arrow-down"></div>
                </div>             
            </div> 
        </div>
      

            
    </div>        
</div>
</body>
</html>
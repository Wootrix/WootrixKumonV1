<?php
include('header.php');
if(!$this->session->userdata('email_id'))
{
    //echo $isLoggedIn;
    redirect('admin');
}

include('sidebar.php');

function getValue($name, $alt = '') {
    
  return isset($_POST[$name]) ? $_POST[$name] : $alt;
}
?>
<head>
    <!--<script type="text/javascript" src="<?php echo $this->config->base_url();?>jss/change_password.js"></script>-->   
</head>

    
            <div class="row-fluid well" style="width: 96%;">
			<div class="page-header">
				<h1>Change Password</h1>
			</div>
                <?php if ($msg != "") { ?>

                    <div class="alert alert-info" role="alert">
                        <?php echo $msg; ?>


                    </div>

                <?php
                }
                if (isset($msg_error)) {
                    ?>
                    <div class="alert alert-danger" role="alert"> <?php echo $msg_error; ?></div>
                    <?php
                }
                ?>
              <!--<div class="alert alert-danger" id="errorBox" role="alert"></div>-->
            <div>
                
                <form id="change_password" method="post" onsubmit="return validate();" action ="<?php  echo $this->config->base_url('index.php/password_update')?>">
                    <table class="table table-bordered">
                    <tr>
                        <td>Current Password</td>
                        <td>
                            <?php if(isset($msg_error)){?>
                            <input type="password"  name="current" id="current" value="<?php echo htmlspecialchars(getValue('current'))?>"/>
                           <?php } else {?>
                            <input type="password"  name="current" id="current" />
                           <?php }?>
                        </td>
                    </div>
                    <tr>
                        <td>New Password</td>
                        <td>
                            <?php if(isset($msg_error)){?>
                            <input type="password"  name="new" onblur="passwordLength(this.value)"   min="6" id="new" value="<?php echo htmlspecialchars(getValue('new'))?>" />
                            <?php } else {?>
                            <input type="password"  name="new" onblur="passwordLength(this.value)"   min="6" id="new" />
                            <?php }?>
                        </td>
                    </tr>
                    <tr>
                        <td>Confirm Password</td>
                        <td>
                            <?php if(isset($msg_error)){?>
                            <input type="password"  name="confirm" id="confirm" value="<?php echo htmlspecialchars(getValue('confirm'))?>" />
                            <?php } else {?>
                            <input type="password"  name="confirm" id="confirm"  />
                            <?php }?>
                        </td>
                    </tr>
                    
                    </table>
                    <div>
                        <input type="submit" value="Update Password">
                   <a href ="<?php echo $this->config->base_url('index.php/user_table') ?>"><INPUT Type="button" VALUE="Cancel"></a>
                    </div>
                </form>
            </div>
        </div>
 

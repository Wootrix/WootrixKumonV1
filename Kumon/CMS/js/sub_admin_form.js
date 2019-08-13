
//*********************validation check for null fields***********//
function validate_form()
{
    if(document.forms['add_admin']['fname'].value == "")
    {
        alert("Please enter sub-admin first name");
        document.forms['add_admin']['fname'].focus();
        return false;
    }
    
    if(document.forms['add_admin']['email'].value == "")
    {
        alert("Please enter sub-admin email");
        document.forms['add_admin']['email'].focus();
        return false;
    }
    
    if(document.forms['add_admin']['password'].value == "")
    {
        alert("Please enter sub-admin password");
        document.forms['add_admin']['password'].focus();
        return false;
    }
    
    if(document.forms['add_admin']['confirm_password'].value == "")
    {
        alert("Please enter sub-admin confirm password");
        document.forms['add_admin']['confirm_password'].focus();
        return false;
    }
}

//***********validation for password limit************//
function password_validation()
{
    password = document.getElementById('pass'); 
    if (password.value.length < 6)
    { 
        alert("Password must be at least 6 characters");
        password.value = "";
        password.focus();
        return false;
    }
    return true;
}  

//**********validation for password matching****************//
function match_password()
{
    var pass = document.add_admin.password.value;
    var repass = document.add_admin.confirm_password;
    if (pass != repass.value)
    {
        alert("Password does not match");
        repass.value = "";
        repass.focus();
        return false;
    }
}

//**************validation for valid email checking************//
function valid_email(email)
{ //alert('hii');
    email_id = document.add_admin.email
    var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
    if (pattern.test(email))
    {
        return true;
    }
    else
    {
        alert("You have entered invalid email-id");
        email_id.value ="";
        email_id.focus();
        return false;
        
    }
}
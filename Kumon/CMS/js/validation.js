
//**************validation check for null value****************//
function validate_form( )
{
    
    if (document.forms['register']['date_day'].value == "day" || document.forms['register']['day_mon'].value == "month" || document.forms['register']['Date_Year'].value == "year")
    {

        alert("Please enter your date of birth ");
        return false;
    }


    if (document.forms['register']['Country_Id'].value == "country")
    {
        alert("Please enter your country ");
        return false;
    }

    if (document.forms['register']['state_id'].value == "state")
    {
        alert("Please enter your state ");
        return false;
    }

    if (document.forms['register']['city_id'].value == "city")
    {
        alert("Please enter your city ");
        return false;
    }


    


}

//****************function for username  check***************//

function allLetter(uname)
{
    var uname = document.register.fullname;
    var letters = /^[A-Za-z]+$/;
    if (uname.value.match(letters))
    {
        return true;
    }
    else
    {
        alert('Name must have alphabet characters only');
        uname.value="";
        uname.focus();
        return false;
    }
}

//***************function for checking password limit*************//

function passid_validation(passid, mx, my)
{
    var mx = 7;
    var my = 15;
    var passid = document.register.password;
    if (passid.value == "")
    {
        alert("Please enter your password");
    }
    var passid_len = passid.value.length;
    if (passid_len == 0 || passid_len >= my || passid_len < mx)
    {
        alert("Password should not be empty / length be between " + mx + " to " + my);
        passid.focus();
        return false;
    }
    return true;
}  

//***********function for matching password*****************//

function confirm_password(repass)
{
    var pass = document.register.password.value;
    var repass = document.register.repassword;
    if (pass != repass.value)
    {
        alert("Password does not match");
        repass.value = "";
        
        return false;
    }
}

//******************function for numeric check for zip code********//

function allnumeric(uzip)
{
    //alert("hii");
    var uzip = document.register.zip;
    //var length=uzip.length;
    //var numbers = /^[0-9]+$/;
    if (isNaN(uzip.value) || uzip.value.length !== 6)
    {
        alert("Please enter 6 digit number only");
        uzip.value = "";
        return false;
    }
    else
    {
        return true;
    }

}

//*************function for mobile no check********// 

function allnumeric_mob(mobileno)
{
    //alert("hii");
    var mob = document.register.mobile;
    //var length=uzip.length;
    //var numbers = /^[0-9]+$/;
    if (isNaN(mob.value) || mob.value.length !== 10)
    {
        alert("Please enter 10 digit number only");
        mob.value = "";
        return false;
    }
    else
    {
        return true;
    }
}  

//**************function for email validation***********************//
function valid_email(email)
{ //alert('hii');
    var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
    if (pattern.test(email))
    {
        return true;
    }
    else
    {
        alert("You have entered invalid email-id");
        email ="";
        return false;
        
    }
}




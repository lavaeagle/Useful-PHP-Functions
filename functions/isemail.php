/*  Checks if email domain is a mail server
--------------------------------------*/
function isEmail($email) {
    if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",$email))
    {
        list($username,$domain)=explode('@',$email);
        if(!checkdnsrr($domain,'MX')) {
            return false;
        }
        return true;
    }
    return false;
}
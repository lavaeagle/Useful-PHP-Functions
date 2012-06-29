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
/*  Is the file name an image
 --------------------------------------*/
function isImage($image) {
	$image = strtolower($image);

	$extension = pathinfo($image, PATHINFO_EXTENSION);
	$allowed = array('jpg', 'png', 'gif', 'jpeg');
	if(!in_array($extension, $allowed))
		return false;
	else
		return true;
}
/*  Uses PHP's filter_var to check for valid URL
 --------------------------------------*/
function isUrl($url) {
	$validation = filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);

	if ($validation)
		$output = TRUE;
	else
		$output = FALSE;

	return $output;
}

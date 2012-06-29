/*	String Cleaning
--------------------------------------
$userInput = "'; dangerous' I'm <script>dangerous(document.cookie);</script>,";
$userInput = cleanInput($userInput);
*/
function cleanInput($input) {
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = cleanScript($val);
        }
    }
    else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input  = cleanScript($input);
        $output = mysql_real_escape_string($input);
    }
    return $output;
}

function cleanScript($input) {
    // Remove <script> and html comments
    $search = array(
        '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
    );
    
    // Remove JS events
    $output = preg_replace($search, '', $input);
    $output = str_replace(array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavaible', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragdrop', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterupdate', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmoveout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'), "", $output);
    return $output;
}
/*	Encryption
--------------------------------------
Security Level: AES 256

Encrypt the string
$encryptedString = encrypt("RapidLeagle");

Decrypt the string
$decryptedString = decrypt($string);
*/

// The key to encrypting and decrypting your string
define('SALT', 'SuPeRsEcReT');
function encrypt($text) 
{ 
    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
} 
function decrypt($text) 
{ 
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 
}
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

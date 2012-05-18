<?php
/*
*
*	Ultimate PHP Library
*
*/


/*
*
*   Boolean checks
*
*/

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

/*  Get server max file upload size
--------------------------------------*/
function getMaxUploadSize() {
    $max_upload = (int)(ini_get('upload_max_filesize'));
    $max_post = (int)(ini_get('post_max_size'));
    $memory_limit = (int)(ini_get('memory_limit'));
    $upload_mb = min($max_upload, $max_post, $memory_limit);
    return $upload_mb;
}

/*  Get file extension of string
--------------------------------------*/
function getExtension($file) {
    return pathinfo($file, PATHINFO_EXTENSION);
}

/*  Wraps links in <a>url</al> and opens in new tab/window
--------------------------------------*/
function makeLinks($text) {
    // The Regular Expression filter
    $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

    // Check if there is a url in the text
    if(preg_match($reg_exUrl, $text, $url)):
        return preg_replace($reg_exUrl, "<a target='_blank' href=\"{$url[0]}\">{$url[0]}</a> ", $text);
    else:
        return $text;
    endif;
}

/*  Jason commented 5 minutes ago...
*   Converts standard dates and UNIX timestamps
--------------------------------------*/
function getAgoTime($date)
{
    // $date = "2011-12-17 17:45"
    // year-month-day hour:minute

    if(empty($date))
        return "No date provided";
    
    if(strlen($date) == 10)
        $date = date("Y-m-j H:i", $date);

        $date = unixToSql($date);
    
    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");
 
    $now             = time();
    $unix_date      = strtotime($date);
 
    // check validity of date
    if(empty($unix_date)) {    
        return "Bad date";
    }
 
    // is it future date or past date
    if($now > $unix_date) {    
        $difference     = $now - $unix_date;
        $tense         = "ago";
 
    } else {
        $difference     = $unix_date - $now;
        $tense         = "from now";
    }
 
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
 
    $difference = round($difference);
 
    if($difference != 1) {
        $periods[$j].= "s";
    }

    if($periods[$j] != 'seconds' && $periods[$j] != 'second')
        return "$difference $periods[$j] {$tense}";
    else
        return "just now";
}

/*  Make strings short... (By words or characters)
--------------------------------------*/
function truncateString($phrase, $max_words=10) {
    $phrase_array = explode(' ',$phrase);
    if(count($phrase_array) > $max_words && $max_words > 0)
        $phrase = implode(' ',array_slice($phrase_array, 0, $max_words)).'...';
    return $phrase;
}

function truncateCharacters($string, $maxChars=16) {
    if(strlen($string) > $maxChars)
        $string = substr($string, 0, $maxChars) . '...';

    return $string;
}

/*  Create a unique id
--------------------------------------*/
function uniqueId($length=30) {
    $salt       = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789".time();
    $len        = strlen($salt);
    $makepass   = '';
    mt_srand(10000000*(double)microtime());
    for ($i = 0; $i < $length; $i++) {
        $makepass .= $salt[mt_rand(0,$len - 1)];
    }
    return $makepass;
}

?>
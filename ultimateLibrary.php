<?php
/*
*
*	Ultimate PHP Library
*
*/

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
$userInput = "'; dangerous' I'm ,";
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
	$search = array(
		'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
		'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
		'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
		'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
	);

	$output = preg_replace($search, '', $input);
	
	return $output;
}


?>
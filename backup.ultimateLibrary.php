<?php
/*
*
*	Ultimate PHP Library
*
*/

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
        $string = substr($string, 0, $maxChars);

    return $string;
}

/*  Create a unique id
--------------------*/
function uniqueId($length = 30){
    $rounds = ceil($length / 13);
    $string = '';
    
    for($i = 0; $i < $rounds; $i++)
        $string .= uniqid();
    
    return substr($string, 0, $length);
}

/*  Check if haystack begins with needle
--------------------------------------*/
function beginsWith($needle, $haystack){
    return (substr($haystack, 0, strlen($needle)) == $needle);
}

/*  Return what comes after needle in haystack
--------------------------------------------*/
function after($needle, $haystack){
    $pos = strpos($haystack, $needle);
    if($pos !== false){
        return substr($haystack, $pos);
    }else {
        return '';
    }
}

/*  trim, ltrim, rtrim in one function
------------------------------------*/
function trimString($string, $left = '', $right = ''){
	return rtrim(ltrim(trim($string), $left), $right);
}

?>
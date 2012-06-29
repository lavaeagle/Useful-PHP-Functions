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
<?php
/*********
 * 
 */

/* 
 Creates a source string to generate registration code. A source string 
 contains product code name and user's registration name.
*/
require 'encoder.php';
require 'decoder.php';
	
function  make_license_source($product_code, $name)
{
  return ($product_code ."," .$name);
}


// This method is called  to generate a registration code. It
// receives a product code string, a registration name and quantity. I'm not
// using quantity here, but you're free to do it.
function make_license($product_code, $name, $copies)
{
	$priv_key_file_name = ("file://./lib/dsapriv512.pem");
	$priv = openssl_pkey_get_private($priv_key_file_name);
    
	$signedData = '';
	$compositeLicenseCode = make_license_source($product_code,$name);
	openssl_sign($compositeLicenseCode, $signature, $priv, OPENSSL_ALGO_DSS1);
	openssl_free_key($priv);
	$len = strlen($signature);

	$b32 = encode($signature);
  	// # Replace Os with 8s and Is with 9s
  	// # See http://members.shaw.ca/akochoi-old/blog/2004/11-07/index.html
	$b32 =  str_replace('O', '8', $b32);
	$b32 =  str_replace('I', '9', $b32);
	$b32 = join("-",str_split($b32,5));
	
	return $b32;
}


function verify_license($product_code, $name, $copies, $license)
{
	$signature=Array();

	$license =  str_replace('9', 'I', $license);
	$license =  str_replace('8', 'O', $license);
	$license = str_replace('-', '', $license);
	
	$compositeLicenseCode = make_license_source($product_code,$name);

	// pad out the license key
	$padded_length = strlen($license)%8;
	if ($padded_length == 0) {
		$padded_length = strlen($license);
	} else {
		$padded_length = (intval(strlen($license)/8)+1)*8;
	}
	$padded = $license . str_repeat('=',$padded_length-strlen($license));

	$signature = base32_decode($padded,base32_decode_buffer_size(strlen($padded)));
	$key = file_get_contents("./lib/dsapub512.pem");

	$pukeyid = openssl_get_publickey($key);
	$valid = openssl_verify($compositeLicenseCode, $signature, $pukeyid, OPENSSL_ALGO_DSS1);

	return ($valid == 1);
}

$test=true;
if ($test) {
	$lic = make_license('product', 'User Name', 10);
	echo "$lic\n";
	if (verify_license('product', 'User Name', 10, $lic)) {
		echo "License IS Valid\n";
	} else {
		echo "License IS NOT valid\n";
	}
}
?>


<?php 


function decode_bits ($bits)
{
  $table = array(
	0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF,
    0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF,
    0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF,
    0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0x1A, 0x1B, 0x1C, 0x1D, 0x1E, 0x1F,
    0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0x00, 0x01, 0x02, 0x03, 0x04,
    0x05, 0x06, 0x07, 0x08, 0x09, 0x0A, 0x0B, 0x0C, 0x0D, 0x0E, 0x0F, 0x10, 0x11, 0x12,
    0x13, 0x14, 0x15, 0x16, 0x17, 0x18, 0x19, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0x00,
    0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0A, 0x0B, 0x0C, 0x0D, 0x0E,
    0x0F, 0x10, 0x11, 0x12, 0x13, 0x14, 0x15, 0x16, 0x17, 0x18, 0x19, 0xFF, 0xFF, 0xFF,
    0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF,
    0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF,
    0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF,
    0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF,
    0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF,
    0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF,
    0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF,
    0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF,
    0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF,
    0xFF, 0xFF, 0xFF, 0xFF
  );
  return $table[ord($bits)];
}

function debug($array) 
{
	echo "$array \n";
	$length = strlen($array);
	echo "$length\n";
	for ($i = 0 ; $i < $length;$i++) {
		$char = chr($array[$i]);
		echo "$i $char\n";
	}
}

function arrayToString($inputArray,$count)
{
	$outputString = '';
	$char = '';
	for ($i = 0 ; $i < $count;$i++) {
		$char = chr($inputArray[$i]);
		$outputString .= $char;
		//echo "$i ----> ". ord($char) ."\n";
	}
	return $outputString;
}

function base32_decode_buffer_size ($encodedTextLength)
{
  if ($encodedTextLength == 0 || $encodedTextLength % 8 != 0)
    return 0;
  return intval(($encodedTextLength * 5) / 8);
}
function base32_decode ($input,$outputLength)
{
	$inputLength = strlen($input);
	// echo "legnth is: $inputLength output:$outputLength\n$input\n";
	$bytes = 0;
  	$currentByte = 0;
	$output = '';
	 for ($offset = 0; $offset < $inputLength  && $bytes < $outputLength; $offset += 8)
	 {
		// print "offset: $offset\n";
	   $output[$bytes] = decode_bits ($input{$offset + 0}) << 3;
	   $currentByte = decode_bits ($input{$offset + 1});
	   $output[$bytes] += $currentByte >> 2;
	   $output[$bytes + 1] = ($currentByte & 0x03) << 6;

	   if ($input{$offset + 2} == '='){
		// print "Return + 2 bytes:$bytes\n";
	     return arrayToString($output,$bytes+1);
	}
	   else
	     $bytes++;

	   $output[$bytes] += decode_bits ($input{$offset + 2}) << 1;
	   $currentByte = decode_bits ($input{$offset + 3});
	   $output[$bytes] += $currentByte >> 4;
	   $output[$bytes + 1] = $currentByte << 4;

$z = $input{$offset+4};
       // print "off4 = $z\n";
	   if ($input{$offset + 4} == '='){
		// print "Return + 4 bytes:$bytes\n";
	     return arrayToString($output,$bytes+1);
	}
	   else
	     $bytes++;

	   $currentByte = decode_bits ($input{$offset + 4});
	   $output[$bytes] += $currentByte >> 1;
	   $output[$bytes + 1] = $currentByte << 7;

	   if ($input{$offset + 5} == '='){
		// print "Return + 5 bytes:$bytes\n";
	     return arrayToString($output,$bytes+1);
	}
	   else
	     $bytes++;

	   $output[$bytes] += decode_bits ($input{$offset + 5}) << 2;
	   $currentByte = decode_bits ($input{$offset + 6});
	   $output[$bytes] +=  $currentByte >> 3;
	   $output[$bytes + 1] = ($currentByte & 0x07) << 5;

	   if ($input{$offset + 7} == '='){
		// print "Return + 7 bytes:$bytes\n";
	     return arrayToString($output,$bytes+1);
	}
	   else
	     $bytes++;

	   $output[$bytes] += decode_bits ($input{$offset + 7}) & 0x1F;
	   $bytes++;
	 }
     return arrayToString($output,$bytes);
}

$test = false;
if ($test) {
	
	print "buff len " . base32_decode_buffer_size(8) . "\n";
	print "buff len " . base32_decode_buffer_size(80) . "\n";
	
	$x = base32_decode('GEZDGNBV',base32_decode_buffer_size(8));
	$y = base32_decode('MFRGGZDF',base32_decode_buffer_size(8));

	echo  "$x should be 12345\n";
	echo  "$y should be abcde\n ";
}




?>
<?php
/*
 * base32 encoder functions - ported directly from Samuel Tesla's base32 ruby gem
 * Here's his copyright notice
 * Copyright (c) 2007-2009 Samuel Tesla
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 
 */

function base32_encoder_last_quintet($length)
{
	$quintets = intval($length * 8 / 5);
	$remainder = $length % 5;
	if ($remainder!=0) {
		$quintets++;		
	}
	return $quintets;
}

function ord_value($buffer, $offset) 
{
  	return ord(substr($buffer,$offset,1));
}

function base32_encoder_encode_bits($position, $buffer)
{
	$offset = intval(($position / 8)) * 5;
	//echo "$position ---> $offset\n";
	switch ($position % 8) {
		case 0:
			return ((ord_value($buffer,$offset) & 0xF8) >> 3);
		 case 1:
		      return
		        ((ord_value($buffer,$offset) & 0x07) << 2) +
		        ((ord_value($buffer,$offset+1) & 0xC0) >> 6);

		    case 2:
		      return
		        ((ord_value($buffer,$offset+1) & 0x3E) >> 1);

		    case 3:
		      return
		        ((ord_value($buffer,$offset+1) & 0x01) << 4) +
		        ((ord_value($buffer,$offset+2) & 0xF0) >> 4);

		    case 4:
		      return
		        ((ord_value($buffer,$offset+2) & 0x0F) << 1) +
		        ((ord_value($buffer,$offset+3) & 0x80) >> 7);

		    case 5:
		      return
		        ((ord_value($buffer,$offset+3) & 0x7C) >> 2);

		    case 6:
		      return
		        ((ord_value($buffer,$offset+3) & 0x03) << 3) +
		        ((ord_value($buffer,$offset+4) & 0xE0) >> 5);

		    case 7:
		      return
		        ord_value($buffer,$offset+4) & 0x1F;
		    
	}
}


function base32_encoder_encode_at_position ($position, $buffer)
{
  $table = "ABCDEFGHIJKLMNOPQRSTUVWXYZ234567";
  $index = base32_encoder_encode_bits ($position, $buffer);
//$y = substr($table,$index,1);
//  echo "$buffer -> $position index: $index $y\n";
  return substr($table,$index,1);
}

function encode($number)
{
	$quintets = base32_encoder_last_quintet(strlen($number));
	#//echo "quintets: $quintets for $number\n";
	$output = '';
	for ($i=0; $i < $quintets;$i++) {
		$output .= base32_encoder_encode_at_position($i,$number);
	}
	
	return $output;
}

$test = false;
if ($test) {

	$x = encode("12345");
	$y = encode("abcde");

	echo "$x should be GEZDGNBV\n";
	echo "$y should be MFRGGZDF\n ";	
}

?>
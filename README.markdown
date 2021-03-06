phpFob
========
-----------

1. DESCRIPTION
==============

phpFob is a PHP implementation of the most excellent CocoaFob at
<http://github.com/gbd/cocoafob/>.


phpFob can generate or decode CocoaFob compatible license codes.  phpFob extends 
the use of CocoaFob and opens up the use of CocoaFob for licensing directly in 
FastSpring <http://www.fastspring.com/>, Shine <http://github.com/tylerhall/Shine> or others.

2. USAGE
========

The best way to get the latest version of the code is to clone the main Git
repository:

git://github.com/jschilli/phpFob.git


licensekey.php has a test function - it illustrates the usage of the library

		$lic = make_license('product', 'User Name', 10);
		echo "$lic\n";
		if (verify_license('product', 'User Name', 10, $lic)) {
			echo "License IS Valid\n";
		} else {
			echo "License IS NOT valid\n";
		}

should result in something like this:

GAWAE-FAJYB-FRQ52-PJTD6-BXVTP-B56N4-QPL5M-PN2AC-CR2E2-5Z2DV-9746H-T3GJP-TANPE-BQLFA-6STE
License IS Valid

Please DO NOT use the enclosed keys!  They are for test purposes only.

Check this space for updates to phpFob and its inclusion in other systems.


3. LICENCE
==========

phpFob is Copyright (C) 2010 manicwave Productions
<http://manicwave.com>. All rights reserved. 

This code is released under the MIT Open Source License. Feel free to do whatever you want with it.

4. CREDITS
==========

The php implementation is effectively a line for line port from Gleb Dolgich's (@glebd)
cocoafob <http://github.com/gbd/cocoafob/>.

CocoaFob is Copyright (C) 2009 PixelEspresso
<http://www.pixelespressoapps.com>. All rights reserved. Written by Gleb
Dolgich (Twitter: @glebd)

CocoaFob is distributed under Creative Commons Attribution 3.0 License
<http://creativecommons.org/licenses/by/3.0/>. Attribution may take form of a
mention in your application About box or other documentation.

encoder.php and decoder.php are effectively line for line ports of Samuel Tesla's Base32 
implementation.

Base32 implementation is Copyright (C) 2007 by Samuel Tesla and comes from
Ruby base32 gem: <http://rubyforge.org/projects/base32/>. Samuel Tesla's blog
is at <http://blog.alieniloquent.com/tag/base32/>.


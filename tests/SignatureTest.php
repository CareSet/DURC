<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use CareSet\DURC;

final class SignatureTest  extends TestCase
{


//
//	The signature of a php file should the md5 of everything under the namespace command...
//	so we do the md5 of a stub file which has truncated to just contain that
//	then we run the signature function and see that they return the same result
//
//
    	public function testBasicSigningWorks(): void
    	{


		$stub_md5 = md5_file(__DIR__ . "/testfile.stub.php");
	
		$whole_file_contents = file_get_contents(__DIR__."/testfile.naked.php");

		$signature = \CareSet\DURC\Signature::calculate_signature_from_phpfile_string($whole_file_contents);

	        $this->assertEquals(
			$stub_md5,
			$signature
        	);
    	}

//
//	make sure has_signed_file_changed returns false when a file has not changed
//
    	public function testSignatureUnchanged(): void 
	{

		$whole_file_contents = file_get_contents(__DIR__."/testfile.signed.unchanged.php");
		
		$has_changed = \CareSet\DURC\Signature::has_signed_file_changed($whole_file_contents);
	
		$this->assertEquals(
			$has_changed,
			false
		);		


	}
//
//	make sure has_signed_file_changed returns false when a file has not changed
// 
   	public function testSignatureChanged(): void 
	{

		$whole_file_contents = file_get_contents(__DIR__."/testfile.signed.changed.php");
		
		$has_changed = \CareSet\DURC\Signature::has_signed_file_changed($whole_file_contents);
	
		$this->assertEquals(
			$has_changed,
			true
		);		

	}



//
//	lets create a signed file, and then see if that file signature is what we are expecting...
//
	public function testCreateSignedFile(): void
	{

		$whole_file_contents = file_get_contents(__DIR__."/testfile.naked.php");
		$signed_file = \CareSet\DURC\Signature::sign_phpfile_string($whole_file_contents);

		//echo "\n#### signed file\n$signed_file\n#######\n";
	
		$has_changed = \CareSet\DURC\Signature::has_signed_file_changed($signed_file);

		$this->assertEquals(
			$has_changed,
			false
		);		
		
	}

}


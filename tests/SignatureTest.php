<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use CareSet\DURC;

final class SignatureTest  extends TestCase
{
/*
	The signature of a php file should the md5 of everything under the namespace command...
	so we do the md5 of a stub file which has truncated to just contain that
	then we run the signature function and see that they return the same result


*/
    public function testBasicSigningWorks(): void
    {


	$stub_md5 = md5_file(__DIR__ . "/testfile.stub.php");
	
	$whole_file_contents = file_get_contents(__DIR__."/testfile.php");

	$signature = \CareSet\DURC\Signature::calculate_signature_from_phpfile_string($whole_file_contents);


        $this->assertEquals(
		$stub_md5,
		$signature
        );
    }
}


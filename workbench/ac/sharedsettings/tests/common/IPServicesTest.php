<?php

require_once __DIR__ .'/../autoload.php';

use Ac\SharedSettings\Common\IPServices;

class IPServicesTest extends TestCase {

	public function testValidateIP()
	{
        //Valid IP
        $ip = IPServices::validateIP('93.168.0.1');
		$this->assertTrue($ip);

        //Invalid IP
        $ip = IPServices::validateIP('192.168.0.1000');
        $this->assertTrue(!$ip);
	}
}

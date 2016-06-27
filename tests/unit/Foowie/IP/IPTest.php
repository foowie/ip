<?php

namespace Foowie\IP;

use UnitTest;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class IPTest extends UnitTest {

	public function testIPv4PlainFormat() {
		$ipAddresses = [
			'99.88.77.66' => '99.88.77.66',
			'0.0.0.1' => '0.0.0.1',
			'255.255.255.255' => '255.255.255.255',
		];
		foreach($ipAddresses as $ipInput => $ipOutput) {
		    $ip = new IP($ipInput);
			$this->assertEquals($ipOutput, $ip->getIP());
		}
	}

	public function testIPv4HexFormat() {
		$ipAddresses = [
			'99.88.77.66' => '63584D42',
			'0.0.0.1' => '1',
			'0.1.0.0' => '10000',
			'255.255.255.255' => 'FFFFFFFF',
		];
		foreach($ipAddresses as $ipInput => $ipOutput) {
		    $ip = new IP($ipInput);
			$this->assertEquals($ipOutput, $ip->getHexIP());
		}
	}

	public function testIPv4BinaryFormat() {
		$ipAddresses = [
			'99.88.77.66' => chr(99) . chr(88) . chr(77) . chr(66),
			'0.0.0.1' => "\0\0\0" . chr(1),
			'0.1.0.0' => chr(0) . chr(1) . chr(0) . chr(0),
			'255.255.255.255' => chr(255) . chr(255) . chr(255) . chr(255),
		];
		foreach($ipAddresses as $ipInput => $ipOutput) {
		    $ip = new IP($ipInput);
			$this->assertEquals($ipOutput, $ip->getBinaryIP());
		}
	}

	public function testIPv6PlainFormat() {
		$ipAddresses = [
			'FE80:0000:0000:0000:0202:B3FF:FE1E:8329' => 'fe80::202:b3ff:fe1e:8329',
			'fe80:0000:0000:0000:0202:b3ff:fe1e:8329' => 'fe80::202:b3ff:fe1e:8329',
			'fe80::0202:b3ff:fe1e:8329' => 'fe80::202:b3ff:fe1e:8329',
			'2001:db8::1' => '2001:db8::1',
			'2607:f0d0:1002:0051:0000:0000:0000:0004' => '2607:f0d0:1002:51::4',
		];
		foreach($ipAddresses as $ipInput => $ipOutput) {
			$ip = new IP($ipInput);
			$this->assertEquals($ipOutput, $ip->getIP());
		}
	}

	public function testIPv6PlainFormatPointingToIPv4() {
		$ip = new IP('0000:0000:0000:0000:0000:0000:0000:0004');
		$this->assertRegExp('#::(0\.0\.0\.)?4#', $ip->getIP());
	}

	public function testIPv6HexFormat() {
		$ipAddresses = [
			'FE80:0000:0000:0000:0202:B3FF:FE1E:8329' => 'FE800000000000000202B3FFFE1E8329',
			'0000:0000:0000:0000:0000:0000:0000:0004' => '00000000000000000000000000000004',
		];
		foreach($ipAddresses as $ipInput => $ipOutput) {
			$ip = new IP($ipInput);
			$this->assertEquals($ipOutput, $ip->getHexIP());
		}
	}

	public function testIPv6BinaryFormat() {
		$ipAddresses = [
			'FE80:0000:0000:0000:0202:B3FF:FE1E:8329' => chr(254) . chr(128) . "\0\0\0\0\0\0" . chr(2) . chr(2) . chr(179) . chr(255) . chr(254) . chr(30) . chr(131) . chr(41),
			'0000:0000:0000:0000:0000:0000:0000:0004' => "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0" . chr(4),
		];
		foreach($ipAddresses as $ipInput => $ipOutput) {
			$ip = new IP($ipInput);
			$this->assertEquals($ipOutput, $ip->getBinaryIP());
		}
	}

	public function testIsIPv4() {
		$ipAddresses = [
			'99.88.77.66' => true,
			'0.0.0.1' => true,
			'0.1.0.0' => true,
			'255.255.255.255' => true,
			'FE80:0000:0000:0000:0202:B3FF:FE1E:8329' => false,
			'0000:0000:0000:0000:0000:0000:0000:0004' => false,
		];
		foreach($ipAddresses as $ipInput => $ipOutput) {
			$ip = new IP($ipInput);
			$this->assertEquals($ipOutput, $ip->isIPv4());
		}
	}
	
	public function testIsIPv6() {
		$ipAddresses = [
			'99.88.77.66' => false,
			'0.0.0.1' => false,
			'0.1.0.0' => false,
			'255.255.255.255' => false,
			'FE80:0000:0000:0000:0202:B3FF:FE1E:8329' => true,
			'0000:0000:0000:0000:0000:0000:0000:0004' => true,
		];
		foreach($ipAddresses as $ipInput => $ipOutput) {
			$ip = new IP($ipInput);
			$this->assertEquals($ipOutput, $ip->isIPv6());
		}
	}

}
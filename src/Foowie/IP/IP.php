<?php

namespace Foowie\IP;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 * @property-read string $ip
 * @property-read string $hexIp
 * @property-read string $binaryIp
 */
class IP {

	/** @var string */
	protected $binaryIp;

	function __construct($ip) {
		$this->binaryIp = inet_pton($ip);
	}

	/**
	 * @return string IPv4/IPv6 address in dot-form (11.55.99.88 or fe80::202:b3ff:fe1e:8329)
	 */
	public function getIP() {
		return inet_ntop($this->binaryIp);
	}

	/**
	 * @return string IPv4/IPv6 address binary form
	 */
	public function getBinaryIP() {
		return $this->binaryIp;
	}

	/**
	 * @return string IPv4/IPv6 address hex form (8/32 upper characters)
	 */
	public function getHexIP() {
		return strtoupper(bin2hex($this->binaryIp));
	}

	/**
	 * @return boolean
	 */
	public function isIPv4() {
	    return strlen($this->binaryIp) === 4;
	}
	
	/**
	 * @return boolean
	 */
	public function isIPv6() {
		return strlen($this->binaryIp) === 16;
	}
	
	/**
	 * @return IP
	 */
	public static function fromHexFormat($value) {
		return $value === null ? null : new IP(inet_ntop(hex2bin($value)));
	}

	/**
	 * @deprecated
	 * @param bool $deep
	 * @return IP
	 */
	public static function createRemoteIP($deep = false) {
		$ip = $_SERVER["REMOTE_ADDR"];
		if($deep) {
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			if (isset($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			}
		}
		return new IP($ip);
	}

	function __toString() {
		return $this->getHexIP();
	}

	function __get($name) {
		switch ($name) {
			case 'ip': return $this->getIP();
			case 'hexIp': return $this->getHexIP();
			case 'binaryIp': return $this->getBinaryIP();
			default: return parent::__get($name);
		}
	}

}
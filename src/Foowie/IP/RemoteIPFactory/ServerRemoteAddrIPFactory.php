<?php

namespace Foowie\IP\RemoteIPFactory;

use Foowie\IP\IP;
use Vectorface\Whip\IpRange\IpWhitelist;
use Vectorface\Whip\Request\RequestAdapter;
use Vectorface\Whip\Request\SuperglobalRequestAdapter;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class ServerRemoteAddrIPFactory implements IPFactory {

	/** @var IpWhitelist */
	protected $proxyIpWhitelist;

	/** @var RequestAdapter */
	protected $requestAdapter;

	public function __construct(IpWhitelist $proxyIpWhitelist = null, RequestAdapter $requestAdapter = null) {
		$this->proxyIpWhitelist = $proxyIpWhitelist;
		$this->requestAdapter = $requestAdapter === null ? new SuperglobalRequestAdapter($_SERVER) : $requestAdapter;
	}

	/**
	 * @return IP
	 * @throws NoRemoteIPException
	 */
	public function createRemoteIP() {
		$remoteAddress = $this->requestAdapter->getRemoteAddr();
		if($remoteAddress === null) {
			throw new NoRemoteIPException("Can't detect remote IP address!");
		}
		$proxiedIp = $this->getProxiedIp($remoteAddress);
		return $proxiedIp === null ? new IP($remoteAddress) : $proxiedIp;
	}

	/**
	 * @param string|null $remoteAddress
	 * @return IP
	 */
	protected function getProxiedIp($remoteAddress) {
		if ($this->proxyIpWhitelist !== null && $this->proxyIpWhitelist->isIpWhitelisted($remoteAddress)) {
			$headers = $this->requestAdapter->getHeaders();
			if (isset($headers['x-forwarded-for'])) {
				$ipAddresses = explode(',', str_replace(' ', '', $headers['x-forwarded-for']));
				if (@\inet_pton($ipAddresses[0]) !== false) {
					return new IP($ipAddresses[0]);
				}
			}
		}
		return null;
	}

}
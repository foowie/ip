<?php

namespace Foowie\IP\RemoteIPFactory;

use Foowie\CloudflareDetection\CloudflareDetector;
use Foowie\IP\IP;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CloudflareIPFactory implements IPFactory {

	/** @var CloudflareDetector */
	protected $cloudflareDetector;

	/** @var IPFactory */
	protected $fallback;

	public function __construct(CloudflareDetector $cloudflareDetector, IPFactory $fallback) {
		$this->cloudflareDetector = $cloudflareDetector;
		$this->fallback = $fallback;
	}

	/**
	 * @return IP
	 */
	public function createRemoteIP() {
		if($this->cloudflareDetector->isCloudflareRequest()) {
			return new IP($this->cloudflareDetector->getConnectingRequestIp());
		} else {
			return $this->fallback->createRemoteIP();
		}
	}

}
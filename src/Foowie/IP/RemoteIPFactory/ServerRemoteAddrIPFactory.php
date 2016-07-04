<?php

namespace Foowie\IP\RemoteIPFactory;
use Foowie\IP\IP;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class ServerRemoteAddrIPFactory implements IPFactory {

	/**
	 * @return IP
	 */
	public function createRemoteIP() {
		return new IP($_SERVER["REMOTE_ADDR"]);
	}

}
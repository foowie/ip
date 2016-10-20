<?php

namespace Foowie\IP\RemoteIPFactory;
use Foowie\IP\IP;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class ServerRemoteAddrIPFactory implements IPFactory {

	/**
	 * @return IP
	 * @throws NoRemoteIPException
	 */
	public function createRemoteIP() {
		if(!isset($_SERVER["REMOTE_ADDR"])) {
			throw new NoRemoteIPException("Can't detect remote IP address!");
		}
		return new IP($_SERVER["REMOTE_ADDR"]);
	}

}
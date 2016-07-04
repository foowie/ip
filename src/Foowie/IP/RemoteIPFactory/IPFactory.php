<?php

namespace Foowie\IP\RemoteIPFactory;
use Foowie\IP\IP;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
interface IPFactory {

	/**
	 * @return IP
	 */
	public function createRemoteIP();
	
}
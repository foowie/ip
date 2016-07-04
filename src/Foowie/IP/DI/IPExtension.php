<?php

namespace Foowie\IP\DI;

use Kdyby\Doctrine\DI\IDatabaseTypeProvider;
use Nette\DI\CompilerExtension;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class IPExtension extends CompilerExtension implements IDatabaseTypeProvider {

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();

		if(class_exists('Foowie\CloudflareDetection\CloudflareDetector')) {
			$fallback = $builder->addDefinition($this->prefix('fallbackIpFactory'))
				->setClass('Foowie\IP\RemoteIPFactory\ServerRemoteAddrIPFactory')
				->setAutowired(false);
			$cloudflareDetector = $builder->addDefinition($this->prefix('cloudflareDetector'))
				->setClass('Foowie\CloudflareDetection\CloudflareDetector')
				->setAutowired(false);
			$builder->addDefinition($this->prefix('ipFactory'))
				->setClass('Foowie\IP\RemoteIPFactory\CloudflareIPFactory', [$cloudflareDetector, $fallback]);
		} else {
			$builder->addDefinition($this->prefix('ipFactory'))
				->setClass('Foowie\IP\RemoteIPFactory\ServerRemoteAddrIPFactory');
		}
	}

	/**
	 * Returns array of typeName => typeClass.
	 *
	 * @return array
	 */
	function getDatabaseTypes() {
		return array(
			\Foowie\IP\Types\IP::IP => 'Foowie\IP\Types\IP',
		);
	}
}
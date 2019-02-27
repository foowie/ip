<?php

namespace Foowie\IP\DI;

use Kdyby\Doctrine\DI\IDatabaseTypeProvider;
use Nette\DI\CompilerExtension;
use Nette\DI\ContainerBuilder;
use Nette\DI\ServiceDefinition;
use Vectorface\Whip\IpRange\IpWhitelist;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class IPExtension extends CompilerExtension implements IDatabaseTypeProvider {

	/** @var array */
	public $defaults = [
		'proxy' => [
			'enabled' => false,
			'filter' => [ //all addresses by default
				'IPv4' => '0.0.0.0/0',
				'IPv6' => '::/0',
			],
		]
	];

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();

		if(class_exists('Foowie\CloudflareDetection\CloudflareDetector')) {
			$fallback = $this->getDefaultFactory($builder, $this->prefix('fallbackIpFactory'))
				->setAutowired(false);
			$cloudflareDetector = $builder->addDefinition($this->prefix('cloudflareDetector'))
				->setClass('Foowie\CloudflareDetection\CloudflareDetector')
				->setAutowired(false);
			$builder->addDefinition($this->prefix('ipFactory'))
				->setClass('Foowie\IP\RemoteIPFactory\CloudflareIPFactory', [$cloudflareDetector, $fallback]);
		} else {
			$this->getDefaultFactory($builder, $this->prefix('ipFactory'));
		}
	}

	/**
	 * Creates Foowie\IP\RemoteIPFactory\ServerRemoteAddrIPFactory service
	 * @param ContainerBuilder $builder
	 * @param string $prefix
	 * @return ServiceDefinition
	 */
	protected function getDefaultFactory(ContainerBuilder $builder, $prefix) {
		$config = $this->getConfig($this->defaults);
		$whitelist = null;
		if ((bool)$config['proxy']['enabled']) {
			$whitelist = new IpWhitelist([
				IpWhitelist::IPV4 => (array)$config['proxy']['filter']['IPv4'],
				IpWhitelist::IPV6 => (array)$config['proxy']['filter']['IPv6'],
			]);
		}
		
		return $builder->addDefinition($prefix)
			->setClass('Foowie\IP\RemoteIPFactory\ServerRemoteAddrIPFactory', [$whitelist]);
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
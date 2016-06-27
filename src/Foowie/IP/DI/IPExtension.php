<?php

namespace Foowie\IP\DI;

use Kdyby\Doctrine\DI\IDatabaseTypeProvider;
use Nette\DI\CompilerExtension;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class IPExtension extends CompilerExtension implements IDatabaseTypeProvider {

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
<?php

namespace Foowie\IP\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class IP extends Type {

	const IP = 'ip';

	public function getName() {
		return self::IP;
	}

	public function convertToPHPValue($value, AbstractPlatform $platform) {
		return \Foowie\IP\IP::fromHexFormat($value);
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform) {
		if ($value === null) {
			return null;
		} else if ($value instanceof \Foowie\IP\IP) {
			return $value->getHexIP();
		} else {
			throw new \Exception("Invalid IP value: $value");
		}
	}

	public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform) {
		if (!isset($fieldDeclaration['length'])) {
			$fieldDeclaration['length'] = 32;
		}
		return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
	}

	public function getDefaultLength(AbstractPlatform $platform) {
		return 32;
	}

}
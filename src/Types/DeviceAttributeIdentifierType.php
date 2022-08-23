<?php declare(strict_types = 1);

/**
 * DeviceAttributeIdentifierType.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:FbMqttConnector!
 * @subpackage     Types
 * @since          0.25.0
 *
 * @date           23.07.22
 */

namespace FastyBird\FbMqttConnector\Types;

use Consistence;
use FastyBird\Metadata\Types as MetadataTypes;

/**
 * Device attribute identifier types
 *
 * @package        FastyBird:FbMqttConnector!
 * @subpackage     Types
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class DeviceAttributeIdentifierType extends Consistence\Enum\Enum
{

	/**
	 * Define device states
	 */
	public const IDENTIFIER_HARDWARE_MAC_ADDRESS = MetadataTypes\DeviceAttributeIdentifierType::IDENTIFIER_HARDWARE_MAC_ADDRESS;
	public const IDENTIFIER_HARDWARE_MANUFACTURER = MetadataTypes\DeviceAttributeIdentifierType::IDENTIFIER_HARDWARE_MANUFACTURER;
	public const IDENTIFIER_HARDWARE_MODEL = MetadataTypes\DeviceAttributeIdentifierType::IDENTIFIER_HARDWARE_MODEL;
	public const IDENTIFIER_HARDWARE_VERSION = MetadataTypes\DeviceAttributeIdentifierType::IDENTIFIER_HARDWARE_VERSION;
	public const IDENTIFIER_FIRMWARE_MANUFACTURER = MetadataTypes\DeviceAttributeIdentifierType::IDENTIFIER_FIRMWARE_MANUFACTURER;
	public const IDENTIFIER_FIRMWARE_NAME = MetadataTypes\DeviceAttributeIdentifierType::IDENTIFIER_FIRMWARE_NAME;
	public const IDENTIFIER_FIRMWARE_VERSION = MetadataTypes\DeviceAttributeIdentifierType::IDENTIFIER_FIRMWARE_VERSION;

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return strval(self::getValue());
	}

}

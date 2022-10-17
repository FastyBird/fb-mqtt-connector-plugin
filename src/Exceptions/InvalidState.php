<?php declare(strict_types = 1);

/**
 * InvalidState.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:FbMqttConnector!
 * @subpackage     Exceptions
 * @since          0.1.0
 *
 * @date           23.02.20
 */

namespace FastyBird\Connector\FbMqtt\Exceptions;

use RuntimeException;

class InvalidState extends RuntimeException implements Exception
{

}
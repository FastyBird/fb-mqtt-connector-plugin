<?php declare(strict_types = 1);

/**
 * Control.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:MqttConnectorPlugin!
 * @subpackage     Entities
 * @since          0.1.0
 *
 * @date           25.02.20
 */

namespace FastyBird\MqttConnectorPlugin\Entities;

use FastyBird\ModulesMetadata\Types as ModulesMetadataTypes;
use FastyBird\MqttConnectorPlugin\Exceptions;
use Nette\Utils;
use Ramsey\Uuid;

/**
 * Device control attribute
 *
 * @package        FastyBird:MqttConnectorPlugin!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
abstract class Control extends Entity
{

	public const CONFIG = ModulesMetadataTypes\ControlNameType::NAME_CONFIGURE;
	public const RESET = ModulesMetadataTypes\ControlNameType::NAME_RESET;
	public const REBOOT = ModulesMetadataTypes\ControlNameType::NAME_REBOOT;
	public const RECONNECT = 'reconnect';
	public const FACTORY_RESET = 'factory-reset';
	public const OTA = 'ota';

	public const DATA_TYPE_BOOLEAN = 'boolean';
	public const DATA_TYPE_NUMBER = 'number';
	public const DATA_TYPE_SELECT = 'select';
	public const DATA_TYPE_TEXT = 'text';

	/** @var string */
	private string $control;

	/** @var mixed[]|string|null */
	private $value = null;

	/** @var mixed[]|string|null */
	private $schema = null;

	public function __construct(
		Uuid\UuidInterface $clientId,
		string $device,
		string $control,
		?string $parent = null
	) {
		if (!in_array($control, $this->getAllowedControls(), true)) {
			throw new Exceptions\InvalidArgumentException(sprintf('Provided control "%s" is not in allowed range', $control));
		}

		parent::__construct($clientId, $device, $parent);

		$this->control = $control;
	}

	/**
	 * @return string
	 */
	public function getControl(): string
	{
		return $this->control;
	}

	/**
	 * @return mixed[]|string|null
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param string|null $value
	 *
	 * @return void
	 */
	public function setValue(?string $value): void
	{
		$this->value = $value;

		if ($this->control === self::CONFIG && $value !== null) {
			try {
				$this->value = Utils\Json::decode($value, Utils\Json::FORCE_ARRAY);

			} catch (Utils\JsonException $ex) {
				throw new Exceptions\ParseMessageException('Control config payload is not valid JSON value');
			}
		}
	}

	/**
	 * @return mixed[]|string|null
	 */
	public function getSchema()
	{
		if (!$this->isConfiguration()) {
			throw new Exceptions\InvalidStateException(sprintf('Schema could be get only for "%s" control type', self::CONFIG));
		}

		return $this->schema;
	}

	/**
	 * @param string $schema
	 *
	 * @return void
	 */
	public function setSchema(string $schema): void
	{
		if (!$this->isConfiguration()) {
			throw new Exceptions\InvalidStateException(sprintf('Schema could be set only for "%s" control type', self::CONFIG));
		}

		try {
			$decodedSchema = Utils\Json::decode($schema, Utils\Json::FORCE_ARRAY);

		} catch (Utils\JsonException $ex) {
			throw new Exceptions\ParseMessageException('Control payload is not valid JSON value');
		}

		$this->schema = [];

		/** @var Utils\ArrayHash $row */
		foreach (Utils\ArrayHash::from($decodedSchema) as $row) {
			if (!$row->offsetExists('type') || !$row->offsetExists('identifier') || !$row->offsetExists('name')) {
				continue;
			}

			$formattedRow = Utils\ArrayHash::from([
				'identifier' => $row->offsetGet('identifier'),
				'type'       => $row->offsetGet('type'),
				'name'       => $row->offsetGet('name'),
				'title'      => null,
				'comment'    => null,
				'default'    => null,
			]);

			if ($row->offsetExists('title') && $row->offsetGet('title') !== '') {
				$formattedRow->offsetSet('title', $row->offsetGet('title'));
			}

			if ($row->offsetExists('comment') && $row->offsetGet('comment') !== '') {
				$formattedRow->offsetSet('comment', $row->offsetGet('comment'));
			}

			switch ($row->offsetGet('type')) {
				case self::DATA_TYPE_NUMBER:
					$formattedRow->offsetSet('data_type', ModulesMetadataTypes\DataTypeType::DATA_TYPE_FLOAT);

					foreach (['min', 'max', 'step', 'default'] as $field) {
						if ($row->offsetExists($field)) {
							$formattedRow->offsetSet($field, (float) $row->offsetGet($field));

						} else {
							$formattedRow->offsetSet($field, null);
						}
					}

					break;

				case self::DATA_TYPE_TEXT:
					$formattedRow->offsetSet('data_type', ModulesMetadataTypes\DataTypeType::DATA_TYPE_STRING);

					if ($row->offsetExists('default')) {
						$formattedRow->offsetSet('default', (string) $row->offsetGet('default'));
					}

					break;

				case self::DATA_TYPE_BOOLEAN:
					$formattedRow->offsetSet('data_type', ModulesMetadataTypes\DataTypeType::DATA_TYPE_BOOLEAN);

					if ($row->offsetExists('default')) {
						$formattedRow->offsetSet('default', (bool) $row->offsetGet('default'));
					}

					break;

				case self::DATA_TYPE_SELECT:
					$formattedRow->offsetSet('data_type', ModulesMetadataTypes\DataTypeType::DATA_TYPE_ENUM);

					if (
						$row->offsetExists('values')
						&& $row->offsetGet('values') instanceof Utils\ArrayHash
					) {
						$selectValues = [];

						foreach ($row->offsetGet('values') as $value) {
							if (
								$value instanceof Utils\ArrayHash
								&& $value->offsetExists('value')
								&& $value->offsetExists('name')
							) {
								$selectValues[] = Utils\ArrayHash::from([
									'value' => (string) $value->offsetGet('value'),
									'name'  => (string) $value->offsetGet('name'),
								]);
							}
						}

						$formattedRow->offsetSet('values', $selectValues);

					} else {
						$formattedRow->offsetSet('values', []);
					}

					if ($row->offsetExists('default')) {
						$formattedRow->offsetSet('default', (string) $row->offsetGet('default'));
					}

					break;
			}

			$this->schema[] = (array) $formattedRow;
		}
	}

	/**
	 * @return bool
	 */
	public function isConfiguration(): bool
	{
		return $this->control === self::CONFIG;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$return = array_merge([
			'control' => $this->getControl(),
		], parent::toArray());

		if ($this->getValue() !== null) {
			$return['value'] = $this->getValue();
		}

		if ($this->isConfiguration() && $this->getSchema() !== null) {
			$return['schema'] = $this->getSchema();
		}

		return $return;
	}

	/**
	 * @return string[]
	 */
	protected function getAllowedControls(): array
	{
		return [];
	}

}

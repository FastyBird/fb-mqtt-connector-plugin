<?php declare(strict_types = 1);

use FastyBird\FbMqttConnector\Entities;

return [
	'hw-' . Entities\Messages\ExtensionAttributeEntity::MAC_ADDRESS  => [
		'/fb/v1/device-name/$hw/' . Entities\Messages\ExtensionAttributeEntity::MAC_ADDRESS,
		'00:0a:95:9d:68:16',
		[
			'device'                                          => 'device-name',
			'retained'                                        => false,
			Entities\Messages\ExtensionAttributeEntity::MAC_ADDRESS => '000a959d6816',
		],
	],
	'hw-' . Entities\Messages\ExtensionAttributeEntity::MANUFACTURER => [
		'/fb/v1/device-name/$hw/' . Entities\Messages\ExtensionAttributeEntity::MANUFACTURER,
		'value-content',
		[
			'device'                                           => 'device-name',
			'retained'                                         => false,
			Entities\Messages\ExtensionAttributeEntity::MANUFACTURER => 'value-content',
		],
	],
	'hw-' . Entities\Messages\ExtensionAttributeEntity::MODEL        => [
		'/fb/v1/device-name/$hw/' . Entities\Messages\ExtensionAttributeEntity::MODEL,
		'value-content',
		[
			'device'                                    => 'device-name',
			'retained'                                  => false,
			Entities\Messages\ExtensionAttributeEntity::MODEL => 'value-content',
		],
	],
	'hw-' . Entities\Messages\ExtensionAttributeEntity::VERSION      => [
		'/fb/v1/device-name/$hw/' . Entities\Messages\ExtensionAttributeEntity::VERSION,
		'value-content',
		[
			'device'                                      => 'device-name',
			'retained'                                    => false,
			Entities\Messages\ExtensionAttributeEntity::VERSION => 'value-content',
		],
	],
];

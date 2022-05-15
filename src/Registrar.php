<?php
namespace TopShelfCraft\base;

use Craft;
use craft\base\Plugin;
use craft\helpers\App;
use craft\helpers\UrlHelper;

class Registrar {

	const REGISTRY = "https://ranger.topshelfcraft.com/w";
	const SCHEMA_VERSION = "4.0.0";

	public static function note(string $id = '', string $type = 'maintenance', ?string $note = null, array $headers = [])
	{

		try
		{
			$source = UrlHelper::siteUrl();
		}
		catch(\Throwable $e)
		{
			$source = '';
		}

		try
		{
			$registry = App::env('TOPSHELFCRAFT_REGISTRY') ?? self::REGISTRY;
			Craft::$app->getApi()->request(
				'POST', $registry . '/' . $id . '/' . $type,
				[
					'headers' => $headers + [
						'X-Registrar-Source' => $source,
						'X-Registrar-SchemaVersion' => self::SCHEMA_VERSION,
					],
					'connect_timeout' => 1.618,
					'timeout' => 1.618,
					'body' => $note,
				]
			);
		}
		catch (\Throwable $e) {}

	}

	public static function pluginNote(Plugin $plugin, string $type = 'maintenance', ?string $note = null)
	{
		self::note(
			$plugin->id,
			$type,
			$note,
			[
				'X-Registrar-Plugin-Version' => $plugin->getVersion(),
			]
		);
	}

}

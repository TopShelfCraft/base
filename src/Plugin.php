<?php
namespace TopShelfCraft\base;

use craft\base\Plugin as CraftPlugin;

class Plugin extends CraftPlugin {

	public function afterInstall(): void
	{
		parent::afterInstall();
		Registrar::pluginNote($this, 'install');
	}

	public function afterUninstall(): void
	{
		parent::afterUninstall();
		Registrar::pluginNote($this, 'uninstall');
	}

}

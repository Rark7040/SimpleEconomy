<?php
declare(strict_types = 1);

namespace rark\simple_economy;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase{

	protected function onEnable():void{
		if(!$this->saveResource($this->getFile().'resources/Config.yml', true)){
			throw new \RuntimeException('Configが正しく生成できませんでした');
		}
		Economy::init(new Config($this->getDataFolder().'Config.yml', Config::YAML));
		$this->getServer()->getPluginManager()->registerEvents(new EventHandler, $this);
	}
}
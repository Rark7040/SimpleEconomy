<?php
declare(strict_types = 1);

namespace rark\simple_economy;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase{

	protected static string $data_folder;

	protected function onEnable():void{
		self::$data_folder = $this->getDataFolder();

		if(!$this->saveResource($this->getFile().'resources/Config.yml', true)){
			throw new \RuntimeException('Configが正しく生成できませんでした');
		}
		Economy::init(new Config($this->getDataFolder().'Config.yml', Config::YAML));
		$this->getServer()->getPluginManager()->registerEvents(new EventHandler, $this);
	}

	public static function getPluginDataPath():string{
		return self::$data_folder;
	}
}
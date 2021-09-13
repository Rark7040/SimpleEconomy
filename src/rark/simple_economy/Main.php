<?php
declare(strict_types = 1);

namespace rark\simple_economy;

use pocketmine\plugin\PluginBase;
use rark\simple_economy\command\MoneyCommand;
use rark\simple_economy\libs\CortexPE\Commando\PacketHooker;

class Main extends PluginBase{

	protected static string $data_folder;

	protected function onEnable():void{
		if(!PacketHooker::isRegistered()) PacketHooker::register($this);
		self::$data_folder = $this->getDataFolder().'internal/';

		if(!is_dir($this->getDataFolder().'internal')){
			@mkdir($this->getDataFolder().'internal');
		}

		$this->reloadConfig();
		if(!$this->saveResource('config.yml', true)){
			throw new \RuntimeException('Configが正しく生成できませんでした');
		}
		Economy::init($this->getConfig());
		Account::init();
		$this->getServer()->getCommandMap()->register($this->getName(), new MoneyCommand($this));
		$this->getServer()->getPluginManager()->registerEvents(new EventHandler, $this);
	}

	protected function onDisable():void{
		$this->allsave();
	}

	public function __destruct(){
		$this->allsave();
	}

	protected function allsave():void{
		Account::save();
		Economy::save();
	}

	public static function getPluginDataPath():string{
		return self::$data_folder;
	}
}
<?php
declare(strict_types = 1);

namespace rark\simple_economy;

use pocketmine\player\Player;
use pocketmine\utils\Config;

class Money extends Config{

	protected string $name;
	protected string $prefix;
	protected bool $prefix_pos;
	protected int $default;

	public function __construct(string $name, string $prefix, bool $prefix_pos, int $default){
		$this->name = $name;
		$this->prefix = $prefix;
		$this->prefix_pos = $prefix_pos;
		parent::__construct(Main::getPluginDataPath().$name.'json', Config::JSON);
	}

	public function __destruct(){
		$this->save();
	}

	public function getName():string{
		return $this->name;
	}

	public function getPrefix():string{
		return $this->prefix;
	}

	public function getDefault():int{
		return $this->default;
	}

	public function getNumberFormat(int $amount):string{
		return $this->prefix_pos?
			$this->prefix.number_format($amount):
			number_format($amount).$this->prefix;
	}

	public function setMoney(Player $player, int $amount):void{
		$this->set($player->getName(), $amount);
	}

	public function getMoney(Player $player):int{
		return $this->get($player->getName(), null)?? $this->default;
	}

	public function addMoney(Player $player, int $amount):bool{
		if(!$this->canAddMoney($player, $amount)) return false;
		$this->setMoney($player, $this->getMoney($player)+$amount);
		return true;
	}

	public function reduceMoney(Player $player, int $amount):bool{
		if(!$this->canReduceMoney($player, $amount)) return false;
		$this->setMoney($player, $this->getMoney($player)-$amount);
		return true;
	}

	public function giveMoney(Player $from, Player $to, int $amount):bool{
		if(!$this->canAddMoney($to, $amount) or !$this->canReduceMoney($from, $amount)) return false;
		$this->addMoney($to, $amount);
		$this->reduceMoney($from, $amount);
		return true;
	}

	public function canAddMoney(Player $player, int $amount):bool{
		return $this->getMoney($player)+$amount < PHP_INT_MAX;
	}

	public function canReduceMoney(Player $player, int $amount):bool{
		return $this->getMoney($player)-$amount > -1;
	}
}
<?php
declare(strict_types = 1);

namespace rark\simple_economy\api;

use pocketmine\player\Player;
use rark\simple_economy\Account;
use rark\simple_economy\Economy;
use rark\simple_economy\Money;

class SimpleEconomyAPI{
	protected Money $money;
	protected string $currency;

	public function __construct(string $currency){
		$this->currency = $currency;
		$this->money = Economy::getInstance($currency)?? throw new \RuntimeException($currency.'という通貨は存在しません');
	}

	public function myMoney(Player|string $player):float{
		$account = $this->getAccount($player);

		if($account === null) return -1;
		return (float) $this->money->getMoney($account);
	}

	public function setMoney(Player|string $player, float $money):bool{
		$account = $this->getAccount($player);

		if($account === null) return false;
		$this->money->setMoney($account, (int) $money);
		return true;
	}

	public function addMoney(Player|string $player, float $money):bool{
		$account = $this->getAccount($player);

		if($account === null) return false;
		$this->money->addMoney($account, (int) $money);
		return true;
	}

	public function reduceMoney(Player|string $player, float $money):bool{
		$account = $this->getAccount($player);

		if($account === null) return false;
		$this->money->reduceMoney($account, (int) $money);
		return true;
	}

	public function existMoney(Player|string $player, float $money):bool{
		$account = $this->getAccount($player);

		if($account === null) return false;
		return $this->money->canReduceMoney($account, (int) $money);
	}

	public function getMoney():Money{
		return $this->money;
	}

	/**
	 * @param Player|string $player
	 * @return Account
	 */
	public function getAccount(Player|string $player):?Account{ //xboxアカウントを取得できない場合生成できない
		$name = $this->getTranslatedName($player);
		return Account::findByName($name)?? Account::create($player);
	}

	/**
	 * @param Player|String $player
	 * @return String
	 */
	public function getTranslatedName(Player|string $player):string{
		return $player instanceof Player? $player->getName(): $player;
	}
}
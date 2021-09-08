<?php
declare(strict_types = 1);

namespace rark\simple_economy\form;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use rark\simple_economy\Account;
use rark\simple_economy\Economy;
use rark\simple_economy\form\api\CustomForm;
use rark\simple_economy\Money;

class MoneyForms{

	protected static CustomForm $ranking_select_form;
	protected static CustomForm $view_select_form;

	public static function getAddMoneyForm():CustomForm{
		$form = new CustomForm;
		$form->addDropdown('account', '対象', 0, ...Account::getAllAccountNames());
		$form->addDropdown('currency', '種類', 0, ...Economy::getAllMoneyNames());
		$form->submit = function(Player $player, array $data):void{
			if(!isset($data['account']) or !isset($data['currency'])){
				$player->sendMessage(TextFormat::RED.'不正な入力データです');
				return;
			}
			$target = Account::findByName($data['account']);
			$money = Economy::getInstance($data['currency']);

			if(!$money instanceof Money){
				$player->sendMessage(TextFormat::RED.'通貨インスタンスを生成できませんでした');
				return;
			}
			$player->sendForm(
				new InputAmountForm(
					1000_0000,
					function(int $amount) use($target, $money):void{
						$money->addMoney($target, $amount);
					}
				)
			);
		};
		return $form;
	}

	public static function getGiveMoneyForm():CustomForm{
		$form = new CustomForm;
		$form->addDropdown('account', '対象', 0, ...Account::getAllAccountNames());
		$form->addDropdown('currency', '種類', 0, ...Economy::getAllMoneyNames());
		$form->submit = function(Player $player, array $data):void{
			if(!isset($data['account']) or !isset($data['currency'])){
				$player->sendMessage(TextFormat::RED.'不正な入力データです');
				return;
			}
			$account = Account::findByName($player->getName());
			$target = Account::findByName($data['account']);
			$money = Economy::getInstance($data['currency']);

			if(!$money instanceof Money){
				$player->sendMessage(TextFormat::RED.'通貨インスタンスを生成できませんでした');
				return;
			}
			$player->sendForm(
				new InputAmountForm(
					$money->getMoney($account),
					function(int $amount) use($account, $target, $money):void{
						$money->giveMoney($account, $target, $amount);
					}
				)
			);
		};
		return $form;
	}

	public static function getReduceForm():CustomForm{
		$form = new CustomForm;
		$form->addDropdown('account', '対象', 0, ...Account::getAllAccountNames());
		$form->addDropdown('currency', '種類', 0, ...Economy::getAllMoneyNames());
		$form->submit = function(Player $player, array $data):void{
			if(!isset($data['account']) or !isset($data['currency'])){
				$player->sendMessage(TextFormat::RED.'不正な入力データです');
				return;
			}
			$target = Account::findByName($data['account']);
			$money = Economy::getInstance($data['currency']);

			if(!$money instanceof Money){
				$player->sendMessage(TextFormat::RED.'通貨インスタンスを生成できませんでした');
				return;
			}
			$player->sendForm(
				new InputAmountForm(
					1000_0000,
					function(int $amount) use($target, $money):void{
						$money->reduceMoney($target, $amount);
					}
				)
			);
		};
		return $form;
	}

	public static function getSetForm():CustomForm{
		$form = new CustomForm;
		$form->addDropdown('account', '対象', 0, ...Account::getAllAccountNames());
		$form->addDropdown('currency', '種類', 0, ...Economy::getAllMoneyNames());
		$form->submit = function(Player $player, array $data):void{
			if(!isset($data['account']) or !isset($data['currency'])){
				$player->sendMessage(TextFormat::RED.'不正な入力データです');
				return;
			}
			$target = Account::findByName($data['account']);
			$money = Economy::getInstance($data['currency']);

			if(!$money instanceof Money){
				$player->sendMessage(TextFormat::RED.'通貨インスタンスを生成できませんでした');
				return;
			}
			$player->sendForm(
				new InputAmountForm(
					1000_0000,
					function(int $amount) use($target, $money):void{
						$money->setMoney($target, $amount);
					}
				)
			);
		};
		return $form;
	}
}
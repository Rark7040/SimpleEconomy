<?php
declare(strict_types = 1);

namespace rark\simple_economy\form;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use rark\simple_economy\Account;
use rark\simple_economy\Economy;
use rark\simple_economy\form\api\CustomForm;
use rark\simple_economy\form\api\SimpleForm;
use rark\simple_economy\Money;

class MoneyForms{

	public static function getAddForm():CustomForm{
		$form = new CustomForm;
		$form->addDropdown('account', '対象', 0, ...Account::getAllAccountNames());
		$form->addDropdown('currency', '種類', 0, ...Economy::getAllMoneyNames());
		$form->submit = function(Player $player, array $data):void{
			if(!isset($data['account']) or !isset($data['currency'])){
				$player->sendMessage(TextFormat::RED.'不正な入力データです');
				return;
			}
			$name = Account::getAllAccountNames()[$data['account']];
			$target = Account::findByName($name)?? new Account($name);
			$money = Economy::getInstance(Economy::getAllMoneyNames()[$data['currency']]);

			if(!$money instanceof Money){
				$player->sendMessage(TextFormat::RED.'通貨インスタンスを生成できませんでした');
				return;
			}
			$player->sendForm(
				new InputAmountForm(
					$money,
					null,
					function(int $amount) use($target, $money):void{
						$money->addMoney($target, $amount);
					}
				)
			);
		};
		return $form;
	}

	public static function getGiveForm():CustomForm{
		$form = new CustomForm;
		$form->addDropdown('account', '対象', 0, ...Account::getAllAccountNames());
		$form->addDropdown('currency', '種類', 0, ...Economy::getAllMoneyNames());
		$form->submit = function(Player $player, array $data):void{
			if(!isset($data['account']) or !isset($data['currency'])){
				$player->sendMessage(TextFormat::RED.'不正な入力データです');
				return;
			}
			$account = Account::findByName($player->getName());
			$name = Account::getAllAccountNames()[$data['account']];
			$target = Account::findByName($name)?? new Account($name);
			$money = Economy::getInstance(Economy::getAllMoneyNames()[$data['currency']]);

			if(!$money instanceof Money){
				$player->sendMessage(TextFormat::RED.'通貨インスタンスを生成できませんでした');
				return;
			}
			$player->sendForm(
				new InputAmountForm(
					$money,
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
			$name = Account::getAllAccountNames()[$data['account']];
			$target = Account::findByName($name)?? new Account($name);
			$money = Economy::getInstance(Economy::getAllMoneyNames()[$data['currency']]);

			if(!$money instanceof Money){
				$player->sendMessage(TextFormat::RED.'通貨インスタンスを生成できませんでした');
				return;
			}
			$player->sendForm(
				new InputAmountForm(
					$money,
					null,
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
			$name = Account::getAllAccountNames()[$data['account']];
			$target = Account::findByName($name)?? new Account($name);
			$money = Economy::getInstance(Economy::getAllMoneyNames()[$data['currency']]);

			if(!$money instanceof Money){
				$player->sendMessage(TextFormat::RED.'通貨インスタンスを生成できませんでした');
				return;
			}
			$player->sendForm(
				new InputAmountForm(
					$money,
					null,
					function(int $amount) use($target, $money):void{
						$money->setMoney($target, $amount);
					}
				)
			);
		};
		return $form;
	}

	public static function getViewForm():CustomForm{
		$form = new CustomForm;
		$form->addDropdown('account', '対象', 0, ...Account::getAllAccountNames());
		$form->submit = function(Player $player, array $data):void{
			if(!isset($data['account'])){
				$player->sendMessage(TextFormat::RED.'不正な入力データです');
				return;
			}
			$target_name = Account::getAllAccountNames()[$data['account']];
			$target = Account::findByName($target_name)?? new Account($target_name);
			$view_form = new SimpleForm;
			
			foreach(Economy::getAllMoneyNames() as $name){
				$money = Economy::getInstance($name);

				if(!$money instanceof Money) continue;
				$view_form->label .= $money->getFormatted($money->getMoney($target)).PHP_EOL.PHP_EOL;
			}
			$view_form->addButton('close');
			$player->sendForm($view_form);
		};
		return $form;
	}

	public static function getRankingForm():CustomForm{
		$form = new CustomForm;
		$form->addDropdown('currency', '種類', 0, ...Economy::getAllMoneyNames());
		$form->addToggle('is_total', '所持金額 | 総取得金額', false);
		$form->submit = function(Player $player, array $data):void{
			if(!isset($data['currency']) or !isset($data['is_total'])){
				$player->sendMessage(TextFormat::RED.'不正な入力データです');
				return;
			}
			$money = Economy::getInstance(Economy::getAllMoneyNames()[$data['currency']]);

			if(!$money instanceof Money){
				$player->sendMessage(TextFormat::RED.'通貨インスタンスを生成できませんでした');
				return;
			}
			$ranking = new SimpleForm;
			$ranking->label = $money->getRanking((bool) $data['is_total'])->__toString();
			$ranking->addButton('close');
			$player->sendForm($ranking);
		};
		return $form;
	}
}
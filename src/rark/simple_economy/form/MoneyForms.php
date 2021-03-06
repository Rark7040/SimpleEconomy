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
			$target = Account::findByName(Account::getAllAccountNames()[$data['account']]);
			$money = Economy::getInstance(Economy::getAllMoneyNames()[$data['currency']]);

			if($target === null){
				$player->sendMessage(TextFormat::RED.'対象のaccountを取得できませんでした');
				return;
			}
			if(!$money instanceof Money){
				$player->sendMessage(TextFormat::RED.'通貨インスタンスを生成できませんでした');
				return;
			}
			$player->sendForm(
				new InputAmountForm(
					$money,
					null,
					function(Player $player, int $amount) use($target, $money):void{
						if($money->addMoney($target, $amount)){
							$player->sendMessage(TextFormat::GREEN.$target->getName().'に'.$money->getFormatted($amount).'を渡しました');
			
						}else{
							$player->sendMessage(TextFormat::RED.$target->getName().'はこれ以上お金を持てません！');
						}
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
			$target = Account::findByName(Account::getAllAccountNames()[$data['account']]);
			$money = Economy::getInstance(Economy::getAllMoneyNames()[$data['currency']]);

			if($target === null){
				$player->sendMessage(TextFormat::RED.'対象のaccountを取得できませんでした');
				return;
			}
			if(!$money instanceof Money){
				$player->sendMessage(TextFormat::RED.'通貨インスタンスを生成できませんでした');
				return;
			}
			$player->sendForm(
				new InputAmountForm(
					$money,
					$money->getMoney($account),
					function(Player $player, int $amount) use($account, $target, $money):void{
						if($money->giveMoney($account, $target, $amount)){
							$player->sendMessage(TextFormat::GREEN.$target->getName().'に'.$money->getFormatted($amount).'を渡しました');
			
						}else{
							$player->sendMessage(TextFormat::RED.$target->getName().'はこれ以上お金を持てないか、あなたの所持金が不足しています');
						}
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
			$target = Account::findByName(Account::getAllAccountNames()[$data['account']]);
			$money = Economy::getInstance(Economy::getAllMoneyNames()[$data['currency']]);

			if($target === null){
				$player->sendMessage(TextFormat::RED.'対象のaccountを取得できませんでした');
				return;
			}
			if(!$money instanceof Money){
				$player->sendMessage(TextFormat::RED.'通貨インスタンスを生成できませんでした');
				return;
			}
			$player->sendForm(
				new InputAmountForm(
					$money,
					null,
					function(Player $player, int $amount) use($target, $money):void{
						if($money->reduceMoney($target, $amount)){
							$player->sendMessage(TextFormat::GREEN.$target->getName().'から'.$money->getFormatted($amount).'を取り上げました');
			
						}else{
							$player->sendMessage(TextFormat::RED.$target->getName().'からはこれ以上お金を取り上げれません！');
						}
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
			$target = Account::findByName(Account::getAllAccountNames()[$data['account']]);
			$money = Economy::getInstance(Economy::getAllMoneyNames()[$data['currency']]);

			if($target === null){
				$player->sendMessage(TextFormat::RED.'対象のaccountを取得できませんでした');
				return;
			}
			if(!$money instanceof Money){
				$player->sendMessage(TextFormat::RED.'通貨インスタンスを生成できませんでした');
				return;
			}
			$player->sendForm(
				new InputAmountForm(
					$money,
					null,
					function(Player $player, int $amount) use($target, $money):void{
						$money->setMoney($target, $amount);
						$player->sendMessage(TextFormat::GREEN.$target->getName().'の所持金を'.$money->getFormatted($amount).'に設定しました');
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
			$target = Account::findByName(Account::getAllAccountNames()[$data['account']]);
			
			if($target === null){
				$player->sendMessage(TextFormat::RED.'対象のaccountを取得できませんでした');
				return;
			}
			$player->sendForm(self::createViewForm($target));
		};
		return $form;
	}

	public static function createViewForm(Account $target):SimpleForm{
		$view_form = new SimpleForm;
		$view_form->label = TextFormat::BOLD.TextFormat::AQUA.'【'.TextFormat::WHITE.$target->getName().'さんの所持金一覧'.TextFormat::AQUA.'】'.PHP_EOL.PHP_EOL;

		foreach(Economy::getAllMoneyNames() as $name){
			$money = Economy::getInstance($name);

			if(!$money instanceof Money) continue;
			$view_form->label .= TextFormat::YELLOW.'- '.$money->getFormatted($money->getMoney($target)).PHP_EOL;
		}
		$view_form->addButton('close');
		return $view_form;
	}
}
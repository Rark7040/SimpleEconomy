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

	protected static CustomForm $add_form;
	protected static CustomForm $give_form;
	protected static CustomForm $ranking_select_form;
	protected static CustomForm $reduce_form;
	protected static CustomForm $set_form;
	protected static CustomForm $view_select_form;

	public static function init():void{
		self::$add_form = new CustomForm;
		self::$add_form->addDropdown('account', '対象', 0, ...Account::getAllAccountNames());
		self::$add_form->addDropdown('currency', '種類', 0, ...Economy::getAllMoneyNames());
		self::$add_form->submit = function(Player $player, array $data):void{
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
		
	}
}
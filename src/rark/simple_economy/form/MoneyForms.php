<?php
declare(strict_types = 1);

namespace rark\simple_economy\form;

use rark\simple_economy\Account;
use rark\simple_economy\Economy;
use rark\simple_economy\form\api\CustomForm;
use rark\simple_economy\form\api\SimpleForm;

class MoneyForms{

	protected static CustomForm $add_form;
	protected static CustomForm $give_form;
	protected static CustomForm $ranking_select_form;
	protected static CustomForm $reduce_form;
	protected static CustomForm $set_form;
	protected static CustomForm $view_select_form;

	public static function init():void{
		self::$add_form = new CustomForm;
		self::$add_form->addDropdown('accounts', '対象', 0, ...Account::getAllAccountNames());
		self::$add_form->addDropdown('currency', '種類', 0, ...Economy::getAllMoneyNames());
		//self::$add_form->addStepSlider('amount', '金額', 0, ...($steps = self::getValidArray()));


	}
}
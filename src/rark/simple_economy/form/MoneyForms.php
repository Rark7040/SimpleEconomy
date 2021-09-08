<?php
declare(strict_types = 1);

namespace rark\simple_economy\form;

use rark\simple_economy\form\api\FormPool;

class MoneyForms extends FormPool{

	public static function init():void{

	}

	public function handleInteger(string $input):?int{
		return is_numeric($input)? intval($input): null;
	}
}
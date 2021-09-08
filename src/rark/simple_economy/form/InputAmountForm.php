<?php
declare(strict_types = 1);

namespace rark\simple_economy\form;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use rark\simple_economy\Account;
use rark\simple_economy\form\api\CustomForm;
use rark\simple_economy\Money;

class InputAmountForm extends CustomForm{
	/**
	 * fucntion(int $amount):void;
	 * 
	 * @var callable
	 */
	protected callable $func;

	public function __construct(int $max_amount, callable $func){
		$this->addStepSlider('amount', '金額', 0, ...($steps = self::getValidArray($max_amount)));
		$this->func = $func;
		$this->submit = function(Player $player, array $data) use($steps):void{
			if(!isset($data['amount'])){
				$player->sendMessage(TextFormat::RED.'金額が入力されていません。');
				return;
			}
			$result = self::handleInteger($steps[$data['amount']]);

			if($result === null){
				$player->sendMessage(TextFormat::RED.'有効な値ではありません。半角英数字で入力してください。');
				return;
			}
			($this->func)($result);
		};
	}

	/**
	 * StepSliderで使用可能な形式の配列を返します
	 * 
	 * @param integer $steps
	 * @return string[]
	 */
	public static function getValidArray(int $amount):array{
		$steps = [];

		for($count = 100; $count <= $amount; $count+=100){
			$steps[] = $count;
		}
		
		if(!isset($steps[0])){
			$steps[] = 0;

		}else{
			$last_offset = count($steps)-1;

			if($steps[$last_offset] > $amount){
				unset($steps[$last_offset]);
			}
		}
		return array_map('strval', $steps);
	}

	public static function handleInteger(string $input):?int{
		return is_numeric($input)? intval($input): null;
	}
}
<?php
declare(strict_types = 1);

namespace rark\simple_economy\form;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use rark\simple_economy\form\api\CustomForm;
use rark\simple_economy\form\api\ModalForm;
use rark\simple_economy\Money;

class InputAmountForm extends CustomForm{

	public function __construct(Money $money, int $amount, callable $func){
		$this->addStepSlider('amount1', '万', 0, ...self::getValidArray((int) ($steps = floor($amount/1_0000)), 1));
		$this->addStepSlider('amount2', '千', 0, ...self::getValidArray((int) ($steps2 = floor(($amount-1_0000*$steps)/1000)), 1));
		$this->addStepSlider('amount3', '百', 0, ...self::getValidArray((int) (floor(($amount-(1_0000*$steps+1000*$steps2))/100)), 1));
		$this->func = $func;
		$this->submit = function(Player $player, array $data) use($money, $amount, $func):void{
			if(!isset($data['amount'])){
				$player->sendMessage(TextFormat::RED.'金額が入力されていません。');
				return;
			}
			$result = $data['amount1']*1_0000+$data['amount2']*1000+$data['amount3']*100;
			
			if($result > $amount){
				$player->sendMessage(TextFormat::RED.'エラーが発生しました 101');
				return;
			}
			$warning = new ModalForm('続行', '閉じる');
			$warning->label = '現在選択している金額は、'.TextFormat::YELLOW.$money->getFormatted($result).TextFormat::RESET.'です。'.PHP_EOL.'処理を続行しますか?';
			$warning->submit = function(Player $player, bool $data) use($result, $func):void{
				if($data) ($func)($result);
			};
		};
	}

	/**
	 * StepSliderで使用可能な形式の配列を返します
	 * 
	 * @param integer $steps
	 * @return string[]
	 */
	public static function getValidArray(int $amount, int $digit = 100):array{
		if($amount < $digit) $amount = 0;
		return array_map('strval', range(0, $amount, $digit));
	}
}
<?php
declare(strict_types = 1);

namespace rark\simple_economy\command\sub;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use rark\simple_economy\Account;
use rark\simple_economy\Economy;
use rark\simple_economy\form\MoneyForms;
use rark\simple_economy\libs\CortexPE\Commando\args\IntegerArgument;
use rark\simple_economy\libs\CortexPE\Commando\args\RawStringArgument;
use rark\simple_economy\libs\CortexPE\Commando\BaseSubCommand;

class SetSubCommand extends BaseSubCommand{

	public function __construct(){
		parent::__construct('set', '所持金を任意の値に設定します');
		$this->setPermission('simple_economy.command.op');
	}

	protected function prepare():void{
		$this->registerArgument(0, new RawStringArgument('money', true));
		$this->registerArgument(1, new RawStringArgument('account', true));
		$this->registerArgument(2, new IntegerArgument('amount', true));
	}

	public function onRun(CommandSender $sender, string $label, array $args):void{
		if(!$sender instanceof Player){
			$sender->sendMessage(TextFormat::RED.'ゲーム内で実行してください');
			return;
		}

		if(!Server::getInstance()->isOp($sender->getName())){
			$sender->sendMessage(TextFormat::RED.'実行権限がありません');
			return;
		}
		if(isset($args['money']) and isset($args['account']) and isset($args['amount'])){
			$money = Economy::getInstance((string) $args['money']);
			$account = Account::findByName((string) $args['account']);
			$amount = (int) $args['amount'];

			if($money === null or $account === null or $amount < 0){
				$sender->sendMessage(TextFormat::RED.'不正な値です');
				return;
			}
			$money->setMoney($account, $amount);
			$sender->sendMessage(TextFormat::GREEN.$account->getName().'の所持金を'.$money->getFormatted($amount).'に設定しました');
			return;
		}
		$sender->sendForm(MoneyForms::getSetForm());
	}
}
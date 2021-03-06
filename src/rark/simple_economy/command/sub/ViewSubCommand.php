<?php
declare(strict_types = 1);

namespace rark\simple_economy\command\sub;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use rark\simple_economy\Account;
use rark\simple_economy\form\MoneyForms;
use rark\simple_economy\libs\CortexPE\Commando\args\RawStringArgument;
use rark\simple_economy\libs\CortexPE\Commando\BaseSubCommand;

class ViewSubCommand extends BaseSubCommand{

	public function __construct(){
		parent::__construct('view', '他の人の所持金を確認する');
		$this->setPermission('simple_economy.command.public');
	}

	protected function prepare():void{
		$this->registerArgument(0, new RawStringArgument('account', true));
	}

	public function onRun(CommandSender $sender, string $label, array $args):void{
		if(!$sender instanceof Player){
			$sender->sendMessage(TextFormat::RED.'ゲーム内で実行してください');
			return;
		}
		if(isset($args['account'])){
			$account = Account::findByName($args['account']);;
			
			if($account === null){
				$sender->sendMessage(TextFormat::RED.'不正な値です');
				return;
			}
			$sender->sendForm(MoneyForms::createViewForm($account));
			return;
		}
		$sender->sendForm(MoneyForms::getViewForm());
	}
}
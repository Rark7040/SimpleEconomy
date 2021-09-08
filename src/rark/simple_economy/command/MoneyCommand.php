<?php
declare(strict_types = 1);

namespace rark\simple_economy\command;

use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use rark\simple_economy\libs\CortexPE\Commando\BaseCommand;

class MoneyCommand extends BaseCommand{

	public function __construct(PluginBase $plugin){
		parent::__construct($plugin, 'money', 'お金に関するあれこれ', ['m']);
	}

	protected function prepare():void{}

	public function onRun(CommandSender $sender, string $label, array $args):void{
		$usage = <<<USAGE
			[Usage]
			/money give <string:name> <int:amount>
			/money ranking
			/money View <string:name>
		USAGE;
		
		if(Server::getInstance()->isOp($sender->getName()) or $sender instanceof ConsoleCommandSender){
			$usage .= <<<ADD
				/money set <string:name> <int:amount>
				/money add <string:name> <int:amount>
				/money reduce <string:name> <int:amount>
			ADD;
		}
		$sender->sendMessage($usage);
	}
}
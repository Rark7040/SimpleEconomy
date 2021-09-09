<?php
declare(strict_types = 1);

namespace rark\simple_economy;

use pocketmine\Server;
use pocketmine\utils\Config;
use Stringable;

class Ranking extends Config implements Stringable{

	const KEY_VALID = 'valid_ranking';
	const KEY_BEFORE = 'before_ranking';

	protected ?string $str_ranking = null;
	protected Money $money;

	public function __construct(Money $money, bool $is_total = false){
		parent::__construct(Main::getPluginDataPath().$money->getName().'_Ranking'.(int)$is_total.'.json', Config::JSON);
		$this->money = $money;
	}

	public function upload(array $sorted_player_data):void{
		$before = array_flip(array_keys($this->get(self::KEY_BEFORE, [])));
		$after = array_flip(array_keys($sorted_player_data));
		$players_data = $this->get(self::KEY_VALID,  []);
		$data = [];

		foreach($after as $name => $rank){
			$dat = [$rank, $name, $sorted_player_data[$name]];
			$before_rank = isset($before[$name])? $before[$name]: -1;
			match(true){
				$before_rank === -1, $before_rank === $rank => $dat[] = 1,
				$before_rank > $rank => $dat[] = 0,
				$before_rank < $rank => $dat[] = 2
			};
			$players_data[$name] = $dat;
			$data[] = $dat;
		}
		$this->set(self::KEY_VALID, $players_data);
		$this->updateRankingText($data);
		$this->set(self::KEY_BEFORE, array_combine(array_keys($after), $data));
	}

	/**
	 * @param array $data ...[int:$rank, string:$player_name, int: $kills, int:$condition]
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	public function updateRankingText(array $data):void{
		if(isset($data[0])) return;
		$this->str_ranking = '';
		$conditions = ['§l§a⇧§r', '§l§7-§r', '§l§c⇩§r'];
        $colors = ['§l§e', '§l§7', '§l§6', '§l§f', '§l§f', '§l§f', '§l§f', '§l§f', '§l§f', '§l§f'];
		$server = Server::getInstance();

		for($i = 0; $i !== 10; ++$i){
			if(!isset($data[$i])) break;
			if($server->isOp((string) $data[1])){
				--$i;
				continue;
			}
            $color = $colors[$i];
			$dat = $data[$i];
			
			if(count($dat) !== 4) throw new \InvalidArgumentException('please refer toRankingText.php at line 12');
			++$dat[0];
			$this->str_ranking .= "$color 【{$dat[0]}位】§r§  {$dat[1]}  §b{$this->money->getFormatted($dat[2])}§r[{$conditions[$dat[3]]}] \n";
		}
	}

	public function __toString():string{
		return $this->str_ranking?? '';
	}
}
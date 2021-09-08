<?php
declare(strict_types = 1);

namespace rark\simple_economy;

use Countable;
use SplFixedArray;
use Stringable;

class Ranking extends SplFixedArray implements Stringable{

	public function __construct(Countable|int $scale = 10){
		if($scale instanceof Countable){
			$scale = count($scale);
		}
		parent::__construct($scale);
	}

	public function __toString():string{
		return ''; //TODO
	}
}
<?php
declare(strict_types = 1);

	/**
	 * StepSliderで使用可能な形式の配列を返します
	 * 
	 * @param integer $steps
	 * @return string[]
	 */
function getValidArray(int $amount):array{
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

$ar = getValidArray(10000);
echo $ar[0];

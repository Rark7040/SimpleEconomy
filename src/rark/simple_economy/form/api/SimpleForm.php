<?php

declare(strict_types = 1);

namespace rark\simple_economy\form\api;


class SimpleForm extends BaseForm{

	public function addButton(string $text, ?string $image = null){
		$button = ['text' => $text];

		if($image !== null){
			$button['image'] = [
				'type' => 'path',
				'data' => $image
			];
		}
		$this->contents[] = $button;
	}
}

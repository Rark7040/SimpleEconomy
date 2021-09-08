<?php

declare(strict_types = 1);

namespace rark\simple_economy\form\api;


class ModalForm extends BaseForm{

	public function __construct(string $text1, string $text2){
		$this->contents[] = $text1;
		$this->contents[] = $text2;
	}
}
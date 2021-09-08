<?php
declare(strict_types = 1);


class A{
  function getA(){
    return 'a';
  }
}

$a = null;
var_dump($a?->getA());
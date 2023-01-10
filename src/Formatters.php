<?php

namespace Hexlet\Code\Formatters;

use function Hexlet\Code\Formatters\Stylish\stylish;

function render($ast, $format)
{
    switch ($format) {
      case 'stylish':
        return stylish($ast);
      default:
        return stylish($ast);
    }
}
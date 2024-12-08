<?php

declare(strict_types=1);

namespace Hexlet\Code\Formatters;

use function Hexlet\Code\Formatters\Stylish\stylish;
use function Hexlet\Code\Formatters\Plain\plain;

function render(array $ast, string $format): mixed
{
    switch ($format) {
        case 'stylish':
            return stylish($ast);
        case 'plain':
            return plain($ast);
        case 'json':
            return json_encode($ast);
        default:
            return stylish($ast);
    }
}

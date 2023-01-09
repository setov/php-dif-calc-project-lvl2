<?php

namespace Hexlet\Code\GenDiff;

use function Hexlet\Code\Parsers\parse;
use function Hexlet\Code\AstData\genAst;
use function Hexlet\Code\Stylish\stylish;

function genDiff($fileName1, $fileName2, $format)
{
    $data1 = parse($fileName1);
    $data2 = parse($fileName2);

    $ast = genAst($data1, $data2);
    switch ($format) {
        case 'stylish':
            return stylish($ast);
        default:
            return stylish($ast);
    }
}

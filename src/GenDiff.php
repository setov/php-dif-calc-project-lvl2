<?php

namespace Hexlet\Code\GenDiff;

use function Hexlet\Code\Utils\getFileContents;
use function Hexlet\Code\AstData\genAst;
use function Hexlet\Code\Stylish\stringifyAst;

function genDiff($fileName1, $fileName2)
{
    $string1 = getFileContents($fileName1);
    $string2 = getFileContents($fileName2);

    $data1 = json_decode($string1, false, 512, JSON_THROW_ON_ERROR);
    $data2 = json_decode($string2, false, 512, JSON_THROW_ON_ERROR);

    $ast = genAst($data1, $data2);
    return stringifyAst($ast);
}

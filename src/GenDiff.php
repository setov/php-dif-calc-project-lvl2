<?php

namespace Hexlet\Code\GenDiff;

use function Hexlet\Code\Parsers\parse;
use function Hexlet\Code\AstData\genAst;
use function Hexlet\Code\Formatters\render;

function genDiff($fileName1, $fileName2, $format)
{
    $data1 = parse($fileName1);
    $data2 = parse($fileName2);

    $ast = genAst($data1, $data2);
    return render($ast, $format);
}

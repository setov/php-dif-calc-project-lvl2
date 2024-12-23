<?php

declare(strict_types=1);

namespace Differ\Differ;

use function Hexlet\Code\Parsers\parse;
use function Hexlet\Code\AstData\genAst;
use function Hexlet\Code\Formatters\render;

function genDiff(string $fileName1, string $fileName2, string $format = 'stylish'): mixed
{
    $data1 = parse($fileName1);
    $data2 = parse($fileName2);

    $ast = genAst($data1, $data2);
    return render($ast, $format);
}

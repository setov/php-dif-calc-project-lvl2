<?php

namespace Hexlet\Code\Parsers;

use Symfony\Component\Yaml\Yaml;

use function Hexlet\Code\Utils\getFileContents;
use function Hexlet\Code\Utils\getFileType;

function parse($fileName)
{
    $parsers = [
    'json' => fn ($content) => json_decode($content, false, 512, JSON_THROW_ON_ERROR),
    'yaml' => fn ($content) => Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP),
    'yml' => fn ($content) => Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP)
    ];
    $content = getFileContents($fileName);
    $type = getFileType($fileName);
    $parser = $parsers[$type] ??  throw new \Exception("Unknown extension file {$fileName}\n", 200);
    return $parser($content);
}

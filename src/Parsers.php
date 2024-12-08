<?php

namespace Hexlet\Code\Parsers;

use Symfony\Component\Yaml\Yaml;

use function Hexlet\Code\Utils\getFileContents;
use function Hexlet\Code\Utils\getFileType;

function parse(string $fileName): array
{
    $parsers = [
    'json' => fn ($content) => json_decode($content, false, 512, JSON_THROW_ON_ERROR),
    'yaml' => fn ($content) => Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP),
    'yml' => fn ($content) => Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP)
    ];

    $content = getFileContents($fileName);

    $type = getFileType($fileName);
    $data = $parsers[$type]($content);

    $arrayData = json_decode((string)json_encode($data), true);

    return $arrayData;
}

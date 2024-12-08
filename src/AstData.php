<?php

declare(strict_types=1);

namespace Hexlet\Code\AstData;

use function Functional\sort;

/**
 * Represents a node in the tree
 * @param string $type Property specifies the type of the node.
 * @param string $name
 * @param mixed $valueBefore
 * @param mixed $valueAfter
 * @param array $children The children property contains an array of child-nodes.
 * @return array
 */
function makeNode(
    string $type,
    string $name,
    mixed $valueBefore = null,
    mixed $valueAfter = null,
    array $children = []
): array {
    return [
        'type' => $type,
        'name' => $name,
        'valueBefore' => $valueBefore,
        'valueAfter' => $valueAfter,
        'children' => $children,
    ];
}

function unionKeys(array $data1, array $data2): array
{
    $firstKeys = array_keys($data1);
    $secondKeys = array_keys($data2);

    return array_values(
        array_unique(
            array_merge($firstKeys, $secondKeys)
        )
    );
}

/**
 * The abstract syntax tree returned by these functions consists of Node arrays.
 * The abstract syntax tree is composed of nodes
 * @param array $firstData
 * @param array $secondData
 * @return array
 */
function genAst(array $firstData, array $secondData): array
{
    $keys = unionKeys($firstData, $secondData);

    $sortedKeys = sort($keys, fn($a, $b) => $a <=> $b);

    $ast = array_reduce(
        $sortedKeys,
        function ($acc, $key) use ($firstData, $secondData) {
            if (!array_key_exists($key, $firstData)) {
                return [...$acc, makeNode('added', $key, $secondData[$key], $secondData[$key])];
            }
            if (!array_key_exists($key, $secondData)) {
                return [...$acc, makeNode('removed', $key, $firstData[$key], $firstData[$key])];
            }
            if (is_array($firstData[$key]) && is_array($secondData[$key])) {
                return [...$acc, makeNode('complex', $key, null, null, genAst($firstData[$key], $secondData[$key]))];
            }
            if ($firstData[$key] === $secondData[$key]) {
                return [...$acc, makeNode('unchanged', $key, $firstData[$key], $firstData[$key])];
            }
            if ($firstData[$key] !== $secondData[$key]) {
                return [...$acc, makeNode('updated', $key, $firstData[$key], $secondData[$key])];
            }
            return $acc;
        },
        []
    );

    $value = (bool) json_encode($ast);
    $stringifyAst = $value ? json_encode($ast) : '';
    $normalizeAst  = json_decode((string) $stringifyAst, true);
    return $normalizeAst;
}

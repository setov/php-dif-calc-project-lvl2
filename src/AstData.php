<?php

namespace Hexlet\Code\AstData;

function toString($value): string
{
    if (is_null($value)) {
        return "null";
    }
    return trim((var_export($value, true)), "'");
}

function normalize($data)
{
    if (!is_array($data)) {
        return toString($data);
    }
    $array = [];

    foreach ($data as $key => $val) {
        $array[$key] = is_array($val) ? normalize($val) : toString($val);
    }
    return $array;
}
/**
 * represents a node in the tree
 * @param mixed $type property specifies the type of the node.
 * @param mixed $name
 * @param mixed $valueBefore
 * @param mixed $valueAfter
 * @param mixed $children The children property contains an array of child-nodes.
 * @return array
 */
function makeNode(
    $type,
    $name,
    $valueBefore = null,
    $valueAfter = null,
    $children = null
) {
    return [
        'type' => $type,
        'name' => $name,
        'valueBefore' => $valueBefore,
        'valueAfter' => $valueAfter,
        'children' => $children,
    ];
}

function getNodeType($node)
{
    return $node['type'];
}

function hasChildren(array $node): bool
{
    return getNodeType($node) === 'complex';
}

function union(object $data1, object $data2): array
{
    $firstKeys = array_keys(get_object_vars($data1));
    $secondKeys = array_keys(get_object_vars($data2));

    return array_values(
        array_unique(
            array_merge($firstKeys, $secondKeys)
        )
    );
}
/**
 * The abstract syntax tree returned by these function consists of Node objects.
 * The abstract syntax tree is composed of nodes
 * @param object $firstData
 * @param object $secondData
 * @return array
 */
function genAst(object $firstData, object $secondData): array
{
    $unionKeys = union($firstData, $secondData);

    usort($unionKeys, fn($a, $b) => $a <=> $b);

    $ast = array_reduce(
        $unionKeys,
        function ($acc, $key) use ($firstData, $secondData) {
            if (!property_exists($firstData, $key)) {
                return [...$acc, makeNode('added', $key, $secondData->$key, $secondData->$key)];
            }
            if (!property_exists($secondData, $key)) {
                return [...$acc, makeNode('removed', $key, $firstData->$key, $firstData->$key)];
            }
            if (is_object($firstData->$key) && is_object($secondData->$key)) {
                return [...$acc, makeNode('complex', $key, null, null, genAst($firstData->$key, $secondData->$key))];
            }
            if ($firstData->$key === $secondData->$key) {
                return [...$acc, makeNode('unchanged', $key, $firstData->$key, $firstData->$key)];
            }
            if ($firstData->$key !== $secondData->$key) {
                return [...$acc, makeNode('updated', $key, $firstData->$key, $secondData->$key)];
            }
        },
        []
    );
    return normalizeAstData($ast);
}


function objectToArray(mixed $obj): array
{
    $array = [];
    foreach ($obj as $key => $val) {
        $array[$key] = (is_array($val) || is_object($val)) ?
        objectToArray($val) : $val;
    }
    return $array;
}

function normalizeAstData(array $nodes)
{
    $values = objectToArray($nodes);
    return array_map(
        function ($node) {
            [
                'name' => $name,
                'type' => $type,
                'valueBefore' => $valueBefore,
                'valueAfter' => $valueAfter,
                'children' => $children
                ] = $node;
                $valueBefore = normalize($valueBefore);
                $valueAfter = normalize($valueAfter);
            if (hasChildren($node)) {
                return makeNode(
                    $type,
                    $name,
                    $valueBefore,
                    $valueAfter,
                    normalizeAstData($children)
                );
            }
            return makeNode(
                $type,
                $name,
                $valueBefore,
                $valueAfter,
                $children
            );
        },
        $values
    );
}

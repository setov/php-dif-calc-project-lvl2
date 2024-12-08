<?php

declare(strict_types=1);

namespace Hexlet\Code\Formatters\Plain;

function toString(mixed $value): string
{
    if (is_null($value)) {
        return "null";
    }
    return trim((var_export($value, true)), "'");
}

function stringifyValue(mixed $value): string
{
    if (is_array($value)) {
        return "[complex value]";
    }
    if (is_string($value)) {
        return "'{$value}'";
    }
    return toString($value);
}

function stringifyByNodeType(mixed $node, string $ancestor, callable $fun): string
{
    [
        'name' => $name,
        'type' => $type,
        'valueBefore' => $valueBefore,
        'valueAfter' => $valueAfter,
        'children' => $children
    ] = $node;
        $newAncestor = $ancestor === '' ? "{$name}" : "{$ancestor}.{$name}";
        $beforeValue = stringifyValue($valueBefore);
        $afterValue = stringifyValue($valueAfter);
    switch ($type) {
        case 'unchanged':
            return '';
        case 'updated':
            return "Property '{$newAncestor}' was updated. From {$beforeValue} to {$afterValue}";
        case 'removed':
            return "Property '{$newAncestor}' was removed";
        case 'added':
            return "Property '{$newAncestor}' was added with value: {$afterValue}";
        case 'complex':
            return $fun($children, $newAncestor);
        default:
            throw new \Exception("unexpected type {$type}");
    }
}

function plain(array $ast): string
{
    $iter = function ($nodes, $ancestor) use (&$iter) {
        $lines = array_map(
            fn($node) => stringifyByNodeType($node, $ancestor, $iter),
            $nodes
        );
        $linesFiltered = array_filter(
            $lines,
            fn($node) => $node,
        );
        return implode("\n", $linesFiltered);
    };
    return $iter($ast, '');
}

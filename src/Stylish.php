<?php

namespace Hexlet\Code\Stylish;

const REPLACER = ' ';
const SPACES_COUNT = 2;
const STEP_BY_DEPTH = 2;

function toString($value): string
{
    if (is_null($value)) {
        return "null";
    }
    return trim((var_export($value, true)), "'");
}

function stringify($value, $depth)
{
    $iter = function ($currentValue, $depthIn) use (&$iter) {
        if (!is_array($currentValue) || is_null($currentValue)) {
            return toString($currentValue);
        }
        $indentSize = (int)($depthIn + SPACES_COUNT) * SPACES_COUNT;
        $currentIndent = str_repeat(REPLACER, $indentSize + SPACES_COUNT);
        $bracketIndent = str_repeat(REPLACER, $indentSize - SPACES_COUNT);

        $lines = array_map(
            fn($key, $val) =>
            "{$currentIndent}{$key}: {$iter($val, $depthIn + STEP_BY_DEPTH)}",
            array_keys($currentValue),
            $currentValue
        );
        $result = ['{', ...$lines, "{$bracketIndent}}"];
        return implode("\n", $result);
    };
    return $iter($value, $depth);
}

function stringByNodeType($node, $depth, $fun)
{
    $indentSize = $depth * SPACES_COUNT;
    $currentIndent = str_repeat(REPLACER, $indentSize);
    $complexIndent = str_repeat(REPLACER, $indentSize + SPACES_COUNT);
    [
        'name' => $name,
        'type' => $type,
        'valueBefore' => $valueBefore,
        'valueAfter' => $valueAfter,
        'children' => $children
        ] = $node;

    switch ($type) {
        case 'unchanged':
            $value = stringify($valueBefore, $depth);
            return "{$currentIndent}  {$name}: {$value}";
        case 'updated':
            $befor = stringify($valueBefore, $depth);
            $after = stringify($valueAfter, $depth);
            $line1 = "{$currentIndent}- {$name}: {$befor}";
            $line2 = "{$currentIndent}+ {$name}: {$after}";
            return "{$line1}\n{$line2}";
        case 'removed':
            $value = stringify($valueBefore, $depth);
            return "{$currentIndent}- {$name}: {$value}";
        case 'added':
            $value = stringify($valueAfter, $depth);
            return "{$currentIndent}+ {$name}: {$value}";
        case 'complex':
            $child = $fun($children, $depth + SPACES_COUNT);
            return "{$complexIndent}{$name}: {$child}";
        default:
            throw new \Exception("unexpected type: {$type}");
    }
}

function stylish($ast)
{
    $iter = function ($nodes, $depth) use (&$iter) {
        $indentSize = $depth * SPACES_COUNT;
        $bracketIndent = str_repeat(REPLACER, $indentSize - SPACES_COUNT);
        $lines = array_map(
            fn($node) => stringByNodeType($node, $depth, $iter),
            $nodes
        );
        $result = ["{", ...$lines, "{$bracketIndent}}"];
        return implode("\n", $result);
    };
    return $iter($ast, 1);
}

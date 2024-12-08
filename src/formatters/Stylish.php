<?php

declare(strict_types=1);

namespace Hexlet\Code\Formatters\Stylish;

class FormatterException extends \Exception
{
}

const REPLACER = ' ';
const SPACES_COUNT = 2;
const STEP_BY_DEPTH = 2;

function toString(mixed $value): string
{
    if (is_null($value)) {
        return "null";
    }
    return trim(var_export($value, true), "'");
}

/**
 * Преобразует значение в строку с отступами.
 */
function stringify(mixed $value, int $depth): string
{
    $iter = function ($currentValue, $depthIn) use (&$iter) {
        if (!is_array($currentValue)) {
            return toString($currentValue);
        }

        $indentSize = ($depthIn + SPACES_COUNT) * SPACES_COUNT;
        $currentIndent = str_repeat(REPLACER, $indentSize + SPACES_COUNT);
        $bracketIndent = str_repeat(REPLACER, $indentSize - SPACES_COUNT);

        $lines = array_map(
            fn($key, $val) => "{$currentIndent}{$key}: {$iter($val, $depthIn + STEP_BY_DEPTH)}",
            array_keys($currentValue),
            $currentValue
        );

        return implode("\n", ['{', ...$lines, "{$bracketIndent}}"]);
    };

    return $iter($value, $depth);
}

/**
 * Форматирует узел AST в строку в зависимости от его типа.
 */
function stringByNodeType(array $node, int $depth, callable $fun): string
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
            return "{$currentIndent}  {$name}: " . stringify($valueBefore, $depth);
        case 'updated':
            return formatUpdatedNode($currentIndent, $name, $valueBefore, $valueAfter, $depth);
        case 'removed':
            return formatNode($currentIndent, '-', $name, $valueBefore, $depth);
        case 'added':
            return formatNode($currentIndent, '+', $name, $valueAfter, $depth);
        case 'complex':
            $child = $fun($children, $depth + SPACES_COUNT);
            return "{$complexIndent}{$name}: {$child}";
        default:
            throw new FormatterException("Unexpected type: {$type}");
    }
}

/**
 * Форматирует узел с префиксом.
 */
function formatNode(string $indent, string $prefix, string $name, mixed $value, int $depth): string
{
    return "{$indent}{$prefix} {$name}: " . stringify($value, $depth);
}

/**
 * Форматирует обновленный узел.
 */
function formatUpdatedNode(string $indent, string $name, mixed $valueBefore, mixed $valueAfter, int $depth): string
{
    $before = stringify($valueBefore, $depth);
    $after = stringify($valueAfter, $depth);
    return "{$indent}- {$name}: {$before}\n{$indent}+ {$name}: {$after}";
}

/**
 * Основная функция для форматирования AST.
 */
function stylish(array $ast): string
{
    $iter = function ($nodes, $depth) use (&$iter) {
        $indentSize = $depth * SPACES_COUNT;
        $bracketIndent = str_repeat(REPLACER, $indentSize - SPACES_COUNT);

        $lines = array_map(
            fn($node) => stringByNodeType($node, $depth, $iter),
            $nodes
        );

        return implode("\n", ["{", ...$lines, "{$bracketIndent}}"]);
    };

    return $iter($ast, 1);
}

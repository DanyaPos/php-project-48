<?php

namespace Differ\Differ;

function genDiff($firstFile, $secondFile)
{
    $first = form($firstFile);
    $second = form($secondFile);
    $result = "{\n";

    $result .= generateCommonDifferences($first, $second);
    $result .= generateUniqueDifferences($first, $second);
    
    $result .= "}\n";
    return $result;
}

function form($str)
{
    $result = json_decode(file_get_contents($str), true);
    ksort($result);
    return $result;
}

function toString($value)
{
    if (is_bool($value)) {
        return var_export($value, true);  // Преобразуем true/false в строку
    }
    return $value;
}

function generateCommonDifferences(array $first, array $second): string
{
    $result = '';
    foreach ($first as $key => $value) {
        if (array_key_exists($key, $second)) {
            $result .= compareValues($key, $value, $second[$key]);
        } else {
            $result .= removeKey($key, $value);
        }
    }
    return $result;
}

function compareValues(string $key, $firstValue, $secondValue): string
{
    if ($firstValue === $secondValue) {
        return "    {$key} : " . toString($firstValue) . "\n";
    }
    return changeKey($key, $firstValue, $secondValue);
}

function changeKey(string $key, $firstValue, $secondValue): string
{
    $result = "  - {$key} : " . toString($firstValue) . "\n";
    $result .= "  + {$key} : " . toString($secondValue) . "\n";
    return $result;
}

function removeKey(string $key, $value): string
{
    return "  - {$key} : " . toString($value) . "\n";
}

function generateUniqueDifferences(array $first, array $second): string
{
    $result = '';
    foreach ($second as $key => $value) {
        if (!array_key_exists($key, $first)) {
            $result .= "  + {$key} : " . toString($value) . "\n";
        }
    }
    return $result;
}

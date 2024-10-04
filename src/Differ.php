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
        return var_export($value, true);  // Convert true/false to string
    }
    return $value;
}

function generateCommonDifferences(array $first, array $second): string
{
    $result = '';
    foreach ($first as $key => $value) {
        if (array_key_exists($key, $second)) {
            if ($value === $second[$key]) {
                $result .= "    {$key} : " . toString($value) . "\n";
            } else {
                $result .= "  - {$key} : " . toString($value) . "\n";
                $result .= "  + {$key} : " . toString($second[$key]) . "\n";
            }
        } else {
            $result .= "  - {$key} : " . toString($value) . "\n";
        }
    }
    return $result;
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

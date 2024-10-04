<?php

namespace Differ\Differ;

function genDiff($firstFile, $secondFile)
{
  $first = form($firstFile);
  $second = form($secondFile);
  $rezult = "{\n";
  foreach ($first as $key=>$value){
    if (array_key_exists($key, $second)){
      if ($value === $second[$key]){
        $rezult .= "    {$key} : ". toString($value) . "\n";
      } else {
        $rezult .= "  - {$key} : ". toString($value) . "\n";
        $rezult .= "  + {$key} : ". toString($second[$key]) . "\n";
      }
    } else {
      $rezult .= "  - {$key} : ". toString($value) . "\n";
    }
    }
    foreach ($second as $key => $value) {
      if (!array_key_exists($key, $first)) {
        $rezult .= "  + {$key} : ". toString($value) . "\n";
      }
  }
  $rezult .= "}\n";
  return $rezult;
};

function form($str)
{
  $rezult = json_decode(file_get_contents($str), $associative = true);
  ksort($rezult);
  return $rezult;
};


function toString($value)
{
    if (is_bool($value)) {
        return var_export($value, true);  // Преобразуем true/false в строку
    }
    return $value;
};
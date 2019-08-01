<?php

/**
* 二位数组排序
*
* author @孙涛 sunta0@outlook.com
*/

$data = array(
  array(
    'id' => 698,
    'first_name' => 'Bill',
    'last_name' => 'Gates',
  ),
  array(
    'id' => 4767,
    'first_name' => 'Steve',
    'last_name' => 'Aobs',
  ),
  array(
    'id' => 3809,
    'first_name' => 'Mark',
    'last_name' => 'Zuckerberg',
  )
);

//根据字段last_name对数组$data进行降序排列
$last_names = array_column($data,'id');
array_multisort($last_names,SORT_DESC,$data);

var_dump($data);
?>
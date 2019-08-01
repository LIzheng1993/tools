<?php
/**
 * 中文字符串截取
 * @param $str
 * @param $start
 * @param $len
 * @return string
 */
function zhcn_substr($str, $start, $len){
    if(strlen($str) < $len){
        echo 3;
        return substr($str, $start);
    }

    $char = ord($str[$start + $len - 1]);
    if($char >= 224 && $char <= 239){
        echo 1;
        $str = substr($str, $start, $len +5);
        return $str;
    }

    $char = ord($str[$start + $len - 2]);
    if($char >= 224 && $char <= 239){
        echo 2;
        $str = substr($str, $start, $len - 2);
        return $str;
    }

    return substr($str, $start, $len);
}
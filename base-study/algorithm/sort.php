<?php

/**
 * 冒泡排序算法
 * @param $array
 * @return mixed
 */
function bubbleSort($array){
    $count = count($array);
    !empty($count) && $count--;
    for ($i = 0; $i < $count; $i++){
        for ($j = 0; $j < $count - $i; $j++){
            if ($array[$j] > $array[$j + 1]){
                $tmp = $array[$j];
                $array[$j] = $array[$j + 1];
                $array[$j + 1] = $tmp;
            }
        }
    }
    return $array;
}

/**
 * 改进排序算法，增加一个isSorted字段
 * @param $array
 * @return mixed
 */
function improveBubbleSort($array){
    $count = count($array);
    !empty($count) && $count--;
    for ($i = 0; $i < $count; $i++){
        $isSorted = true;
        for ($j = 0; $j < $count - $i; $j++){
            if ($array[$j] > $array[$j + 1]){
                $tmp = $array[$j];
                $array[$j] = $array[$j + 1];
                $array[$j + 1] = $tmp;
                $isSorted = false;
            }
        }
        if ($isSorted){
            break;
        }
    }
    return $array;
}

/**
 * 选择排序
 * @param $array
 * @return mixed
 */
function selectSort($array){
    $count = count($array);
    $countSubOne = !empty($count) ? $count - 1 : 0;
    for ($i = 0; $i < $countSubOne; $i++){
        $p = $i;
        for ($j = $i + 1; $j < $count; $j++){
            if ($array[$j] < $array[$i]){
                $p = $j;
            }
        }

        if ($p != $i){
            $tmp = $array[$i];
            $array[$i] = $array[$p];
            $array[$p] = $tmp;
        }
    }
    return $array;
}

/**
 * 插入排序
 * @param $array
 * @return mixed
 */
function insertSort($array){
    $count = count($array);
    for ($i = 1; $i < $count; $i++){
        $tmp = $array[$i];
        for ($j = $i - 1; $j >= 0; $j--){
            if ($tmp < $array[$j]){
                $array[$j + 1] = $array[$j];
                $array[$j] = $tmp;
            }else{
                break;
            }
        }
    }
    return $array;
}

function quickSort($array){

}
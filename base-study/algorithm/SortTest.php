<?php
/**
 * Created by PhpStorm.
 * User: 86184
 * Date: 2019/8/11
 * Time: 14:27
 */

namespace baseStudy\algorithm;

use PHPUnit\Framework\TestCase;
require_once "sort.php";

/**
 * 排序测试
 * Class SortTest
 */
class SortTest extends TestCase{

    public $testArray;
    public $successArray;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->testArray = ["123", "213", "1", "213123", "2131"];
        $this->successArray = ["1", "123", "213", "2131", "213123"];

    }

    /**
     * 测试冒泡排序算法
     */
    public function testBubbleSort(){
        $result = bubbleSort($this->testArray);
        $this->assertEquals($result, $this->successArray);
    }

    /**
     * 测试改进冒泡排序算法
    */
    public function testImproveBubbleSort(){
        $result = improveBubbleSort($this->testArray);
        $this->assertEquals($result, $this->successArray);
    }

    /**
     * 测试选择排序
     */
    public function testSelectSort(){
        $result = selectSort($this->testArray);
        $this->assertEquals($result, $this->successArray);
    }

    /**
     * 测试插入排序
     */
    public function testInsertSort(){
        $result = insertSort($this->testArray);
        $this->assertEquals($result, $this->successArray);
    }
}
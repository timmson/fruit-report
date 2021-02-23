<?php

namespace ru\timmson\FruitReport;

use PHPUnit\Framework\TestCase;

class MathTest extends TestCase
{
    public function testLinearApproximation()
    {
        $arrange = range(100, 200,10);
        $this->assertEquals([100, 10], Math::linearApproximation($arrange));
    }


    public function testPercentile()
    {
        $arrange = range(0, 99);
        $p = 0.85;
        $this->assertEquals(85, Math::percentile($arrange, $p));
    }

    public function testRollingAverage()
    {
        $arrange = range(0, 99);
        $p = count($arrange) / 20;
        $this->assertEquals(85, Math::rollingAverage($arrange, $p)[85]);
    }

}

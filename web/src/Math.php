<?php

namespace ru\timmson\FruitReport;


class Math
{

    /**
     * Returns data for linear approximation
     *
     * @param $data
     * @return array
     */
    public static function trend($data): array
    {
        $line = self::linearApproximation($data);

        $result = array();
        for ($x = 0; $x < count($data); $x++) {
            $result[$x] = round($line[0] + $line[1] * $x);
        }
        return $result;
    }

    /**
     * Returns "a" and "b" for linear approximation (y=a+bx)
     *
     * @param $data
     * @return float[]
     */
    public static function linearApproximation($data): array
    {

        $xSum = array_sum(array_keys($data));
        $ySum = array_sum($data);
        $x2Sum = array_sum(array_map(fn($x) => $x * $x, array_keys($data)));
        $xySum = array_sum(array_map(fn($x) => $x * $data[$x], array_keys($data)));

        $size = count($data);

        $xsr = $xSum / $size;
        $ysr = $ySum / $size;

        $b = ($xySum - $size * $xsr * $ysr) / ($x2Sum - $size * $xsr * $xsr);
        $a = $ysr - $b * $xsr;

        return [$a, $b];
    }

    /**
     * Returns value at given percentile ($p)
     *
     * @param $data
     * @param $percentile
     * @return int
     */
    public static function percentile($data, $percentile): int
    {
        $result = array_values($data);
        sort($result);
        return $result[round(count($data) * $percentile)];
    }

    /**
     * Returns rollingAerage for given $window
     *
     * @param $data
     * @param $window
     * @return array
     */
    public static function rollingAverage($data, $window): array
    {
        $result = array();
        for ($i = 0; $i < count($data); $i++) {
            $array = array_slice($data, max(0, $i - floor($window / 2)), $window + $i < count($data) ? $window : count($data) - $i);
            $result[$i] = round(array_sum($array) / count($array));
        }
        return $result;
    }
}

<?php

namespace App\Services;

class AhpMathService
{

    private const RI = [
        1 => 0.00,
        2 => 0.00,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49,
    ];

    /**
     * @param  mixed  $raw  Raw user input
     * @return float
     */
    public function parseValue(mixed $raw): float
    {
        if (is_string($raw) && str_contains($raw, '/')) {
            [$num, $den] = explode('/', $raw);
            $val = (float) $num / max((float) $den, PHP_FLOAT_EPSILON);
        } else {
            $val = (float) $raw;
        }

        if ($val == 0) {
            $val = 1.0;
        }
        if ($val < 0) {
            $val = 1.0 / abs($val);
        }

        return $val;
    }

    /**
     * @param  array<int|string>  $ids       
     * @param  array              $rawPairs 
     * @return array<int|string, array<int|string, float>>
     */
    public function buildMatrix(array $ids, array $rawPairs): array
    {
        $n = count($ids);
        $matrix = [];

        foreach ($ids as $id) {
            $matrix[$id][$id] = 1.0;
        }

        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $idA = $ids[$i];
                $idB = $ids[$j];

                $val = $this->parseValue($rawPairs[$idA][$idB] ?? 1);

                $matrix[$idA][$idB] = $val;
                $matrix[$idB][$idA] = 1.0 / $val;
            }
        }

        return $matrix;
    }

    /**
     * @param  array              $ids
     * @param  array              $matrix
     * @return array<int|string, float>
     */
    public function computeWeights(array $ids, array $matrix): array
    {
        $n = count($ids);

        $colSums = [];
        foreach ($ids as $col) {
            $sum = 0.0;
            foreach ($ids as $row) {
                $sum += $matrix[$row][$col];
            }
            $colSums[$col] = $sum;
        }

        $weights = [];
        foreach ($ids as $row) {
            $rowSum = 0.0;
            foreach ($ids as $col) {
                $rowSum += $matrix[$row][$col] / $colSums[$col];
            }
            $weights[$row] = $rowSum / $n;
        }

        return $weights;
    }

    /**
     * @param  array  $ids
     * @param  array  $matrix
     * @param  array  $weights
     * @return array
     */
    public function computeConsistency(array $ids, array $matrix, array $weights): array
    {
        $n = count($ids);

        $aw = [];
        foreach ($ids as $row) {
            $sum = 0.0;
            foreach ($ids as $col) {
                $sum += $matrix[$row][$col] * $weights[$col];
            }
            $aw[$row] = $sum;
        }

        $lambdaMax = 0.0;
        foreach ($ids as $id) {
            if ($weights[$id] > 0) {
                $lambdaMax += $aw[$id] / $weights[$id];
            }
        }
        $lambdaMax /= $n;

        $ci = ($n > 1) ? ($lambdaMax - $n) / ($n - 1) : 0.0;
        $ri = self::RI[$n] ?? 1.49;
        $cr = ($ri > 0) ? $ci / $ri : 0.0;

        return compact('lambdaMax', 'ci', 'ri', 'cr');
    }

    /**
     * @param  array  $ids
     * @param  array  $rawPairs
     * @return array{
     *   matrix:   array,
     *   weights:  array,
     *   consistency: array
     * }
     */
    public function calculate(array $ids, array $rawPairs): array
    {
        $matrix = $this->buildMatrix($ids, $rawPairs);
        $weights = $this->computeWeights($ids, $matrix);
        $consistency = $this->computeConsistency($ids, $matrix, $weights);

        return compact('matrix', 'weights', 'consistency');
    }

    /**
     * @param  float  $value
     * @return string
     */
    public function formatSaatyDisplay(float $value): string
    {
        if ($value > 0 && $value < 1) {
            return '1/' . round(1 / $value);
        }

        $int = (int) $value;
        return ($int == $value) ? (string) $int : (string) round($value, 4);
    }
}

<?php

declare(strict_types=1);

function getFooBar(int $number): string|int
{
    if ($number % 15 === 0) {
        return 'foobar';
    } else if ($number % 3 === 0) {
        return 'foo';
    } else if ($number % 5 === 0) {
        return 'bar';
    }

    return $number;
}

function foobarRange($start, $limit, $step = 1): Generator
{
    $value = '';

    if ($start <= $limit) {
        if ($step <= 0) {
            throw new LogicException('Step must be positive');
        }

        for ($i = $start; $i <= $limit; $i += $step) {
            $value = getFooBar($i);

            if ($i < $limit) {
                $value .= ', ';
            }

            yield $value;
        }
    } else {
        if ($step >= 0) {
            throw new LogicException('Step must be negative');
        }

        for ($i = $start; $i >= $limit; $i += $step) {
            $value = getFooBar($i);

            if ($i < $limit) {
                $value .= ', ';
            }

            yield $value;
        }
    }
}

foreach (foobarRange(1, 100, 1) as $value) {
    print $value;
}

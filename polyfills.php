<?php

use Zorille\framework\DATE_SUB;
use Zorille\framework\INTERVAL;
use Zorille\framework\NOW;
use Zorille\framework\DATE_FORMAT;

if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }
}

if (!function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool {
        return substr($haystack, -strlen($needle)) === $needle;
    }
}

if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool {
        return !!strstr($haystack, $needle);
    }
}

if (!function_exists('json_validate')) {
    function json_validate(string $json, int $depth = 512, int $flags = 0): bool {
        if ($flags !== 0 && $flags !== JSON_INVALID_UTF8_IGNORE) {
            throw new ValueError('json_validate(): Argument #3 ($flags) must be a valid flag (allowed flags: JSON_INVALID_UTF8_IGNORE)');
        }

        if ($depth <= 0 ) {
            throw new ValueError('json_validate(): Argument #2 ($depth) must be greater than 0');
        }

        json_decode($json, null, $depth, $flags);

        return json_last_error() === JSON_ERROR_NONE;
    }
}

if (!function_exists('csv_decode')) {
    /**
     * @param string|resource $csv
     * @return array[]
     */
    function csv_decode($csv, string $separator = ',', string $enclosure = '"'): array|stdClass {
        $handle = gettype($csv) === 'resource'
            ? $csv : fopen('data://text/csv;base64,' . base64_encode($csv),'r');

        $keys = [];
        $rows = [];

        $row = 1;
        while (($data = fgetcsv($handle, 10000, $separator, $enclosure)) !== FALSE) {
            if ($row === 1) {
                $keys = $data;
            } else {
                $rows[] = array_reduce($data, fn($r, $v) => [
                    array_merge($r[0], [
                        $keys[$r[1]] => $v
                    ]),
                    ++$r[1]
                ], [[], 0])[0];
            }

            $row++;
        }
        fclose($handle);

        return $rows;
    }
}

if (!function_exists('csv_encode')) {
    function csv_encode(array $csv, string $separator = ',', string $enclosure = '"'): string {
        $finalCsv = [];

        foreach ($csv as $i => $line) {
            if ($i === 0) {
                $finalCsv[] = $enclosure . implode(
                        $enclosure . $separator . $enclosure,
                        array_map(
                            fn($v) => str_replace($enclosure, "\{$enclosure}", $v),
                            array_keys($line)
                        )
                    ) . $enclosure;
            }

            $finalCsv[] = $enclosure . implode(
                    $enclosure . $separator . $enclosure,
                    array_map(
                        fn($v) => str_replace($enclosure, "\{$enclosure}", $v),
                        array_values($line)
                    )
                ) . $enclosure;
        }

        return implode("\n", $finalCsv);
    }
}

if (!function_exists('array_find')) {
    function array_find(array $array, callable $callback): mixed {
        foreach ($array as $key => $item) {
            if ($callback($item, $key)) {
                return $item;
            }
        }
        return null;
    }
}

if (!function_exists('array_find_key')) {
    function array_find_key(array $array, callable $callback): mixed {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $key;
            }
        }
        return null;
    }
}

function _DATE_SUB(mixed $date, mixed $interval): DATE_SUB
{
	return new DATE_SUB($date, $interval);
}

function INTERVAL(int $value, string $unit): INTERVAL
{
	return new INTERVAL($value, $unit);
}

function NOW(): NOW
{
	return new NOW();
}

function _DATE_FORMAT(DATE_SUB $sub, string $format): DATE_FORMAT
{
	return new DATE_FORMAT($sub, $format);
}

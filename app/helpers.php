
<?php

if (! function_exists('array_compare')) {
    function array_compare(array $array1, array $array2): array
    {
        $diff = [];

        foreach ($array1 as $key => $value) {
            if (!array_key_exists($key, $array2) || $value != $array2[$key]) {
                $oldValue = array_key_exists($key, $array2) ? $array2[$key] : null;
                $diff[$key] = ["old" => $oldValue, "new" => $value];
            }
        }

        return $diff;
    }
}

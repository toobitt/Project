<?php


function arrpreg($a, $b)
{
    $arr = array();
    if (is_array($a))
    {
        foreach ($a as $k => $r)
        {
            if (is_array($r))
            {
                foreach ($r as $k1 => $r1)
                {
                    $arr[$k][$k1] = $r1;
                }
            }
        }
    }
    if (is_array($b))
    {
        foreach ($b as $k => $r)
        {
            if (is_array($r))
            {
                foreach ($r as $k1 => $r1)
                {
                    $arr[$k][] = $r1;
                }
            }
        }
    }
    return $arr;
}


?>
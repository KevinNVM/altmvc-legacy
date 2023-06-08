<?php

class JSON
{
    public static function parse($arg, $asObject = true)
    {
        return json_decode($arg, !$asObject);
    }

    public static function stringify($arg)
    {
        return json_encode($arg);
    }
}
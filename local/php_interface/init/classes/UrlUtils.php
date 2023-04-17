<?php


class UrlUtils
{
    public static function getCanonicalUrl()
    {
        return $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"];
    }
}
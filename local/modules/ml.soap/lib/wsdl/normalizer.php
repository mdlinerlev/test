<?php

namespace Ml\Soap\Wsdl;

class Normalizer
{
    public static function FormatComlexType(string $name,
                                            string $typeClass,
                                            string $phpType,
                                            string $compositor,
                                            string $restrictionBase,
                                            array $actions)
    {
        return [
            'name' => $name,
            'typeClass' => $typeClass,
            'phpType' => $phpType,
            'compositor' => $compositor,
            'restrictionBase' => $restrictionBase,
            'actions' => $actions
        ];
    }

    public static function FormatRegisterType(string $name,
                                              array $query,
                                              array $return,
                                              string $namespace,
                                              string $soapaction,
                                              string $style = '',
                                              string $use = '',
                                              string $documentation = ''
    )
    {
        return [
            'name' => $name,
            'query' => $query,
            'return' => $return,
            'namespace' => $namespace,
            'soapaction' => $soapaction,
            'style' => $style,
            'use' => $use,
            'documentation' => $documentation
        ];
    }
}

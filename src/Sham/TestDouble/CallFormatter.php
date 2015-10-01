<?php

namespace Sham\TestDouble;

class CallFormatter 
{
    public static function format($call) {
        $string = "{$call[0]}(\n";
        $argStrings = [];
        foreach ($call[1] as $arg) {
            if (is_object($arg) && $arg instanceof \Hamcrest\Core\IsTypeOf) {
                $argStrings[] = "<".(string) $arg.">";
            } else if ($arg instanceof \PHPUnit_Framework_Constraint_IsType) {
                $argStrings[] = "<".$arg->toString().">";
            } else {
                $argStrings[] = self::formatPrimitive($arg);
            }
        }

        $string.= "    ".implode(",\n    ", $argStrings)."\n)\n";

        return $string;
    }

    private static function formatPrimitive($arg)
    {
        if ($arg === null) {
            return 'null';
        }

        $type = gettype($arg);

        if ($type === 'boolean') {
            $arg = $arg ? 'true' : 'false';
        }

        return $type."($arg)";
    }
}

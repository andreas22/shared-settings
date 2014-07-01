<?php namespace Ac\SharedSettings\Common;

class ArrayHelper
{
    /**
     * Get a value from an array by its key
     *
     * @param mixed $needle The key you want to check for
     * @param mixed $haystack The array you want to search
     * @return bool
     */
    public static function getValueByKey( $needle, $haystack )
    {
        if(isset($haystack[$needle]))
            return $haystack[$needle];

        foreach ( $haystack as $value )
        {
            if ( is_array( $value ) )
            {
                $in_data = ArrayHelper::getValueByKey( $needle, $value );
                if ( $in_data != false )
                    return $in_data;
                else
                    continue;
            }

        }

        return false;
    }
} 
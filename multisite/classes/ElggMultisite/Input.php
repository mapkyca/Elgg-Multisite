<?php


    namespace ElggMultisite {

        class Input
        {

            public static function getInput($name, $default = null, callable $filter = null)
            {
                if (!empty($name)) {
                    $value = null;
                    if (!empty($_REQUEST[$name])) {
                        $value = $_REQUEST[$name];
                    }
                    if (($value===null) && ($default!==null))
                        $value = $default;
                    if (!$value!==null) {
                        if (isset($filter) && is_callable($filter)) {
                            $value = call_user_func($filter, $name, $value);
                        }

                        return $value;
                    }
                }

                return null;
            }

        }

    }
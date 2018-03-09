<?php

namespace ElggMultisite {

    class Version {

        private static $details = [];

        /**
         * Parse version details from version file.
         */
        protected static function parse() {

            if (!empty(static::$details))
                return static::$details;

            $versionfile = dirname(dirname(dirname(dirname(__FILE__)))) . '/version.ini';

            if (!file_exists($versionfile))
                throw new \Exception("Version file $versionfile could not be found, Known doesn't appear to be installed correctly.");

            static::$details = @parse_ini_file($versionfile);

            return static::$details;
        }

        /**
         * Retrieve a field from the version file.
         * @param string $field
         * @return boolean|string
         */
        public static function get($field) {

            $version = static::parse();

            if (isset($version[$field]))
                return $version[$field];

            return false;
        }

        /**
         * Return the human readable version.
         * @return type
         */
        public static function version() {
            return static::get('version');
        }

        /**
         * Return the machine version.
         * @return type
         */
        public static function build() {
            return static::get('build');
        }

    }

}

<?php

namespace SheDied;

class SheDieDConfig {

    static $sources = [];

    private static function _sources() {
        if (empty(self::$sources)) {
            #jobstreet
            self::$sources[96] = ['name' => 'Jobstreet: Yogyakarta', 'url' => 'http://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32700&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[97] = ['name' => 'Jobstreet: Jawa Tengah', 'url' => 'http://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30800&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[98] = ['name' => 'Jobstreet: ', 'url' => ''];
            self::$sources[99] = ['name' => 'Jobstreet: ', 'url' => ''];
        }
    }

    public static function getSourcesList() {
        self::_sources();
        return self::$sources;
    }

    public static function getSource($id) {
        self::_sources();
        if (array_key_exists($id, self::$sources)) {
            return self::$sources[$id];
        } else {
            throw new \Exception('Source ID ' . $id . ' is not found');
        }
    }

}

<?php

namespace SheDied;

class SheDieDConfig {

    static $sources = [];

    const IS_LOKERKREASI = true;

    private static function _sources() {
        if (empty(self::$sources)) {
            #jobstreet
            self::$sources[11] = ['name' => 'Jobstreet: Aceh', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30100&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[12] = ['name' => 'Jobstreet: Bali', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30200&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[13] = ['name' => 'Jobstreet: Bangka Belitung', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32800&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[14] = ['name' => 'Jobstreet: Banten', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32900&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[16] = ['name' => 'Jobstreet: Bengkulu', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30300&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[17] = ['name' => 'Jobstreet: Gorontalo', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=33000&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[6] = ['name' => 'Jobstreet: Jakarta Raya', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30500&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[18] = ['name' => 'Jobstreet: Jambi', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30600&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[9] = ['name' => 'Jobstreet: Jawa Barat', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30700&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[8] = ['name' => 'Jobstreet: Jawa Tengah', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30800&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[7] = ['name' => 'Jobstreet: Jawa Timur', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30900&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[19] = ['name' => 'Jobstreet: Kalimantan Barat', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31000&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[20] = ['name' => 'Jobstreet: Kalimantan Selatan', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31100&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[21] = ['name' => 'Jobstreet: Kalimantan Tengah', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31200&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[22] = ['name' => 'Jobstreet: Kalimantan Timur', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31300&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[23] = ['name' => 'Jobstreet: Kalimantan Utara', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=33500&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[24] = ['name' => 'Jobstreet: Kepulauan Riau', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=33200&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[15] = ['name' => 'Jobstreet: Lampung', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31400&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[25] = ['name' => 'Jobstreet: Maluku', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31500&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[26] = ['name' => 'Jobstreet: Maluku Utara', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=33100&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[27] = ['name' => 'Jobstreet: Nusa Tenggara Barat', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31600&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[28] = ['name' => 'Jobstreet: Nusa Tenggara Timur', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31700&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[29] = ['name' => 'Jobstreet: Papua', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30400&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[30] = ['name' => 'Jobstreet: Papua Barat', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=33300&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[31] = ['name' => 'Jobstreet: Riau', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31800&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[32] = ['name' => 'Jobstreet: Sulawesi Barat', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=33400&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[33] = ['name' => 'Jobstreet: Sulawesi Selatan', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31900&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[34] = ['name' => 'Jobstreet: Sulawesi Tengah', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32000&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[36] = ['name' => 'Jobstreet: Sulawesi Tenggara', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32100&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[35] = ['name' => 'Jobstreet: Sulawesi Utara', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32200&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[37] = ['name' => 'Jobstreet: Sumatera Barat', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32300&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[38] = ['name' => 'Jobstreet: Sumatera Selatan', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32400&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[39] = ['name' => 'Jobstreet: Sumatera Utara', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32500&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[10] = ['name' => 'Jobstreet: Yogyakarta', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32700&specialization=&area=&salary=&ojs=3&src=12'];
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

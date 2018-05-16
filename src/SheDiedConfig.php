<?php

namespace SheDied;

class SheDieDConfig {

    const BEAUTIFUL_YOGYAKARTA_MAPPRESS_ID = 5;
    const AUTHOR_ID = 1;

    static $sources = [];

    private static function _sources() {
        if (empty(self::$sources)) {
            #kompas
            self::$sources[11] = ['name' => 'Kompas: Tekno', 'url' => 'http://indeks.kompas.com/terpopuler/index/tekno'];
            self::$sources[12] = ['name' => 'Kompas: Olahraga', 'url' => 'http://indeks.kompas.com/terpopuler/index/news/olahraga'];
            self::$sources[13] = ['name' => 'Kompas: Nasional', 'url' => 'http://indeks.kompas.com/terpopuler/index/news/nasional'];
            self::$sources[14] = ['name' => 'Kompas: Otomotif', 'url' => 'http://indeks.kompas.com/terpopuler/index/otomotif'];
            self::$sources[15] = ['name' => 'Kompas: Travel', 'url' => 'http://indeks.kompas.com/terpopuler/index/travel'];
            self::$sources[16] = ['name' => 'Kompas: Internasional', 'url' => 'http://indeks.kompas.com/terpopuler/index/news/internasional'];
            self::$sources[17] = ['name' => 'Kompas: Ekonomi', 'url' => 'http://indeks.kompas.com/terpopuler/index/ekonomi'];
            self::$sources[18] = ['name' => 'Kompas: Entertainment', 'url' => 'http://indeks.kompas.com/terpopuler/index/entertainment'];
            self::$sources[19] = ['name' => 'Kompas: Bola', 'url' => 'http://indeks.kompas.com/terpopuler/index/bola'];
            //self::$sources[20] = ['name' => 'Kompas: Otomotif', 'url' => 'http://indeks.kompas.com/terpopuler/index/otomotif'];
            self::$sources[21] = ['name' => 'Kompas: Health', 'url' => 'http://indeks.kompas.com/terpopuler/index/health'];
            self::$sources[22] = ['name' => 'Kompas: Female', 'url' => 'http://indeks.kompas.com/terpopuler/index/female'];

            #krJogja
            self::$sources[26] = ['name' => 'KR Jogja: DIY', 'url' => 'http://krjogja.com/web/news/kanal/1/DIY'];
            self::$sources[27] = ['name' => 'KR Jogja: Info Hotel', 'url' => 'http://krjogja.com/web/news/kategori/53/Info_Hotel'];
            self::$sources[28] = ['name' => 'KR Jogja: Jalan-Jalan', 'url' => 'http://krjogja.com/web/news/kategori/39/Jalan_jalan'];
            self::$sources[29] = ['name' => 'KR Jogja: Agenda Jogja', 'url' => 'http://krjogja.com/web/news/kategori/48/Agenda_Jogja'];
            self::$sources[30] = ['name' => 'KR Jogja: Kampus', 'url' => 'http://krjogja.com/web/news/kategori/17/Kampus'];
            self::$sources[31] = ['name' => 'KR Jogja: Kriminal', 'url' => 'http://krjogja.com/web/news/kategori/14/Kriminal'];

            #liputan6
            self::$sources[36] = ['name' => 'Liputan6: Pilkada DKI', 'url' => 'http://www.liputan6.com/tag/pilkada-dki-2017'];
            self::$sources[37] = ['name' => 'Liputan6: Health', 'url' => 'http://health.liputan6.com/indeks/'];
            self::$sources[38] = ['name' => 'Liputan6: Tekno', 'url' => 'http://tekno.liputan6.com/indeks/'];
            self::$sources[39] = ['name' => 'Liputan6: Lifestyle', 'url' => 'http://lifestyle.liputan6.com/indeks/'];
            self::$sources[40] = ['name' => 'Liputan6: Otomotif', 'url' => 'http://otomotif.liputan6.com/indeks/'];
            self::$sources[41] = ['name' => 'Liputan6: Bisnis', 'url' => 'http://bisnis.liputan6.com/indeks/'];
            self::$sources[42] = ['name' => 'Liputan6: Bola', 'url' => 'http://bola.liputan6.com/indeks/'];
            self::$sources[43] = ['name' => 'Liputan6: Showbiz', 'url' => 'http://showbiz.liputan6.com/indeks/'];
            self::$sources[44] = ['name' => 'Liputan6: News', 'url' => 'http://news.liputan6.com/indeks/'];
            self::$sources[45] = ['name' => 'Liputan6: Global', 'url' => 'http://global.liputan6.com/indeks/'];
            self::$sources[46] = ['name' => 'Liputan6: Regional', 'url' => 'http://regional.liputan6.com/indeks/'];
            self::$sources[47] = ['name' => 'Liputan6: Ramadhan', 'url' => 'http://ramadan.liputan6.com/indeks/'];
            self::$sources[48] = ['name' => 'Liputan6: Ramalan Zodiak', 'url' => 'http://www.liputan6.com/tag/ramalan-zodiak'];
            //self::$sources[49] = ['name' => 'Liputan6: ', 'url' => ''];
            //self::$sources[50] = ['name' => 'Liputan6: ', 'url' => ''];
            #antaranews
            self::$sources[51] = ['name' => 'Antara News: Jogja Terkini', 'url' => 'http://jogja.antaranews.com/terkini'];
            self::$sources[52] = ['name' => 'Antara News: Sinema', 'url' => 'http://www.antaranews.com/hiburan/sinema'];
            self::$sources[53] = ['name' => 'Antara News: Internet', 'url' => 'http://www.antaranews.com/teknologi/internet'];
            self::$sources[54] = ['name' => 'Antara News: Gadget', 'url' => 'http://www.antaranews.com/teknologi/Gadget'];
            self::$sources[55] = ['name' => 'Antara News: Balap', 'url' => 'http://www.antaranews.com/olahraga/balap'];
            self::$sources[56] = ['name' => 'Antara News: Selebriti', 'url' => 'http://www.antaranews.com/hiburan/selebriti'];
            self::$sources[57] = ['name' => 'Antara News: Polkam', 'url' => 'http://www.antaranews.com/nasional/polkam'];
            self::$sources[58] = ['name' => 'Antara News: Nasional', 'url' => 'http://www.antaranews.com/nasional/umum'];
            self::$sources[59] = ['name' => 'Antara News: Bursa', 'url' => 'http://www.antaranews.com/ekonomi/bursa'];
            self::$sources[60] = ['name' => 'Antara News: Seni Budaya', 'url' => 'http://www.antaranews.com/hiburan/seni-budaya'];
            self::$sources[61] = ['name' => 'Antara News: Komputer', 'url' => 'http://www.antaranews.com/teknologi/komputer'];
            //self::$sources[62] = ['name' => 'Antara News : ', 'url' => ''];
            //self::$sources[63] = ['name' => 'Antara News : ', 'url' => ''];
            //self::$sources[64] = ['name' => 'Antara News : ', 'url' => ''];
            #nova
            self::$sources[66] = ['name' => 'Nova: Sedap', 'url' => 'http://nova.grid.id/Sedap'];
            self::$sources[67] = ['name' => 'Nova: Horoskop', 'url' => 'http://nova.grid.id/Horoskop'];
            self::$sources[68] = ['name' => 'Nova: ', 'url' => ''];
            self::$sources[69] = ['name' => 'Nova: ', 'url' => ''];
            self::$sources[70] = ['name' => 'Nova: ', 'url' => ''];
            self::$sources[71] = ['name' => 'Nova: ', 'url' => ''];
            self::$sources[72] = ['name' => 'Nova: ', 'url' => ''];
            self::$sources[73] = ['name' => 'Nova: ', 'url' => ''];
            self::$sources[74] = ['name' => 'Nova: ', 'url' => ''];

            #gamestation
            self::$sources[76] = ['name' => 'GameStation: Reviews', 'url' => 'http://gamestation.id/category/reviews/'];
            self::$sources[77] = ['name' => 'GameStation: ', 'url' => ''];
            self::$sources[78] = ['name' => 'GameStation: ', 'url' => ''];
            self::$sources[79] = ['name' => 'GameStation: ', 'url' => ''];

            #detik
            self::$sources[81] = ['name' => 'Detik: detikNews', 'url' => 'http://news.detik.com/indeks'];
            self::$sources[82] = ['name' => 'Detik: detikFinance', 'url' => 'http://finance.detik.com/indeks?ftindeks'];
            self::$sources[83] = ['name' => 'Detik: detikHot', 'url' => 'http://hot.detik.com/indeks?htind'];
            self::$sources[84] = ['name' => 'Detik: Oto Berita', 'url' => 'http://oto.detik.com/indeks/berita'];
            self::$sources[85] = ['name' => 'Detik: Oto Mobil', 'url' => 'http://oto.detik.com/indeks/mobil'];
            self::$sources[86] = ['name' => 'Detik: Oto Motor', 'url' => 'http://oto.detik.com/indeks/motor'];
            self::$sources[87] = ['name' => 'Detik: Oto Modifikasi', 'url' => 'http://oto.detik.com/indeks/modifikasi'];
            self::$sources[88] = ['name' => 'Detik: Travel Berita', 'url' => 'http://travel.detik.com/indeks/1382'];
            self::$sources[89] = ['name' => 'Detik: Travel Destinasi', 'url' => 'http://travel.detik.com/indeks/1383'];
            self::$sources[90] = ['name' => 'Detik: Travel Stories', 'url' => 'http://travel.detik.com/indeks/1025'];
            self::$sources[91] = ['name' => 'Detik: Travel Photos', 'url' => 'http://travel.detik.com/indeks/1384'];
            self::$sources[92] = ['name' => 'Detik: Sepak Bola', 'url' => 'http://sport.detik.com/sepakbola/indeks?btindeks'];
            self::$sources[93] = ['name' => 'Detik: detikFood', 'url' => 'http://food.detik.com/indeks?dtindeks'];
            self::$sources[94] = ['name' => 'Detik: detikHealth', 'url' => 'http://health.detik.com/indeks?ltindeks'];

            #jobstreet
            self::$sources[96] = ['name' => 'Jobstreet: Yogyakarta', 'url' => 'http://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32700&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[97] = ['name' => 'Jobstreet: Jawa Tengah', 'url' => 'http://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30800&specialization=&area=&salary=&ojs=3&src=12'];
            self::$sources[98] = ['name' => 'Jobstreet: ', 'url' => ''];
            self::$sources[99] = ['name' => 'Jobstreet: ', 'url' => ''];

            #visiting jogja
            self::$sources[101] = ['name' => 'Visiting Jogja: Wisata', 'url' => 'http://visitingjogja.com/web/index.php/detail/wisata.html'];

            #freetutorialsdotus
            self::$sources[102] = ['name' => 'Freetutorials: Academics', 'url' => 'https://www.freetutorials.us/academics-1'];
            self::$sources[103] = ['name' => 'Freetutorials: Business', 'url' => 'https://www.freetutorials.us/business-1'];
            self::$sources[104] = ['name' => 'Freetutorials: Design', 'url' => 'https://www.freetutorials.us/design-1'];
            self::$sources[105] = ['name' => 'Freetutorials: Development', 'url' => 'https://www.freetutorials.us/development-14-1'];
            self::$sources[106] = ['name' => 'Freetutorials: Health & Fitness', 'url' => 'https://www.freetutorials.us/health-fitness-1-2'];
            self::$sources[107] = ['name' => 'Freetutorials: IT & Software', 'url' => 'https://www.freetutorials.us/it-software-1'];
            self::$sources[108] = ['name' => 'Freetutorials: Language', 'url' => 'https://www.freetutorials.us/language-1'];
            self::$sources[109] = ['name' => 'Freetutorials: Lifestyle', 'url' => 'https://www.freetutorials.us/lifestyle'];
            self::$sources[110] = ['name' => 'Freetutorials: Machine Learning', 'url' => 'https://www.freetutorials.us/machine-learning'];
            self::$sources[111] = ['name' => 'Freetutorials: Marketing', 'url' => 'https://www.freetutorials.us/marketing-1'];
            self::$sources[112] = ['name' => 'Freetutorials: Music', 'url' => 'https://www.freetutorials.us/music-1'];
            self::$sources[113] = ['name' => 'Freetutorials: Office Productivity', 'url' => 'https://www.freetutorials.us/office-productivity-1-2'];
            self::$sources[114] = ['name' => 'Freetutorials: Personal Development', 'url' => 'https://www.freetutorials.us/personal-development-1'];
            self::$sources[115] = ['name' => 'Freetutorials: Photography', 'url' => 'https://www.freetutorials.us/photography-1-2'];
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
            return false;
        }
    }

}

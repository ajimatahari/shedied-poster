<?php

use SheDied\SheDieDConfig;
use SheDied\PojokJogjaController;
use SheDied\parser\Collections;

function shedied_exec_bot($sources = [], $count = 1, $transient_name = '', $sweeper = false) {
    try {
        $post_links = get_transient($transient_name);
        $controller = new PojokJogjaController(new Collections());

        if (empty($post_links) && !$sweeper && !empty($sources)) {
            foreach ($sources as $source) {
                $config = SheDieDConfig::getSource($source['src']);
                $controller->setUrl($config['url']);
                $controller->setNewsSrc($source['src']);
                $controller->setCategory($source['cat']);
                $controller->fetchPostLinks();
            }
            $post_links = $controller->getPostLinks();
            set_transient($transient_name, $post_links, DAY_IN_SECONDS);
            syslog(LOG_DEBUG, '[shedied bot] - update transient ' . $transient_name . ' count(' . count($post_links) . ')');
        }

        if (!empty($post_links) && $sweeper) {
            $to_run = array_slice($post_links, 0, $count);
            $to_save = array_slice($post_links, $count, count($post_links));

            if (!empty($to_run)) {
                $controller->setBulkPostType('post')
                        ->setAuthor(SheDieDConfig::AUTHOR_ID) //redaksi
                        ->setBulkPostStatus('publish')
                        ->setInterval(['value' => 2, 'type' => 'minutes'])
                        ->setCount($count)
                        ->hijack(false)
                        ->isAuto(true)
                        ->setPostLinks($to_run)
                        ->botPosts();
            }

            set_transient($transient_name, $to_save, DAY_IN_SECONDS);
        }
    } catch (\Exception $e) {
        syslog(LOG_ERR, '[shedied bot] - ' . $e->getMessage());
    }
}

function bot_yogyakarta() {
    $sources = [
        ['src' => 26, 'cat' => 44], //kr jogja, 
        ['src' => 51, 'cat' => 44], //antara jogja
        ['src' => 30, 'cat' => 73], //kr jogja kampus
        ['src' => 31, 'cat' => 60], //kr jogja kriminal
    ];
    shedied_exec_bot($sources, 5, 'tsnt_yogyakarta');
}

add_action('bot_yogyakarta', 'bot_yogyakarta');

function bot_nasional() {
    $sources = [
        ['src' => 44, 'cat' => 41], //liputan 6 nasional,
        ['src' => 58, 'cat' => 41], //antara news
    ];
    shedied_exec_bot($sources, 5, 'tsnt_nasional');
}

add_action('bot_nasional', 'bot_nasional');

function bot_politik() {
    //liputan 6 pilkada dki, antara polkam
    $sources = [['src' => 36, 'cat' => 64], ['src' => 57, 'cat' => 64]];
    shedied_exec_bot($sources, 5, 'tsnt_politik');
}

add_action('bot_politik', 'bot_politik');

function bot_lifetyle_kesehatan() {
    $sources = [
        ['src' => 37, 'cat' => 35], //kesehatan
        ['src' => 39, 'cat' => 29], //lifestyle
        ['src' => 66, 'cat' => 57] //hobi
    ];
    shedied_exec_bot($sources, 5, 'tsnt_lifetyle_kesehatan');
}

add_action('bot_lifetyle_kesehatan', 'bot_lifetyle_kesehatan');

function bot_entertainment() {
    $sources = [
        ['src' => 43, 'cat' => 28], //liputan 6 showbiz
        ['src' => 52, 'cat' => 40], //antara sinema
        ['src' => 56, 'cat' => 39] //antara selebriti
    ];
    shedied_exec_bot($sources, 5, 'tsnt_entertainment');
}

add_action('bot_entertainment', 'bot_entertainment');

function bot_tekno() {
    $sources = [
        ['src' => 38, 'cat' => 21], //liputan 6 tekno
        ['src' => 53, 'cat' => 24], //antara internet
        ['src' => 54, 'cat' => 23], //antara gadget
        ['src' => 61, 'cat' => 25], //antara komputer
        ['src' => 76, 'cat' => 94] //gamestation
    ];
    shedied_exec_bot($sources, 5, 'tsnt_tekno');
}

add_action('bot_tekno', 'bot_tekno');

function bot_sport() {
    $sources = [
        ['src' => 42, 'cat' => 32], //liputan 6 bola
        ['src' => 55, 'cat' => 27], //antara balap
    ];
    shedied_exec_bot($sources, 5, 'tsnt_sport');
}

add_action('bot_sport', 'bot_sport');

function bot_bisnis() {
    $sources = [
        ['src' => 41, 'cat' => 36], //liputan 6 bisnis
        ['src' => 17, 'cat' => 43], //kompas ekonomi
        ['src' => 59, 'cat' => 43], //antara bursa
    ];
    shedied_exec_bot($sources, 5, 'tsnt_bisnis');
}

add_action('bot_bisnis', 'bot_bisnis');

function bot_ragam() {
    $sources = [
        ['src' => 45, 'cat' => 42], //liputan 6 global
        ['src' => 46, 'cat' => 41], //liputan 6 regional
    ];
    shedied_exec_bot($sources, 5, 'tsnt_ragam');
}

add_action('bot_ragam', 'bot_ragam');

function bot_ragam_dua() {
    $sources = [
        ['src' => 60, 'cat' => 41], //antara seni budaya
        ['src' => 40, 'cat' => 53] //liputan 6 otomotif
    ];
    shedied_exec_bot($sources, 5, 'tsnt_ragam_dua');
}

add_action('bot_ragam_dua', 'bot_ragam_dua');

function bot_ragam_tiga() {
    $sources = [
        ['src' => 48, 'cat' => 65], //liputan 6 tag ramalan zodiak
    ];
    shedied_exec_bot($sources, 5, 'tsnt_ragam_tiga');
}

add_action('bot_ragam_tiga', 'bot_ragam_tiga');

function bot_jogja_karir() {
    $sources = [
        ['src' => 96, 'cat' => 81], //jobstreet yogyakarta
        ['src' => 97, 'cat' => 81] //jobstreet jateng
    ];
    shedied_exec_bot($sources, 5, 'tsnt_jogja_karir');
}

add_action('bot_jogja_karir', 'bot_jogja_karir');

function bot_freetutorials_us() {
    $sources = [
        ['src' => 102, 'cat' => 6],
        ['src' => 103, 'cat' => 7],
        ['src' => 104, 'cat' => 8],
        ['src' => 105, 'cat' => 9],
        ['src' => 106, 'cat' => 10],
        ['src' => 107, 'cat' => 11],
        ['src' => 108, 'cat' => 12],
        ['src' => 109, 'cat' => 13],
        ['src' => 110, 'cat' => 14],
        ['src' => 111, 'cat' => 15],
        ['src' => 112, 'cat' => 16],
        ['src' => 113, 'cat' => 17],
        ['src' => 114, 'cat' => 18],
        ['src' => 115, 'cat' => 19],
    ];
    shedied_exec_bot($sources, 5, 'tsnt_freetutorials_us');
}

add_action('bot_freetutorials_us', 'bot_freetutorials_us');

function bot_freetutorials_us_once() {
    $sources = [
        ['src' => 102, 'cat' => 6],
        ['src' => 103, 'cat' => 7],
        ['src' => 104, 'cat' => 8],
        ['src' => 105, 'cat' => 9],
        ['src' => 106, 'cat' => 10],
        ['src' => 107, 'cat' => 11],
        ['src' => 108, 'cat' => 12],
        ['src' => 109, 'cat' => 13],
        ['src' => 110, 'cat' => 14],
        ['src' => 111, 'cat' => 15],
        ['src' => 112, 'cat' => 16],
        ['src' => 113, 'cat' => 17],
        ['src' => 114, 'cat' => 18],
        ['src' => 115, 'cat' => 19],
    ];

    $transient_name = 'tsnt_freetutorials_us';
    $postlinks = [];
    $controller = new PojokJogjaController(new Collections());
    foreach ($sources as $row) {
        $config = SheDieDConfig::getSource($row['src']);
        if (!empty($config)) {
            for ($i = 1; $i <= 50; $i++) {
                $url = $config['url'] . '/page/' . $i . '/?_=' . time();
                $controller->setUrl($url);
                $controller->setNewsSrc($row['src']);
                $controller->setCategory($row['cat']);
                if ($controller->fetchPostLinks()) {
                    $postlinks = array_merge($postlinks, $controller->getPostLinks());
                }
            }
        }
    }

    set_transient($transient_name, $postlinks, WEEK_IN_SECONDS);
    syslog(LOG_DEBUG, '[shedied bot] - update transient ' . $transient_name . ' count(' . count($postlinks) . ')');
}

add_action('bot_freetutorials_us_once', 'bot_freetutorials_us_once');

function bot_sweeper() {
    shedied_exec_bot([], 5, 'tsnt_nasional', true);
    shedied_exec_bot([], 5, 'tsnt_politik', true);
    shedied_exec_bot([], 5, 'tsnt_yogyakarta', true);
    shedied_exec_bot([], 5, 'tsnt_lifetyle_kesehatan', true);
    shedied_exec_bot([], 5, 'tsnt_entertainment', true);
    shedied_exec_bot([], 5, 'tsnt_tekno', true);
    shedied_exec_bot([], 5, 'tsnt_sport', true);
    shedied_exec_bot([], 5, 'tsnt_bisnis', true);
    shedied_exec_bot([], 5, 'tsnt_ragam', true);
    shedied_exec_bot([], 5, 'tsnt_ragam_dua', true);
    shedied_exec_bot([], 5, 'tsnt_jogja_karir', true);
    shedied_exec_bot([], 5, 'tsnt_ragam_tiga', true);
    shedied_exec_bot([], 5, 'tsnt_freetutorials_us', true);
}

add_action('bot_sweeper', 'bot_sweeper');

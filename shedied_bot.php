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
                        ->setAuthor(7) //redaksi
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

function bot_jogja_karir() {
    $sources = [
            ['src' => 96, 'cat' => 81], //jobstreet yogyakarta
        ['src' => 97, 'cat' => 81] //jobstreet jateng
    ];
    shedied_exec_bot($sources, 5, 'tsnt_jogja_karir');
}

add_action('bot_jogja_karir', 'bot_jogja_karir');

function bot_sweeper() {
    shedied_exec_bot([], 5, 'tsnt_jogja_karir', true);
}

add_action('bot_sweeper', 'bot_sweeper');

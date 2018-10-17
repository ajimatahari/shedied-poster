<?php

use SheDied\SheDieDConfig;
use SheDied\PojokJogjaController;
use SheDied\parser\Collections;

function shedied_exec_bot($sources = [], $count = 1, $transient_name = '', $sweeper = false) {
    try {
        $post_links = get_transient($transient_name);
        $controller = new PojokJogjaController(new Collections());

        if (empty($post_links) && !$sweeper && !empty($sources)) {
            foreach ($sources as $sourceId => $source) {
                $controller->setUrl($source['url']);
                $controller->setNewsSrc($sourceId);
                $controller->setCategory(5); //kategori lowongan-kerja
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
                        ->setAuthor(2) //bot
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

function bot_jobstreet_1() {
    $sources = array_slice(SheDieDConfig::getSourcesList(), 0, 10, true);
    shedied_exec_bot($sources, 20, 'tsnt_jobstreet_1');
}

add_action('bot_jobstreet_1', 'bot_jobstreet_1');

function bot_jobstreet_2() {
    $sources = array_slice(SheDieDConfig::getSourcesList(), 10, 10, true);
    shedied_exec_bot($sources, 20, 'tsnt_jobstreet_2');
}

add_action('bot_jobstreet_2', 'bot_jobstreet_2');

function bot_jobstreet_3() {
    $sources = array_slice(SheDieDConfig::getSourcesList(), 20, 10, true);
    shedied_exec_bot($sources, 20, 'tsnt_jobstreet_3');
}

add_action('bot_jobstreet_3', 'bot_jobstreet_3');

function bot_jobstreet_4() {
    $sources = array_slice(SheDieDConfig::getSourcesList(), 30, 10, true);
    shedied_exec_bot($sources, 20, 'tsnt_jobstreet_4');
}

add_action('bot_jobstreet_4', 'bot_jobstreet_4');

function bot_sweeper() {
    shedied_exec_bot([], 5, 'tsnt_jobstreet_1', true);
    shedied_exec_bot([], 5, 'tsnt_jobstreet_2', true);
    shedied_exec_bot([], 5, 'tsnt_jobstreet_3', true);
    shedied_exec_bot([], 5, 'tsnt_jobstreet_4', true);
}

add_action('bot_sweeper', 'bot_sweeper');

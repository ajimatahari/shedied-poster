<?php

namespace SheDied;

use SheDied\parser\Controller;
use SheDied\parser\Collections;
use SheDied\parser\CWriter;
use SheDied\parser\InterfaceParser;
use SheDied\WPWrapper;
use SheDied\SheDieDConfig;

class PojokJogjaController extends Controller {

    protected $news_src;
    protected $bulk_post_type;
    protected $category;
    protected $author;
    protected $bulk_post_status;
    protected $date;
    protected $interval;
    protected $action;
    protected $url;
    protected $collections;
    protected $post_links = [];
    protected $count = 20;
    protected $auto = false;
    protected $is_rewrite;
    protected $is_remove_links;
    protected $is_full_source;
    protected $additional = [];
    protected $hijack = false;
    protected $map_pois = [];
    protected $map_pois_collect = false;

    public function __construct(Collections $collections) {
        parent::__construct();
        $this->getOptions();
        $this->collections = $collections;
    }

    protected function getOptions() {
        $this->is_rewrite = WPWrapper::get_option('shedied_isRewrite');
        $this->is_remove_links = WPWrapper::get_option('shedied_isRemoveLink');
        $this->is_full_source = WPWrapper::get_option('shedied_isFullSource');
        $rewriter['prefix'] = WPWrapper::get_option('shedied_firstpara');
        $rewriter['suffix'] = WPWrapper::get_option('shedied_lastpara');
        $this->additional = $rewriter;
    }

    public function setNewsSrc($news_src) {
        $this->news_src = $news_src;
        return $this;
    }

    public function setBulkPostType($type) {
        $this->bulk_post_type = $type;
        return $this;
    }

    public function setCategory($cat) {
        $this->category = $cat;
        return $this;
    }

    public function setAuthor($author) {
        $this->author = $author;
        return $this;
    }

    public function setBulkPostStatus($status) {
        $this->bulk_post_status = $status;
        return $this;
    }

    public function setDate(\DateTime $date) {
        $this->date = $date;
        return $this;
    }

    public function setInterval($interval) {
        $this->interval = $interval;
        return $this;
    }

    public function setAction($action) {
        $this->action = $action;
        return $this;
    }

    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    public function setCount($count) {
        $this->count = $count;
        return $this;
    }

    public function isAuto($bool) {
        $this->auto = (bool) $bool;
        return $this;
    }

    public function setPostLinks($links) {
        $this->post_links = $links;
        return $this;
    }

    public function buildPosts() {
        if (!$this->hijack && !$this->auto) {
            $this->fetchPostLinks();
        }

        switch ($this->news_src) {
            case $this->news_src > 10 && $this->news_src < 25:
                $this->buildPostsKompas();
                break;
            case $this->news_src > 25 && $this->news_src < 35:
                $this->buildPostsKrJogja();
                break;
            case $this->news_src > 35 && $this->news_src < 50:
                $this->buildPostsLiputan6();
                break;
            case $this->news_src > 50 && $this->news_src < 65:
                $this->buildPostsAntaraNews();
                break;
            case $this->news_src > 65 && $this->news_src < 75:
                $this->buildPostNova();
                break;
            case $this->news_src > 75 && $this->news_src < 80:
                $this->buildPostGameStation();
                break;
            case $this->news_src > 80 && $this->news_src < 95:
                $this->buildPostDetik();
                break;
            case $this->news_src > 95 && $this->news_src < 100:
                $this->buildPostJobstreet();
                break;
            case $this->news_src > 100 && $this->news_src < 105:
                if ($this->news_src == 101) {
                    $this->map_pois_collect = true;
                    $this->buildPostVisitingJogja();
                    $this->update_page_beautiful_yogyakarta();
                } else {
                    $this->buildPostVisitingJogja();
                }
                break;
        }
    }

    public function fetchPostLinks() {
        $doc = @file_get_contents($this->url);
        if (function_exists('mb_convert_encoding')) {
            $doc = mb_convert_encoding($doc, "HTML-ENTITIES", "UTF-8");
        }

        \phpQuery::newDocument($doc);

        if ($this->news_src > 10 && $this->news_src < 25) {
            #kompas
            foreach (pq('div.tleft h3 a') as $a) {
                $link = pq($a)->attr('href');
                $title = pq($a)->elements[0]->nodeValue;
                $this->post_links[] = array("title" => $title, "link" => trim($link), 'src' => $this->news_src, 'cat' => $this->category);
            }
        }

        if ($this->news_src > 25 && $this->news_src < 35) {
            #kr jogja
            foreach (pq('div.news-post.article-post h2 a') as $a) {
                $link = pq($a)->attr('href');
                $title = pq($a)->elements[0]->nodeValue;
                $this->post_links[] = array("title" => $title, "link" => trim($link), 'src' => $this->news_src, 'cat' => $this->category);
            }
        }

        if ($this->news_src > 35 && $this->news_src < 50) {
            if ($this->news_src == 36 || $this->news_src == 48) {
                #liputan 6 pilkada dki
                #tag ramalan zodiak
                foreach (pq('header.articles--iridescent-list--text-item__header h4 a') as $a) {
                    $link = pq($a)->attr('href');
                    $title = pq($a)->elements[0]->nodeValue;
                    $this->post_links[] = array("title" => $title, "link" => trim($link), 'src' => $this->news_src, 'cat' => $this->category);
                }
            } else {
                #liputan 6
                foreach (pq('header.articles--rows--item__header h4 a') as $a) {
                    $link = pq($a)->attr('href');
                    $title = pq($a)->elements[0]->nodeValue;
                    $this->post_links[] = array("title" => $title, "link" => trim($link), 'src' => $this->news_src, 'cat' => $this->category);
                }
            }
        }

        if ($this->news_src > 50 && $this->news_src < 65) {
            #Antara News
            foreach (pq('div.bxpd h3 a') as $a) {
                $link = pq($a)->attr('href');
                $title = pq($a)->elements[0]->nodeValue;
                $this->post_links[] = array("title" => $title, "link" => trim($link), 'src' => $this->news_src, 'cat' => $this->category);
            }
        }

        if ($this->news_src > 65 && $this->news_src < 75) {
            #Nova
            if ($this->news_src == 66) {
                #sedap
                foreach (pq('div.latest_tx p a') as $a) {
                    $link = pq($a)->attr('href');
                    $title = pq($a)->elements[0]->nodeValue;
                    $this->post_links[] = array("title" => $title, "link" => trim($link), 'src' => $this->news_src, 'cat' => $this->category);
                }
            }
            if ($this->news_src == 67) {
                #horoskop
                $base_date = new \DateTime(WPWrapper::current_time(), new \DateTimeZone(WPWrapper::get_option('timezone_string')));
                $awal = $base_date->format('d M Y');
                $base_date->modify('+7 days');
                $akhir = $base_date->format('d M Y');
                $expire = ' ( ' . $awal . ' - ' . $akhir . ' )';
                foreach (pq('div.latest-horoskop a') as $a) {
                    $link = pq($a)->attr('href');
                    $title = pq($a)->find('div.box-horoskop h1')->elements[0]->nodeValue . $expire;
                    $this->post_links[] = array("title" => $title, "link" => trim($link), 'src' => $this->news_src, 'cat' => $this->category);
                }
            }
        }

        if ($this->news_src > 75 && $this->news_src < 80) {
            #Gamestation
            foreach (pq('div.article-image h3 a') as $a) {
                $link = pq($a)->attr('href');
                $title = pq($a)->elements[0]->nodeValue;
                $this->post_links[] = array("title" => $title, "link" => trim($link), 'src' => $this->news_src, 'cat' => $this->category);
            }
        }

        if ($this->news_src > 80 && $this->news_src < 95) {
            #Detik
        }

        if ($this->news_src > 95 && $this->news_src < 100) {
            #Jobstreet
            foreach (pq('div.position-title.header-text a') as $a) {
                $link = pq($a)->attr('href');
                $title = pq($a)->elements[0]->nodeValue;
                $this->post_links[] = array("title" => trim($title), "link" => trim($link), 'src' => $this->news_src, 'cat' => $this->category);
            }
        }

        if ($this->news_src > 100 && $this->news_src < 105) {
            #Jobstreet
            foreach (pq('h2.prl-article-title a') as $a) {
                $link = pq($a)->attr('href');
                $title = pq($a)->elements[0]->nodeValue;
                $this->post_links[] = array("title" => trim($title), "link" => trim($link), 'src' => $this->news_src, 'cat' => $this->category);
            }
        }
    }

    protected function loopPostLinks($instance_class_name = '') {
        $post_links = array_reverse($this->post_links);
        $key = 0;

        foreach ($post_links as $post_link) {
            if ($key >= $this->count) {
                break;
            }
            if ($this->auto) {
                $this->news_src = trim($post_link['src']);
                $this->category = trim($post_link['cat']);
                $instance_class_name = $this->getParserNameBySource($this->news_src);
            }

            $title = str_replace("&", "dan", trim($post_link['title']));
            $title = ucwords($title);
            $link = $post_link['link'];

            if (!WPWrapper::get_page_by_title($title)) {
                $parser = new $instance_class_name();
                $parser->setTitle($title)
                        ->setSourceCategory($this->news_src)
                        ->setCategoryId($this->category)
                        ->setUrl($link)
                        ->grab();

                if (strlen($parser->getContent()) > 0 && $parser->postThis()) {
                    CWriter::removeHtmlComments($parser);
                    if ($this->is_rewrite == "true") {
                        CWriter::rewrite($parser);
                    }
                    if ($this->is_remove_links == "true") {
                        CWriter::removeLinks($parser);
                    }
                    if ($this->is_full_source == "true") {
                        CWriter::rewriteURL($parser);
                    }
                    if (strlen($this->additional['prefix']) > 0 && strlen($this->additional['suffix']) > 0) {
                        CWriter::addPrefixSuffix($parser, $this->additional);
                    }

                    $new_draft_id = $this->createPost($parser, $key);
                    
                    if ($new_draft_id > 0) {
                        WPWrapper::exe_custom_upload_dir($new_draft_id);
                        WPWrapper::generate_featured_image($parser, $new_draft_id);

                        if ($parser->getHost() == 'jobstreet.co.id') {
                            WPWrapper::pojokjogja_set_source_for_jobstreet($new_draft_id, $parser->getHost(), $parser->getUrl(), $parser->getNamaPerusahaan());
                            WPWrapper::pojokjogja_set_tags_for_jobstreet($new_draft_id, $parser->getNamaPerusahaan(), $parser->getTags());
                        } else {
                            WPWrapper::pojokjogja_set_source($new_draft_id, $parser->getHost(), $parser->getUrl());
                        }

                        WPWrapper::add_to_yoast_seo($new_draft_id, $parser->getMetaTitle(), $parser->getMetaDescription(), $parser->getMetaKeywords());

                        $map = WPWrapper::pojokjogja_set_map_to_post($new_draft_id, $parser);
                        if ($map && $this->map_pois_collect) {
                            $this->map_pois = array_merge($this->map_pois, $map->pois);
                        }

                        $this->echoSuccess($title, $new_draft_id);
                    }
                    syslog(LOG_DEBUG, 'key = ' . $key);
                    $key++;
                } else {
                    $this->echoFailed($title);
                }
            } else {
                $this->echoSkip($title);
            }
        }

        if ($key == 0) {
            $this->echoNoEntry();
        }
    }

    /**
     * update for page titled "Beautiful Yogyakarta"
     */
    protected function update_page_beautiful_yogyakarta() {
        $page = WPWrapper::get_page_by_title('Beautiful Yogyakarta', 'OBJECT', 'page');
        if ($page) {
            if (!empty($this->map_pois)) {
                $map = WPWrapper::mappress_get_map(SheDieDConfig::BEAUTIFUL_YOGYAKARTA_MAPPRESS_ID);
                $current_pois = $map->pois;
                $map->pois = array_merge($current_pois, $this->map_pois);
                WPWrapper::mappress_update_map($page->ID, $map);
                //clear & set to false
                unset($this->map_pois);
                $this->map_pois_collect = false;
            }
        }
    }

    protected function echoSuccess($title, $new_draft_id) {
        if ($this->auto) {
            syslog(LOG_DEBUG, '[shedied bot] created - ' . $new_draft_id . ' - ' . $title);
        } else {
            echo $title . " created. <a href='post.php?action=edit&post=" . $new_draft_id . "' target='_blank'>Edit</a> |"
            . "<a href='" . WPWrapper::get_option('siteurl') . "?p=" . $new_draft_id . "' target='_blank'> View</a><br>" . PHP_EOL;
        }
    }

    protected function echoFailed($title) {
        if ($this->auto) {
            syslog(LOG_DEBUG, '[shedied bot] failed - ' . $title);
        } else {
            echo $title . " failed<br />";
        }
    }

    protected function echoSkip($title) {
        if ($this->auto) {
            syslog(LOG_DEBUG, '[shedied bot] skipped - ' . $title);
        } else {
            echo $title . " already posted. Skipped<br />";
        }
    }

    protected function echoNoEntry() {
        if ($this->auto) {
            syslog(LOG_DEBUG, '[shedied bot] no entry - belum ada berita baru');
        } else {
            echo "New posts are not found, please try again later";
        }
    }

    protected function buildPostsKompas() {
        $this->loopPostLinks('SheDied\parser\KompasParser');
    }

    protected function createPost(InterfaceParser $parser, $key) {
        try {
            $base_date = new \DateTime(WPWrapper::current_time(), new \DateTimeZone(WPWrapper::get_option('timezone_string')));
            $post_interval = '+' . ((int) $this->interval['value'] * (int) $key) . ' ' . $this->interval['type'];
            $post_time = strtotime($post_interval, $base_date->getTimestamp());
            $base_date->setTimestamp($post_time);
            $parser->setAuthorId($this->author)
                    ->setType($this->bulk_post_type)
                    ->setTime($base_date->format('Y-m-d H:i:s'))
                    ->setStatus($this->bulk_post_status);
            if (!empty($parser->getTags())) {
                //$post_tag = WPWrapper::get_cat_name($parser->getTags());
                //$parser->setTags($post_tag);
            }
            $post_id = WPWrapper::wp_insert_post($parser);
            if ($post_id == 0) {
                syslog(LOG_ERR, '[shedied poster] - gagal simpan berita ' . $parser->getTitle());
            }
            return $post_id;
        } catch (\Exception $ex) {
            syslog(LOG_ERR, '[shedied poster] - gagal simpan berita ' . $parser->getTitle() . ' - ' . $ex->getMessage());
            return 0;
        }
    }

    protected function buildPostsKrJogja() {
        $this->loopPostLinks('SheDied\parser\KrJogjaParser');
    }

    protected function buildPostsLiputan6() {
        $this->loopPostLinks('SheDied\parser\Liputan6Parser');
    }

    public function hijack($bool) {
        $this->hijack = (bool) $bool;
        if ($this->hijack) {
            $this->count = 1;
            $this->post_links[] = [
                'title' => 'Coba Custom Sendiri',
                'link' => 'http://nova.grid.id/Sedap/Kue/Camilan-Sore-Puding-Durian-Gula-Palem-Dan-Puding-Susu-Pandan'
            ];
        }
        return $this;
    }

    protected function buildPostsAntaraNews() {
        $this->loopPostLinks('SheDied\parser\AntaraNewsParser');
    }

    protected function buildPostNova() {
        $this->loopPostLinks('SheDied\parser\NovaParser');
    }

    protected function buildPostGameStation() {
        $this->loopPostLinks('SheDied\parser\GameStationParser');
    }

    protected function buildPostDetik() {
        $this->loopPostLinks('SheDied\parser\DetikParser');
    }

    protected function buildPostJobstreet() {
        $this->loopPostLinks('SheDied\parser\JobstreetParser');
    }

    protected function buildPostVisitingJogja() {
        $this->loopPostLinks('SheDied\parser\VisitingJogjaParser');
    }

    public function getPostLinks() {
        return $this->post_links;
    }

    public function botPosts() {
        $this->loopPostLinks();
    }

    protected function getParserNameBySource($id) {
        switch ($id) {
            case $id > 10 && $id < 25:
                return 'SheDied\parser\KompasParser';
            case $id > 25 && $id < 35:
                return 'SheDied\parser\KrJogjaParser';
            case $id > 35 && $id < 50:
                return 'SheDied\parser\Liputan6Parser';
            case $id > 50 && $id < 65:
                return 'SheDied\parser\AntaraNewsParser';
            case $id > 65 && $id < 75:
                return 'SheDied\parser\NovaParser';
            case $id > 75 && $id < 80:
                return 'SheDied\parser\GameStationParser';
            case $id > 80 && $id < 95:
                return 'SheDied\parser\DetikParser';
            case $id > 95 && $id < 100:
                return 'SheDied\parser\JobstreetParser';
            case $id > 100 && $id < 105:
                return 'SheDied\parser\VisitingJogjaParser';
            default:
                return '';
        }
    }

}

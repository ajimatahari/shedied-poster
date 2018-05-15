<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParser;

class FreetutorialsParser extends AbstractParser {

    const PLACEHOLDER_TORRENT_LINK = '<download-torrent>';

    protected $torrent_link = '';
    protected $sub_header_text;
    protected $upload_torrent_link = false;

    protected function getPostDetail() {
        $doc = $this->curlGrabContent();
        if (function_exists('mb_convert_encoding')) {
            $doc = mb_convert_encoding($doc, "HTML-ENTITIES", "UTF-8");
        }
        $html = \phpQuery::newDocument($doc);
        $node = pq(".entry-content");
        $node->find('.code-block')->remove();
        $node->find('.post-views')->remove();
        $node->find('.clp-component-render')->remove();
        $node->find('div > div')->contentsUnwrap();
        $node->find('strong')->contentsUnwrap();
        $this->cleanContentObject($node);
        $this->node = $node;
    }

    public function grab() {
        $this->getPostDetail();
        $this->_getFeaturedImage();
        $this->_getTorrentLink();
        $this->_setContent();
        $this->aggregateContent();
        $this->_getHost();
        $this->cleanUp();
        $this->generateSeoMetaDescription();
        $this->generateSeoMetaKeywords();
    }

    protected function _setContent() {
        $download_button = $this->downloadButton();
        $this->node->find('p > a')->replaceWith($download_button);
        $this->node->find('h1')->remove();
        $this->content = $this->node->html();
    }

    protected function downloadButton() {
        if (empty($this->torrent_link)) {
            $link = '#';
        } else {
            if (strpos($this->torrent_link, '.torrent') !== false) {
                $link = self::PLACEHOLDER_TORRENT_LINK;
                $this->upload_torrent_link = true;
            } else {
                $link = $this->torrent_link;
            }
        }
        $button = '<a href="' . $link . '" target="_blank" class="button button-primary" rel="nofollow">';
        $button .= '<i class="fas fa-download"></i> Download';
        $button .= '</a>';

        return $button;
    }

    protected function _getFeaturedImage() {
        if (!$this->no_image) {
            $this->featured_image = trim($this->node->find('h1 img')->attr('src'));
        }
    }

    protected function _getTorrentLink() {
        $link = $this->node->find('img.alignnone')->parent('a')->attr('href');
        $this->torrent_link = trim($link);
    }

    protected function generateSeoMetaDescription() {
        $text = 'Download video course: ' . $this->title;
        $this->meta_description = $text;
    }

    protected function generateSeoMetaKeywords() {
        $text = 'download video course,' . $this->title;
        $this->meta_keywords = $text;
    }

    protected function getSubHeaderText() {
        $text = $this->node->find('h1:not(:has(>img))')->text();
        $this->sub_header_text = trim($text);
    }

    public function uploadTorrentLink() {
        return $this->upload_torrent_link;
    }

    public function getTorrentLink() {
        return $this->torrent_link;
    }

}

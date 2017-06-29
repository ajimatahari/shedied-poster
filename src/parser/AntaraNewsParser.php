<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParser;

class AntaraNewsParser extends AbstractParser {

    protected function getPostDetail() {
        $doc = $this->curlGrabContent();
        if (function_exists('mb_convert_encoding')) {
            $doc = mb_convert_encoding($doc, "HTML-ENTITIES", "UTF-8");
        }
        $html = \phpQuery::newDocument($doc);
        $node = pq("#content_news");
        $this->cleanContentObject($node);
        $this->content = $node->html();
    }

    public function setUrl($url) {
        if ($this->source_category == 51) {
            $this->url = 'http://jogja.antaranews.com' . strval($url);
        } else {
            $this->url = 'http://www.antaranews.com' . strval($url);
        }
        return $this;
    }

    public function grab() {
        $this->getPostDetail();
        $this->_getFeaturedImage();
        $this->_getTags();
        $this->aggregateContent();
        $this->_getHost();
        $this->cleanUp();
        $this->generateSeoMetaTitle();
        $this->generateSeoMetaDescription();
        $this->generateSeoMetaKeywords();
    }

    protected function _getFeaturedImage() {
        if (!$this->no_image) {
            $this->featured_image = pq('#image_news img')->attr('src');
        }
    }

    protected function generateSeoMetaDescription() {
        $this->meta_description = pq('meta[name="description"]')->attr('content');
    }

    protected function generateSeoMetaKeywords() {
        $this->meta_keywords = pq('meta[name="keywords"]')->attr('content');
    }

}

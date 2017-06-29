<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParser;

class KompasParser extends AbstractParser {

    public function setUrl($url) {
        $this->url = strval($url) . '?page=all';
        return $this;
    }

    protected function getPostDetail() {
        $node = '';
        $doc = $this->curlGrabContent();
        if (function_exists('mb_convert_encoding')) {
            $doc = mb_convert_encoding($doc, "HTML-ENTITIES", "UTF-8");
        }

        $html = \phpQuery::newDocument($doc);

        if ($this->source_category == 11 || $this->source_category == 12 || $this->source_category == 13 || $this->source_category == 16 || $this->source_category == 17 || $this->source_category == 18 || $this->source_category == 21 || $this->source_category == 22) {
            $node = pq("div.kcm-read-text");
        }
        if ($this->source_category == 14 || $this->source_category == 20) {
            $node = pq('div.div-read');
        }
        if ($this->source_category == 15) {
            $node = pq('div.kcm-read-text');
        }
        if ($this->source_category == 19) {
            $node = pq('span.kcmread1114');
        }
        if ($node->html() == "") {
            $node = pq("div.kcm-read");
        }

        if (!empty($node)) {
            $node->find('div.video')->remove();
            $this->cleanContentObject($node);

            $this->content = $node->html();
        }
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
            $this->featured_image = pq('div.photo img')->attr('src');
        }
    }

    protected function _getTags() {
        if (pq('ul.orange.kcm-breadcrumb a')->html() != "") {
            $this->category_name = pq('ul.orange.kcm-breadcrumb a')->elements[1]->nodeValue;
        }
    }

    protected function generateSeoMetaTitle() {
        ;
    }

    protected function generateSeoMetaDescription() {
        $first_paragraph = substr($this->content, 0, strpos($this->content, '</p>') + 4);
        $first_paragraph = strip_tags($first_paragraph);
        $description = $this->stripKompasTextFromDescription($first_paragraph);
        $description = $this->setMetaDescriptionLength($description);
        $description = ucfirst($description);
        $this->meta_description = $description;
    }

    private function stripKompasTextFromDescription($words) {
        $words = preg_replace('/.*?kompas.*?[\s]/', '', strtolower($words));
        $words = preg_replace('/^[^\s]++/', '', trim($words));
        return trim($words);
    }

    protected function cleanUp() {
        parent::cleanUp();
        //$this->removeIframeVideoFromContent();
        $this->removeContentSuggestions();
        $this->alterBlockQuote();
    }

    private function removeIframeVideoFromContent() {
        $content = preg_replace('/<div[^>]*>.*?<iframe.*?<\/div>/s', '', $this->content);
        $content = preg_replace('/<p[^>]*>.*?<iframe.*?<\/p>/s', '', $content);
        $this->content = $content;
    }

    private function removeContentSuggestions() {
//        $content = preg_replace('/<p>\(Baca.*?<\/p>/s', '', $this->content);
//        $content = preg_replace('/<strong>.*?Baca.*?<\/strong>/s', '', $content);
//        $content = preg_replace('/\(Baca.*?\)/s', '', $content);
        $content = preg_replace('/<p>Selengkapnya\sbaca\sdi\ssini.*?<\/p>/s', '', $this->content);
        $this->content = $content;
    }

}

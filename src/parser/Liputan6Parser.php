<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParser;

class Liputan6Parser extends AbstractParser {

    protected function getPostDetail() {
        $doc = $this->curlGrabContent();
        if (function_exists('mb_convert_encoding')) {
            $doc = mb_convert_encoding($doc, "HTML-ENTITIES", "UTF-8");
        }
        $html = \phpQuery::newDocument($doc);
        $node = pq("div.article-content-body__item-content");
        $node->find('a[title="Selengkapnya..."]')->remove();
        $node->find('a[title="Selanjutnya..."]')->remove();
        $node->find('div.baca-juga')->remove();
        $node->find('img')->attr('class', 'aligncenter size-full');
        $node->find('div.article-content-body__item-break')->remove();
        $node->find('p:contains("Saksikan Video Pilihan")')->remove();
        $this->cleanContentObject($node);

        $this->content = $node->html();
    }

    public function grab() {
        $this->getPostDetail();
        $this->checkTitle();
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
            $this->featured_image = pq('meta[property="og:image"]')->attr('content');
        }
    }

    protected function _getTags() {
        if ($this->source_category == 36) {
            $this->tags = 'Pilkada DKI 2017';
        }
    }

    protected function generateSeoMetaDescription() {
        $this->meta_description = pq('meta[name="description"]')->attr('content');
    }

    protected function generateSeoMetaKeywords() {
        $this->meta_keywords = pq('meta[name="news_keywords"]')->attr('content');
    }

    protected function cleanUp() {
        parent::cleanUp();
        $this->alterBlockQuote();
        $this->removeContentSuggestions();
    }

    private function removeContentSuggestions() {
        $content = preg_replace('/<p>Selanjutnya\sbaca\sdi\ssini.*?<\/p>/s', '', $this->content);
        $this->content = $content;
    }

    protected function checkTitle() {
        $ttl = strtolower($this->title);
        if (preg_match('/video:/', $ttl)) {
            $this->post_this = false;
        }
    }

}

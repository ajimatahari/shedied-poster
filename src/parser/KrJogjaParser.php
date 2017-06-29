<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParser;

class KrJogjaParser extends AbstractParser {

    public function setUrl($url) {
        $this->url = 'http://krjogja.com' . strval($url);
        return $this;
    }

    protected function getPostDetail() {
        $doc = $this->curlGrabContent();
        if (function_exists('mb_convert_encoding')) {
            $doc = mb_convert_encoding($doc, "HTML-ENTITIES", "UTF-8");
        }
        $html = \phpQuery::newDocument($doc);
        #image treatments
        $a = [];
        foreach (pq("div.single-post-box > div.post-content:first img")as $img) {
            $image = pq($img)->attr('src');
            $a[] = $image;
            pq($img)->attr('src', 'http://krjogja.com' . trim($image));
            pq($img)->attr('class', 'aligncenter size-full');
            pq($img)->removeAttr('width');
            pq($img)->removeAttr('height');
            pq($img)->removeAttr('style');
        }
        #end
        $node = pq("div.single-post-box")->children("div.post-content:first");
        $this->cleanContentObject($node);
        $this->content = $node->html();
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
            $this->featured_image = pq('div.post-gallery img')->attr('src');
        }
    }

    protected function generateSeoMetaTitle() {
        ;
    }

    protected function generateSeoMetaDescription() {
        $first_paragraph = substr($this->content, 0, strpos($this->content, '</p>') + 4);
        $first_paragraph = strip_tags($first_paragraph);
        $description = $this->stripKrJogjaTextFromDescription($first_paragraph);
        $description = $this->setMetaDescriptionLength($description);
        $description = ucfirst($description);
        $this->meta_description = $description;
    }

    protected function cleanUp() {
        parent::cleanUp();
    }

    private function stripKrJogjaTextFromDescription($words) {
        $words = preg_replace('/.*?krjogja.*?[\s]/', '', strtolower($words));
        $words = preg_replace('/^[^\s]++/', '', trim($words));
        return trim($words);
    }

}

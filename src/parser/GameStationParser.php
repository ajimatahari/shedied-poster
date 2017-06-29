<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParser;

class GameStationParser extends AbstractParser {

    protected function getPostDetail() {
        $doc = $this->curlGrabContent();
        if (function_exists('mb_convert_encoding')) {
            $doc = mb_convert_encoding($doc, "HTML-ENTITIES", "UTF-8");
        }
        $html = \phpQuery::newDocument($doc);
        #images treatment
        foreach (pq("#content-anchor-inner img") as $img) {
            $image = pq($img)->attr('data-lazy-src');
            pq($img)->attr('src', trim($image));
            pq($img)->attr('class', 'aligncenter size-full');
            pq($img)->removeAttr('width');
            pq($img)->removeAttr('height');
            pq($img)->removeAttr('sizes');
            pq($img)->removeAttr('srcset');
            pq($img)->removeAttr('data-lazy-src');
        }
        #end images treatment
        $node = pq("#content-anchor-inner");
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
            $this->featured_image = pq('div.featured-image-inner a')->attr('href');
        }
    }

    protected function generateSeoMetaDescription() {
        $first_paragraph = substr($this->content, 0, strpos($this->content, '</p>') + 4);
        $first_paragraph = strip_tags($first_paragraph);
        $description = $this->setMetaDescriptionLength($first_paragraph);
        $description = ucfirst($description);
        $this->meta_description = $description;
    }

}

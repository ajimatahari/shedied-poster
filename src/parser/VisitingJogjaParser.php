<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParser;

/**
 * Grabbing VisitingJogja.web.id
 */
class VisitingJogjaParser extends AbstractParser {

    protected function getPostDetail() {
        $doc = $this->curlGrabContent();
        if (function_exists('mb_convert_encoding')) {
            $doc = mb_convert_encoding($doc, "HTML-ENTITIES", "UTF-8");
        }
        $html = \phpQuery::newDocument($doc);

        $node = pq('div.prl-entry-content');
        $node->find('div[style="margin-bottom: 10px;"]')->remove();
        $this->cleanContentObject($node);
        $this->node = $node;
        $this->_latitudeLongitude($doc);
    }

    public function grab() {
        $this->getPostDetail();
        $this->_getFeaturedImage();
        $this->_setContent();
        $this->_getTags();
        $this->aggregateContent();
        $this->_getHost();
        $this->cleanUp();
        $this->generateSeoMetaTitle();
        $this->generateSeoMetaDescription();
        $this->generateSeoMetaKeywords();
    }

    protected function _setContent() {
        $this->content = $this->node->html();
    }

    protected function _getFeaturedImage() {
        if (!$this->no_image) {
            $this->featured_image = $this->node->find('a.prl-thumbnail img')->attr('src');
            $this->node->find('div.space-bot')->remove();
        }
    }

    protected function _latitudeLongitude($html) {
        //destination: new google.maps.LatLng( -8.120997, 110.514284)
        if (preg_match('/destination:\snew\sgoogle\.maps\.LatLng\((.*?)\)/', $html, $matches)) {
            if (isset($matches[1])) {
                list($lat, $long) = explode(',', $matches[1]);
                $this->latitude = $this->cleanAllSpaces(trim($lat));
                $this->longitude = $this->cleanAllSpaces(trim($long));
                $this->has_map = true;
            }
        }
    }

}

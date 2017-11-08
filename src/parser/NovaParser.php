<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParser;

class NovaParser extends AbstractParser {

    public function getPostDetail() {
        if ($this->source_category == 66) {
            //http://nova.grid.id/Sedap/Makanan/Tasty-Pasta-Tuna-Jamur?page=all
            $temp_url = $this->url . '?page=all';
            $this->setUrl($temp_url);
        }
        $doc = $this->curlGrabContent();
        if (function_exists('mb_convert_encoding')) {
            $doc = mb_convert_encoding($doc, "HTML-ENTITIES", "UTF-8");
        }
        $html = \phpQuery::newDocument($doc);
        if ($this->source_category == 66) {
            #sedap
            $this->node = pq("div.artikelnya");
            //foto di tengah gambar
            $this->node->find('figure')->contentsUnwrap();
            $this->node->find('figcaption')->remove();
            //set wordpress class
            $this->node->find('img')->attr('class', 'aligncenter size-full');

            $this->cleanContentObject($this->node);
        }
        if ($this->source_category == 67) {
            #horoskop
            $this->node = pq('div.latest-horoskop');
            $this->node->find('a')->remove();
            $this->node->find('h1')->remove();
            $this->node->find('h2')->remove();
            //$this->node->find('p:first')->remove();
            $this->cleanContentObject($this->node);
        }
    }

    protected function _setContent() {
        $this->content = $this->node->html();
    }

    public function grab() {
        $this->getPostDetail();
        $this->_getFeaturedImage();
        $this->_getTags();
        $this->_setContent();
        $this->aggregateContent();
        $this->_getHost();
        $this->cleanUp();
        $this->generateSeoMetaTitle();
        $this->generateSeoMetaDescription();
        $this->generateSeoMetaKeywords();
    }

    protected function _getHost() {
        $this->host = 'tabloid nova';
    }

    protected function _getTags() {
        if ($this->source_category == 66) {
            $this->tags = 'Aneka Resep Masakan';
        }
    }

    protected function _getFeaturedImage() {
        if ($this->source_category == 66) {
            $this->featured_image = pq('div.cover-thumb img')->attr('src');
        }
        if ($this->source_category == 67) {
            $this->featured_image = pq('div.latest-horoskop img')->attr('src');
            $this->node->find('img')->remove();
        }
    }

    protected function generateSeoMetaDescription() {
        $meta_description = pq('meta[name="description"]')->attr('content');
        if ($this->source_category == 67) {
            $meta_description = $this->title . ' - ' . $meta_description;
        }
        $this->meta_description = $meta_description;
    }

    protected function generateSeoMetaKeywords() {
        $meta_keywords = pq('meta[name="keywords"]')->attr('content');
        if ($this->source_category == 67) {
            $meta_keywords = 'Horoskop zodiak terkini,ramalan bintang hari ini,asmara,keuangan,pekerjaan';
        }
        $this->meta_keywords = $meta_keywords;
    }

}

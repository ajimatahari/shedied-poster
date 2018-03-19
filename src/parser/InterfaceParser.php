<?php

namespace SheDied\parser;

interface InterfaceParser {

    public function setUrl($url);

    public function getUrl();

    public function setCategoryId($id);

    public function getCategoryId();

    public function setCategoryName($name);

    public function getCategoryName();

    public function setFeaturedImage($image);

    public function getFeaturedImage();

    public function setContent($content);

    public function getContent();

    public function setNoImage($bool);

    public function getNoImage();

    public function setTitle($title);

    public function getTitle();

    public function setStatus($status);

    public function getStatus();

    public function setAuthorId($id);

    public function getAuthorId();

    public function setType($type);

    public function getType();

    public function setTags($tags);

    public function getTags();

    public function setTime($time);

    public function getTime();

    public function setSourceCategory($cat);

    public function getSourceCategory();

    public function setHost($host);

    public function getHost();

    public function setMetaDescription($desc);

    public function getMetaDescription();

    public function setMetaKeywords($keys);

    public function getMetaKeywords();

    public function setMetaTitle($title);

    public function getMetaTitle();

    public function setLatitude($latitude);

    public function getLatitude();

    public function setLongitude($longitude);

    public function getLongitude();

    public function setCommentStatus($status);

    public function getCommentStatus();

    public function hasMap();

    public function grab();

    public function toArray();

    public function toWordpressPost();

    public function getDefaultAttachID();
}

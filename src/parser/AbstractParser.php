<?php

namespace SheDied\parser;

use SheDied\parser\InterfaceParser;

abstract class AbstractParser implements InterfaceParser {

    protected $url;
    protected $category_id;
    protected $category_name;
    protected $featured_image;
    protected $no_image = false;
    protected $content;
    protected $title;
    protected $status;
    protected $author_id;
    protected $type;
    protected $tags;
    protected $time;
    protected $source_category;
    protected $host;
    protected $meta_title;
    protected $meta_description;
    protected $meta_keywords;
    protected $meta_description_length = 160;
    protected $node;
    protected $has_map = false;
    protected $latitude;
    protected $longitude;
    protected $comment_status = 'open';
    protected $post_this = true;

    public function __construct() {
        
    }

    public function toArray() {
        return get_object_vars($this);
    }

    abstract protected function getPostDetail();

    protected function cleanContentObject(\phpQueryObject $node) {
        //remove iframe
        $node->find('iframe')->remove();
        //remove noscript
        $node->find('noscript')->remove();
        //<div>content
        //to
        //<div><p>content
        $node->find('div')->wrap('<p/>');
        //<div><p>content
        //to
        //<p>content
        $node->find('div')->contentsUnwrap();
        //clean p from attributs mostly style, class
        $node->find('p')->removeAttr('*');
        //<li><p>content
        //to
        //<li>content
        $node->find('li > p')->contentsUnwrap();
        //<li style="">content
        //to
        //<li>content
        $node->find('li')->removeAttr('style');
        //remove br
        //$node->find('br')->remove();
        //<h2>content
        //to
        //<h3>content
        $node->find('h2')->wrap('<h3/>');
        $node->find('h2')->contentsUnwrap();
        //remove Baca juga or s.o like that
        $node->find('a:contains("Baca")')->remove();
    }

    protected function cleanUp() {
        $this->removeJSTags();
        $this->removeCSSTags();
        $this->removeInsTags();
        $this->removeEmptyHTMLTags();
    }

    protected function _getFeaturedImage() {
        
    }

    protected function _getTags() {
        
    }

    protected function generateSeoMetaTitle() {
        
    }

    protected function generateSeoMetaDescription() {
        
    }

    protected function generateSeoMetaKeywords() {
        $keywords = str_replace(' ', ',', $this->title);
        $this->meta_keywords = $keywords;
    }

    public function setUrl($url) {
        $this->url = strval($url);
        return $this;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setCategoryId($id) {
        $this->category_id = $id;
        return $this;
    }

    public function getCategoryId() {
        return $this->category_id;
    }

    public function setSourceCategory($cat) {
        $this->source_category = $cat;
        return $this;
    }

    public function getSourceCategory() {
        return $this->source_category;
    }

    public function setCategoryName($name) {
        $this->category_name = $name;
        return $this;
    }

    public function getCategoryName() {
        return $this->category_name;
    }

    public function setFeaturedImage($image) {
        $this->featured_image = $image;
        return $this;
    }

    public function getFeaturedImage() {
        return $this->featured_image;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function getContent() {
        return $this->content;
    }

    public function setNoImage($bool) {
        $this->no_image = (bool) $bool;
        return $this;
    }

    public function getNoImage() {
        return $this->no_image;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setAuthorId($id) {
        $this->author_id = $id;
        return $this;
    }

    public function getAuthorId() {
        return $this->author_id;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function getType() {
        return $this->type;
    }

    public function setTags($tags) {
        $this->tags = $tags;
        return $this;
    }

    public function getTags() {
        return $this->tags;
    }

    public function setTime($time) {
        $this->time = $time;
        return $this;
    }

    public function getTime() {
        return $this->time;
    }

    public function setHost($host) {
        $this->host = $host;
        return $this;
    }

    public function getHost() {
        return $this->host;
    }

    protected function _getHost() {
        $url = parse_url($this->url);
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $url['host'], $regs)) {
            $this->host = $regs['domain'];
        }
    }

    public function setMetaDescription($desc) {
        $this->meta_description = $desc;
        return $this;
    }

    public function getMetaDescription() {
        return $this->meta_description;
    }

    public function setMetaKeywords($keys) {
        $this->meta_keywords = $keys;
        return $this;
    }

    public function getMetaKeywords() {
        return $this->meta_keywords;
    }

    public function setMetaTitle($title) {
        $this->meta_title = $title;
        return $this;
    }

    public function getMetaTitle() {
        return $this->title;
    }

    public function toWordpressPost() {
        return array(
            'post_content' => $this->content,
            'post_status' => $this->status,
            'post_title' => ucwords($this->title),
            'post_category' => array($this->category_id),
            'post_author' => $this->author_id,
            'post_type' => $this->type,
            'tags_input' => $this->tags,
            'post_date' => $this->time,
            'comment_status' => $this->comment_status
        );
    }

    public function grab() {
        
    }

    protected function curlGrabContent() {
        try {
            $ch = curl_init();
            $parse = parse_url($this->url);
            $host = $parse['host'];
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: $host"));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Fiddler');
            curl_setopt($ch, CURLOPT_HEADER, false);
            $output = curl_exec($ch);

            $error = curl_error($ch);
            if ($error) {
                throw new \Exception($error);
            }

            curl_close($ch);
            return $output;
        } catch (\Exception $ex) {
            syslog(LOG_DEBUG, '[shedied poster] - gagal grab berita - ' . $ex->getMessage());
        }
    }

    protected function setMetaDescriptionLength($description) {
        if (strlen($description) > $this->meta_description_length) {
            $offset = ($this->meta_description_length - 3) - strlen($description);
            $description = substr($description, 0, strrpos($description, ' ', $offset)) . '...';
        }
        return $description;
    }

    protected function aggregateContent() {
        $content = str_replace(array("\r\n", "\r"), "\n", $this->content);
        $lines = explode("\n", $content);
        $new_lines = [];
        foreach ($lines as $i => $line) {
            if (!empty($line)) {
                $new_lines[] = trim($line);
            }
        }
        $this->content = implode($new_lines);
    }

    protected function removeEmptyHTMLTags() {
        $content = preg_replace('#<p[^>]*>(\xc2\xa0|\s|&nbsp;|</?\s?br\s?/?>)*</?p>#', '', $this->content);
        $content = preg_replace('#<div[^>]*>(\xc2\xa0|\s|&nbsp;|</?\s?br\s?/?>)*</?div>#', '', $content);
        $content = preg_replace('#<strong[^>]*>(\xc2\xa0|\s|&nbsp;|</?\s?br\s?/?>)*</?strong>#', '', $content);
        $content = preg_replace('#<p><strong[^>]*>(\xc2\xa0|\s|&nbsp;|</?\s?br\s?/?>)*</?strong></?p>#', '', $content);
        $this->content = $content;
    }

    protected function removeJSTags() {
        $content = preg_replace('/<script[^>]*>.*?<\/script>/', '', $this->content);
        $this->content = $content;
    }

    protected function removeInsTags() {
        $content = preg_replace('/<ins[^>]*>.*?<\/ins>/', '', $this->content);
        $this->content = $content;
    }

    protected function removeCSSTags() {
        $content = preg_replace('/<style[^>]*>.*?<\/style>/', '', $this->content);
        $this->content = $content;
    }

    protected function alterBlockQuote() {
        if (preg_match_all('/<blockquote[^>]*>(.*?)<\/blockquote>/s', $this->content, $matches, PREG_PATTERN_ORDER)) {
            if (isset($matches[1])) {
                foreach ($matches[1] as $key => $blockquote) {
                    $new_texts = strip_tags(trim($blockquote));
                    $this->content = str_replace(trim($matches[0][$key]), '<blockquote>' . $new_texts . '</blockquote>', $this->content);
                }
            }
        }
    }

    public function hasMap() {
        return $this->has_map;
    }

    public function setLatitude($latitude) {
        $this->latitude = $latitude;
        return $this;
    }

    public function setLongitude($longitude) {
        $this->longitude = $longitude;
        return $this;
    }

    public function getLatitude() {
        return $this->latitude;
    }

    public function getLongitude() {
        return $this->longitude;
    }

    public function setCommentStatus($status) {
        $status = strtolower(strval($status));
        if ($status == 'open' || $status == 'closed') {
            $this->comment_status = $status;
        }
        return $this;
    }

    public function getCommentStatus() {
        return $this->comment_status;
    }

    protected function cleanAllSpaces($string = '') {
        return preg_replace('/\s+/S', '', $string);
    }
    public function postThis() {
        return $this->post_this;
    }

}

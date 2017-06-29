<?php

namespace SheDied\parser;

use SheDied\parser\InterfaceParser;

class Collections {

    private $collections;

    public function __construct() {
        
    }

    public function addCollection(InterfaceParser $parser, $key = null) {
        if ($key === null) {
            $this->collections[] = $parser;
        } else {
            if (isset($this->collections[$key])) {
                throw new \Exception("Key $key already in use.");
            } else {
                $this->collections[$key] = $parser;
            }
        }
    }

    public function deleteCollection($key) {
        if (isset($this->collections[$key])) {
            unset($this->collections[$key]);
        } else {
            throw new \Exception("Invalid key $key.");
        }
    }

    public function getCollection($key) {
        if (isset($this->collections[$key])) {
            return $this->collections[$key];
        } else {
            throw new \Exception("Invalid key $key.");
        }
    }

    public function getCollections() {
        return $this->collections;
    }

    public function keyExists($key) {
        return isset($this->collections[$key]);
    }

    public function length() {
        return count($this->collections);
    }

    public function keys() {
        return array_keys($this->collections);
    }

}

<?php

namespace SheDied\parser;

class Controller {

    public function __construct() {
        
    }

    public function toArray() {
        return get_object_vars($this);
    }

}

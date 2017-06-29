<?php

namespace SheDied\parser;

use SheDied\parser\InterfaceParser;
use SheDied\WPWrapper;

class CWriter {

    protected static $dicFile = "dict.txt";

    protected static function findSym($word, $lines) {
        $res = $word;
        if (trim($word) != "" && count($lines) > 0) {
            foreach ($lines as $line_num => $line) {
                $arr = explode(",", trim($line));
                if (in_array($word, $arr)) {
                    shuffle($arr);
                    $res = trim($arr[0]);
                    if ($res == $word) {
                        $res = trim($arr[1]);
                    }
                    return $res;
                }
            }
        }
        return $res;
    }

    public static function rewrite(InterfaceParser $parser) {
        $array = explode(" ", $parser->getContent());
        $dir = WPWrapper::plugin_dir_path(__FILE__);
        $dictFile = str_replace("\\", "/", $dir) . "/src/parser/" . self::$dicFile;

        $lines = file($dictFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($array as $value) {
            $value = self::findSym(trim($value), $lines);
            $hasil[] = $value;
        }
        $new_content = implode(" ", $hasil);
        $parser->setContent($new_content);
    }

    public static function removeLinks(InterfaceParser $parser) {
        $new_content = preg_replace('#<a.*?>(.*?)</a>#i', '\1', $parser->getContent());
        $parser->setContent($new_content);
    }

    public static function removeHtmlComments(InterfaceParser $parser) {
        $new_content = preg_replace('/<!--.*?-->/s', '', $parser->getContent());
        $parser->setContent($new_content);
    }

    public static function rewriteURL(InterfaceParser $parser) {
        $parse = parse_url($parser->getUrl());
        $parser->setUrl("http://{$parse['host']}");
    }

    public static function addPrefixSuffix(InterfaceParser $parser, $additional) {
        $prefix = str_replace("{TITLE}", $parser->getTitle(), $additional['prefix']);
        $prefix = str_replace("{CATEGORY}", $parser->getCategoryName(), $additional['prefix']);
        $suffix = str_replace("{TITLE}", $parser->getTitle(), $additional['suffix']);
        $suffix = str_replace("{CATEGORY}", $parser->getCategoryName(), $additional['suffix']);
        $new_content = $prefix . "<br />" . $parser->getContent() . "<br />" . $suffix;
        $parser->setContent($new_content);
    }

}

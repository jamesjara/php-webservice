<?php
/**
 * Abstract Metadata Class
 *
 */
namespace JamesJara\X7Cloud\Response;

/**
 * AbstractMetadata for all frameworks
 */
class AbstractMetadata
{

    function jsonDecode($json)
    {
        $json = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $json);
        $return = json_decode(preg_replace('@"(\w*)"\s*:\s*(-?\d{9,})\s*([,|\}])@', '"$1":"$2"$3', $json), true);
        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                $echo = ' - Maximum stack depth exceeded';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $echo = ' - Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $echo = ' - Syntax error, malformed JSON';
                break;
            case JSON_ERROR_NONE:
                $echo = null;
                break;
        }
        if (isset($echo)) {
            var_dump($echo);
            // throw new Exception($echo);
            return false;
        }
        return $return;
    }
}
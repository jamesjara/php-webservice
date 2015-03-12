<?php
/**
 * Response for ExtJs Framework.
 *
 */
namespace JamesJara\X7Cloud\Response\Models;

use JamesJara\X7Cloud\Response\IResponse;
use JamesJara\X7Cloud\Response\AbstractResponse;

/**
 * ResponseExt for ExtJs Framework.
 *
 * Custom Class for specific response of ExtJs Sencha.
 */
class ResponseExt extends AbstractResponse implements IResponse
{

    public $showMetaData = true;

    public function showMetadata($showMetaData = true)
    {
        $this->showMetaData = $showMetaData;
    }

    public function showConfigRoot($showConfigRoot = true)
    {
        $this->showConfigRoot = $showConfigRoot;
    }

    /**
     * Returns response as json string fomratted to ExtJs Framework
     *
     * @return String
     */
    public function response()
    {
        $metadata = $this->metadata;
        $tmp = array();
        
        if ($this->showConfigRoot != false) {
            $tmp['root'] = $metadata['root'];
            $tmp['totalProperty'] = $metadata['totalProperty'];
            $tmp['successProperty'] = $metadata['successProperty'];
            $tmp['messageProperty'] = $metadata['messageProperty'];
        }
        
        $tmp[$metadata['root']] = (empty($this->data)) ? array() : $this->data;
        $tmp[$metadata['successProperty']] = $this->success;
        
        // if metadata Is String return as Raw else create new array
        if ($this->showMetaData != false)
            if ($metadata['onlyRaw'] === true) {
                $tmp['metaData'] = "|1|2|3|4|5|...5|||";
            } else {
                $tmp['metaData'] = $this->metadata;
            }
        $rawJson = json_encode($tmp);
        if ($metadata['onlyRaw'] === true) {
            return str_replace('"|1|2|3|4|5|...5|||"', $metadata['raw'], $rawJson);
        }
        
        return $rawJson;
    }

    /**
     * Set data for the response
     *
     * @return array
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Sets metadata structure data
     *
     * @param array $data            
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * Sets specific grid structure data
     *
     * @param array $data            
     */
    public function setGridData($data)
    {}

    /**
     * Sets specific form structure data
     *
     * @param array $data            
     */
    public function setFormData($data)
    {}

    /**
     * Sets response type
     *
     * @param int $type            
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Set success type
     *
     * @param int $success            
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }
}



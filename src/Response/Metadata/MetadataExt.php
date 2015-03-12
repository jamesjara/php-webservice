<?php
/**
 * Metadata for ExtJs Framework.
 *
 */
namespace JamesJara\X7Cloud\Response\Metadata;

use JamesJara\X7Cloud\Response\IMetadata;
use JamesJara\X7Cloud\Response\AbstractMetadata;

/**
 * Metadata for ExtJs Framework.
 *
 * Custom Class for specific Metadata of ExtJs Sencha.
 */
class MetadataExt extends AbstractMetadata implements IMetadata
{

    /**
     * Selected columns for the Active Record model
     *
     * @var array
     */
    private $ids = array();

    /**
     * Column model for GridPanel
     *
     * @var array
     */
    private $columns = array();

    /**
     * Fields model for DataStore
     *
     * @var array
     */
    private $fields = array();

    public $root = array(
        'root' => 'users',
        'totalProperty' => 'count',
        'successProperty' => 'ok',
        'messageProperty' => 'msg'
    );

    /**
     * Register an new column
     *
     * @param string $id            
     * @param string $title            
     * @param string $type            
     * @return void
     */
    public function addColumn($id, $title, $type)
    {
        array_push($this->ids, $id);
        $fields = array();
        $fields['name'] = $id;
        $fields['type'] = $type;
        array_push($this->fields, $fields);
        $columns = array();
        $columns['text'] = $title;
        $columns['dataIndex'] = $id;
        array_push($this->columns, $columns);
    }

    /**
     *
     * Load and parse Metadata From File
     *
     * @param unknown $path            
     */
    public function loadFromFile($path)
    {
        $json = file_get_contents($path);
        $decoded = $this->jsonDecode($json);
        
        // Get Fields
        $tmpFields = $decoded['fields'];
        $columns = $decoded['columns'];
        
        $fields = array();
        foreach ($tmpFields as $k => $v) {
            $fields[$v['name']] = $v;
        }
        
        foreach ($columns as $columnRecord) {
            
            if (isset($columnRecord['notSql']))
                continue;
            
            $id = $columnRecord['dataIndex'];
            $column = $columnRecord['text'];
            $type = $fields[$id]['type'];
            if (! isset($id))
                continue;
            $this->addColumn($id, $column, $type);
        }
        
        $this->root['root'] = $decoded['root'];
        $this->root['totalProperty'] = $decoded['totalProperty'];
        $this->root['successProperty'] = $decoded['successProperty'];
        $this->root['messageProperty'] = $decoded['messageProperty'];
        
        $this->raw = str_replace('<script>', '', str_replace('</script>', '', $json));
        $this->onlyRaw = true;
    }

    /**
     * alias function of metadata, with fancy name
     *
     * @alias metadata
     *
     * @param int $type            
     * @return array
     */
    public function get($type)
    {
        return $this->metadata($type);
    }

    /**
     * Returns all set of metadata: columns, fields, root
     *
     * @param int $type            
     * @return array
     */
    public function metadata($type)
    {
        // root data
        if ($type == 0) {
            $metadata = array();
            $tmp = array();
            $metadata['root'] = $this->root['root'];
            $metadata['totalProperty'] = $this->root['totalProperty'];
            $metadata['successProperty'] = $this->root['successProperty'];
            $metadata['messageProperty'] = $this->root['messageProperty'];
            if ($this->fields)
                $metadata['fields'] = $this->fields;
            if ($this->columns)
                $metadata['columns'] = $this->columns;
            $metadata['onlyRaw'] = $this->onlyRaw;
            $metadata['raw'] = $this->raw;
            return $metadata;
        } elseif ($type == 1) {
            
            return $this->fields;
        } elseif ($type == 2) {
            
            return $this->ids;
        } elseif ($type == 3) {
            
            return $this->columns;
        }
    }
}
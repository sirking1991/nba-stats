<?php

require_once('classes/custom_exceptions.php');

class Controller {

    public function __construct($args) {
        $this->args = $args;
    }

    public function export($type, $format) {
        
        try {
            $data = $this->dataSourceClass($type)->data($this->args);            
        
            if (!$data) exit("Error: No data found!");

            $exporter = $this->dataExporterClass($format);
            return $exporter->export($data);
        
        } catch (\InvalidType $e) {
            exit($e->getMessage());
        } catch (\InvalidFormat $e) {
            exit($e->getMessage());
        }
        
    }

    /**
     * returns data source class based on passed type
     */
    private function dataSourceClass($type) 
    {    
        if (!file_exists('classes/datasource/' . $type . '.php')) {            
            throw new InvalidType('Unsupported type: ' . $type);
        }

        include('classes/datasource/' . $type . '.php');
        $class = ucfirst($type) . 'DataSource';
        return new $class();
        
    }

    /**
     * returns data exporter class based on passed format
     */
    private function dataExporterClass($format) 
    {    
        if (!file_exists('classes/exporter/' . $format . '.php')) {    
            throw new InvalidFormat('Unsupported format: ' . $format);
        }    

        include('classes/exporter/' . $format . '.php');
        $class = strtoupper($format) . 'DataExporter';
        return new $class();
        
    }



}
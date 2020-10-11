<?php

require_once('vendor/autoload.php');

class Controller {

    public function __construct($args) {
        $this->args = $args;
    }

    public function export($type, $format) {
        
        $data = [];
        
        try {
            $data = $this->dataSourceClass($type)->data($this->args);            
        } catch (\Throwable $th) {
            exit('Error: Invalid type - ' . $th->getMessage());
        }
        
        if (!$data) exit("Error: No data found!");

        try {
            $exporter = $this->dataExporterClass($format);
            return $exporter->export($data);
        } catch (\Throwable $th) {
            exit('Error: Invalid format - ' . $th);
        }
        
    }

    /**
     * returns data exporter class based on passed format
     */
    private function dataSourceClass($type) 
    {    
        $class = ucfirst($type) . 'DataSource';
        include('classes/datasource/' . $type . '.php');
        if (class_exists($class)) return new $class();

        //throw new Exception('Unsupported type: ' . $class);
    }

    /**
     * returns data exporter class based on passed format
     */
    private function dataExporterClass($format) 
    {    
        $class = strtoupper($format) . 'DataExporter';
        include('classes/exporter/' . $format . '.php');
        if (class_exists($class)) return new $class();

        //throw new Exception('Unsupported format: ' . $class);
    }



}
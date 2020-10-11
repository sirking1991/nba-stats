<?php

require('interface.php');

class JSONDataExporter implements DataExporter {

    public function export($data)
    {
        header('Content-type: application/json');
        
        return json_encode($data->all());
    }

}
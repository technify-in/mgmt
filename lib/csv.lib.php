<?php
///From http://www.phpwest.com/scripts/87/DataBases/PHP_CSV_generator.html

class export2CSV{
    
    var $delimiter = ",";
    var $row_end = "\n";
    
    function exportDb2CSV($delimiter,$row_end){
        $this->delimiter = $delimiter;
        $this->row_end = $row_end;
    }
    
    function create_csv_file_header($data)
    {
        $row = "";
        if (count($data)>0){
            foreach ($data[0] as $key=>$val)
            {
              if(!is_int($key))
              {
                if ($row){
                    $row .= $this->delimiter . $key;
                }else{
                    $row .= $key;
                }
              }
            }
            $row .= $this->row_end;
        }
        return $row;
    }
    
    
    function create_csv_file_row($row)
    {
        $res = "";
        foreach ($row as $key=>$val)
        {
          if(!is_int($key))
          {
            if ($res){
                $res .= $this->delimiter .'"'. $val.'"';
            }else{
                $res .= '"'.$val.'"';
            }
          }
        }
        $res .= $this->row_end;
    
        return $res;
    }
    
    function create_csv_file($data)
    {
        $csv = $this->create_csv_file_header($data);
        foreach ($data as $key=>$val){
            $csv .= $this->create_csv_file_row($val);
        }
        return $csv;
    }
}
?>
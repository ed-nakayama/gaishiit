<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class JobsCsvImport implements ToCollection, WithStartRow
//class JobsImport implements ToArray
{
    /**
    * @param Collection $collection
    */
public $sheetData;

    public function __construct(){
		$this->sheetData = array();
    }


    public function collection(Collection $rows)
    {
    	foreach ($rows as $row) {
		    $this->sheetData[] = [
		    	'comp_name' => isset($row[0])  ? $row[0]  : '',
		    	'status'    => isset($row[1])  ? $row[1]  : '',
		    	'job_id'    => isset($row[2])  ? $row[2]  : '',
		    	'comment'   => isset($row[3])  ? $row[3]  : '',
		    ];
    	}

    }


    public function startRow(): int
    {
        return 2;
    }

}

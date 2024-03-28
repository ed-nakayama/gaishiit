<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class JobsImport implements ToCollection, WithStartRow
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
		    	'comp_id'       => isset($row[0])  ? $row[0]  : '',
		    	'comp_name'     => isset($row[1])  ? $row[1]  : '',
		    	'unit_name'     => isset($row[2])  ? $row[2]  : '',
		    	'cat_name'      => isset($row[3])  ? $row[3]  : '',
		    	'job_id'        => isset($row[4])  ? $row[4]  : '',
		    	'job_title'     => isset($row[5])  ? $row[5]  : '',
		    	'job_detail'    => isset($row[6])  ? $row[6]  : '',
		    	'job_detail_2'  => isset($row[7])  ? $row[7]  : '',
		    	'job_detail_3'  => isset($row[8])  ? $row[8]  : '',
		    	'job_detail_4'  => isset($row[9])  ? $row[9]  : '',
		    	'job_detail_5'  => isset($row[10]) ? $row[10] : '',
		    	'working_place' => isset($row[11]) ? $row[11] : '',
		    	'register_date' => isset($row[12]) ? $row[12] : '',
		    	'agent'         => isset($row[13]) ? $row[13] : '',
		    	'url'           => isset($row[14]) ? $row[14] : '',
		    	'kind'          => isset($row[15]) ? $row[15] : '',
		    	'open'          => isset($row[16]) ? $row[16] : '',
		    ];
    	}

    }


    public function startRow(): int
    {
        return 2;
    }

}

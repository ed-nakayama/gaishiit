<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
 
class JobsExport implements FromView
{
public $view;

    public function __construct($view){
		$this->view = $view;
    }
  
 public function view(): View
    {
        return $this->view;
    }

}

<?php

namespace App\Classes;

use App\Models\Strain;
use Maatwebsite\Excel\Concerns\ToModel;

class TransactionsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        
        if($row[1] == '') {
            return null;
        }

        // $exist_count = Strain::where('name', '=', preg_replace("/\r|\n/", "", $row[0]))->get()->count();
        // if($exist_count > 0) {
        //     return null;
        // }
        
        return new Strain([
            'name'     => preg_replace("/\r|\n/", "", $row[0]),
            'effect'    => preg_replace("/\r|\n/", "", $row[1]),
            'description'    => preg_replace("/\r|\n/", "", $row[2]), 
            'parent'    => preg_replace("/\r|\n/", "", $row[3])
        ]);
    }
}
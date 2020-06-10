<?php

namespace App\Imports;

use App\Timetable;
use Maatwebsite\Excel\Concerns\ToModel;

class TimetableImport implements ToModel
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Timetable([
            'hour_in' => $row[0],
            'hour_out' => $row[1],
            'created_by' => $this->user
        ]);
    }
}

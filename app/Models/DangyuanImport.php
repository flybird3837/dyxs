<?php
namespace App\Models;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DangyuanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        print_r($row);
        return new Dangyuan([
            'name'  => $row[0],
            'sex' => $row[1],
            'in_time'    => $row[2],
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}

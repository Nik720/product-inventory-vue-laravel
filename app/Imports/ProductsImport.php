<?php

namespace App\Imports;

use App\Products;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Products([
            'name' => $row[0],
            'description' => $row[1],
            'stock' => $row[2],
            'price' => $row[3],
            'sku' => $row[4]
        ]);
    }
}

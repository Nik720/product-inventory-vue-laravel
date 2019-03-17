<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Products;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{

    public function importFile(Request $request){

        if($request->hasFile('sample_file')){

            Excel::import(new ProductsImport,request()->file('sample_file'));
        }  
    } 

    public function exportFile($type){
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Content-Disposition: "attachment;filename=products."'.$type);
        header('Expires: 0');
        flush();
        if($type == 'xlsx') {
             return Excel::download(new ProductsExport, 'products.'.$type, \Maatwebsite\Excel\Excel::XLSX);
        } else {
             return Excel::download(new ProductsExport, 'products.'.$type, \Maatwebsite\Excel\Excel::CSV);
        }
       
    }      
}
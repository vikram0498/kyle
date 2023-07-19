<?php

namespace App\Imports;

use App\Models\Buyer;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithValidation;

class MultipleBuyerImport implements  Importable, WithHeadings, WithValidation
{
    public function import(array $data)
    {
        foreach ($data as $row) {
            $buyer = new Buyer();
            $buyer->first_name    = $row['first_name'];
            $buyer->last_name     = $row['last_name'];
            $buyer->email         = $row['email'];
            $buyer->save();
        }
    }

    public function rules()
    {
        return [
            'first_name' => ['required','string','max:255'],
            'last_name'  => ['required','string','max:255'],
            'email'      => ['required','email','unique:buyers,email,NULL,id,deleted_at,NULL'],
        ];
    }

   
    

}

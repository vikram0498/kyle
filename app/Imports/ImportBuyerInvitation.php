<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\BuyerInvitation; 

class ImportBuyerInvitation implements ToModel, WithStartRow
{
    private $rowCount = 0;
    private $insertedCount = 0;

    public function startRow(): int
    {
        return 2;
    }


    public function model(array $row)
    {
        $invitationArr = [];
        $email = $this->modifiedString($row[0]);
        
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            $invitationArr['email'] = $email;

            $emailExists = BuyerInvitation::where('email',$email)->exists();
            if(!$emailExists){
                $createBuyerInvitation = BuyerInvitation::create($invitationArr);

                if($createBuyerInvitation){
                    $this->insertedCount++;
                }
            }
        }

        ++$this->rowCount;
    }

    private function modifiedString($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function totalRowCount(): int
    {
        return $this->rowCount;
    }

    public function insertedCount(): int
    {
        return $this->insertedCount;
    }
}

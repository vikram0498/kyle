<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\BuyerInvitation; 
use App\Models\User; 

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
        $flag = true;
        $email = $this->modifiedString($row[0]);
        
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            $invitationArr['email'] = $email;

            $emailExists = BuyerInvitation::where('email',$email)->first();
            if($emailExists){
                $flag = false;
            }

            $emailExists = User::where('email',$email)->withTrashed()->exists();
            if($emailExists){
                $flag = false;
            }

            if($flag){
                $createBuyerInvitation = BuyerInvitation::create($invitationArr);

                if($createBuyerInvitation){
                    $subject = config('constants.reminder_mail_subject');

                    $createBuyerInvitation->sendInvitationEmail($subject,1);

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

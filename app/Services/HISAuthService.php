<?php 

namespace App\Services;

use Illuminate\Support\Facades\DB;

class HISAuthService
{
    public function getUserFromHIS(string $username, string $password): ?array
    {
        $user =  DB::connection('mysql2')
            ->table('user')
            ->select('UserID', 'Nama')
            ->where('UserID', $username)
            ->where('Password', md5($password))
            ->first();
        
        if (!$user) {
            return null;
        }

        return [
            'username' => $user->UserID,
            'name' => $user->Nama
        ];
    }

    
}
?>
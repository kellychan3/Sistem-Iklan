<?php 

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected $hisAuth;

    public function __construct(HISAuthService $hisAuth)
    {
        $this->hisAuth = $hisAuth;
    }
    
    public function login(string $username, string $password): User
    {
        $user = User::where('username', $username)->first();

        if($user && Hash::check($password, $user->password)) {
            return $user;
        }

        $hisUser = $this->hisAuth->getUserFromHIS($username, $password);

        if (!$hisUser) {
            throw ValidationException::withMessages([
                'loginError' => 'Username atau password salah'
            ]);
        }

        if(!$user) {
            return User::create([
                'username' => $hisUser['username'],
                'name' => $hisUser['name'],
                'password' => Hash::make($password),
            ]);
        }

        $user->update([
            'password' => Hash::make($password),
            'name' => $hisUser['name'],
        ]);

        return $user;
    }
}
?>
<?php

namespace Hea\Controllers;

use Hea\Router\Request;
use Hea\Models\Model;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $paramsRequest = $request->getBody();
        if (
            !empty($paramsRequest['email']) &&
            !empty($paramsRequest['password']) &&
            filter_var($paramsRequest['email'], FILTER_VALIDATE_EMAIL)
        ) {
            $stmt = Model::connect()->prepare("
            select email, password, role 
            from users
            where email=:email and password=md5(:password)");
            $stmt->execute(['email' => $paramsRequest['email'], 'password' => $paramsRequest['password']]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($user) {// login success
                $user['role'] == 2 ? $_SESSION['login_flag'] = true : $_SESSION['login_error_message'] = 'your account is not admin role';
            } else {// login fail
                $_SESSION['login_error_message'] = 'wrong email or password. Please try again !';
            }
        } else {
            $_SESSION['login_error_message'] = 'missing or wrong input data login. Please try again !';
        }
        header('Location: '. '/');
    }

    public function logout(Request $request)
    {
        if(isset($_SESSION['login_flag']) && $_SESSION['login_flag']) {
            unset($_SESSION['login_flag']);
        }
        header('Location: '. '/');
    }

}
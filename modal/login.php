<?php
include '../config/key.php';
include '../config/database.php';
include '../vendor/autoload.php';


use Firebase\JWT\JWT;

class Login
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }
    
    public function login($input = NULL)
    {
        $database = new Database;
        $conn = $database->connect();
        if (isset($input['number']) && isset($input['password'])){
            $stmt = $conn->prepare("SELECT * FROM users WHERE users_number = ?");
            $stmt->bind_param("s", $input['number']);
            $stmt->execute();
            $result =  $stmt->get_result()->fetch_assoc();
            if(password_verify($input['password'], $result['users_password']))
            {
                $data = [
                    'iat' => time(),
                    'exp' => time() + 3600,
                    'data' => [
                        'number' => $result['users_number'],
                        'name' => $result['users_name'],
                        'role' => $result['users_role']
                    ]
                ];
                http_response_code(200);
                $jwt = [
                    'token' => JWT::encode($data, KEY, 'HS256')
                ];
                echo json_encode($jwt);
                exit;
            } else {
                http_response_code(401);
                $res = [
                    'status' => 401,
                    'msg' => "Username / Password salah."
                ];
                echo json_encode($res);
                exit;
            }
        } else {
            http_response_code(401);
                $res = [
                    'status' => 401,
                    'msg' => "Number Password harus di isi."
                ];
            echo json_encode($res);
            exit;
        }
    }
}
?>
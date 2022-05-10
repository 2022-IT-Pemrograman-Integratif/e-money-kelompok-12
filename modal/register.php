<?php
include '../config/key.php';
include '../config/database.php';
include '../vendor/autoload.php';

use Firebase\JWT\JWT;

class Register
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        
    }
    public function register($input = NULL)
    {
        $database = new Database;
        $conn = $database->connect();

        if(isset($input['number']) && isset($input['name']) && isset($input['password']) && $input['password'] != "" && $input['number'] != "" && $input['name'] != "")
        {
            $stmt = $conn->prepare("SELECT * FROM users WHERE users_number = ?");
            $stmt->bind_param('s', $input['number']);
            $stmt->execute();
            $res = $stmt->get_result();
            $num_rows = $res->num_rows;
            if($num_rows === 0)
            {
                $role = "user";
                $date = date("Y-m-d H:i:s");
                $password = password_hash($input['password'], PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users(users_number, users_name, users_password, users_role, users_dateCreated) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param('sssss', $input['number'], $input['name'], $password, $role, $date);
                try {
                    $stmt->execute();
                    http_response_code(200);
                    $res = [
                        "status" => 200,
                        "msg" =>  "Users berhasil dibuat."
                    ];
                    echo json_encode($res);
                    exit;
                }
                catch (Exception $e)
                {
                    http_response_code(500);
                    $res = [
                        "status" => 500,
                        "msg" =>  "Internal Server Error: ". $e->getMessage() . "."
                    ];
                    echo json_encode($res);
                    exit;
                }
            }
            else
            {
                http_response_code(409);
                $res = [
                    "status" => 409,
                    "msg" => "Nomor sudah terdaftar"
                ];
                echo json_encode($res);
                exit;
            }
        }
        else
        {
            http_response_code(400);
            $res = [
                "status" => 400,
                "msg" =>  "Memerlukan data berupa nomor hp, nama, dan password untuk melakukan pembuatan akun."
            ];
            echo json_encode($res);
            exit;
        }
    }
}
?>
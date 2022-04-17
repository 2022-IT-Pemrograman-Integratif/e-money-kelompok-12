<?php
    include_once '../config/key.php';
    include_once '../config/database.php';
    include_once '../vendor/autoload.php';
    include_once '../modal/auth.php';

    use Firebase\JWT\JWT;

    class Users
    {
        public function __construct()
        {
            date_default_timezone_set('Asia/Jakarta');
        }
        
        public function users($number = NULL)
        {
            $auth = new Auth;
            $token = $auth->auth();
            $database = new Database;
            $conn = $database->connect();
            if($token->data->role == "admin"){
                if($number == NULL)
                {
                    http_response_code(200);
                    $sql = "SELECT users_number, users_name, users_balance FROM users";
                    $query = $conn->query($sql);
                    $result = $query->fetch_all(MYSQLI_ASSOC);
                    echo json_encode($result);
                    exit;
                }
                else
                {
                    $stmt = $conn->prepare("SELECT users_number, users_name, users_balance FROM users WHERE users_number = ?");
                    $stmt->bind_param("s", $number);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $num_rows = $res->num_rows;
                    if($num_rows === 1)
                    {
                        http_response_code(200);
                        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
                        exit;
                    }
                    else
                    {
                        http_response_code(400);
                        $res = [
                            'status' => 400,
                            'msg' => "Nomor tidak ditemukan."
                        ];
                        echo json_encode($res);
                        exit;
                    }
                }  
            } else {
                $stmt = $conn->prepare("SELECT users_number, users_name, users_balance FROM users WHERE users_number = ?");
                $stmt->bind_param("s", $token->data->number);
                $stmt->execute();
                $res = $stmt->get_result();
                $num_rows = $res->num_rows;
                if($num_rows === 1)
                {
                    http_response_code(200);
                    echo json_encode($res->fetch_all(MYSQLI_ASSOC));
                    exit;
                }
            }
        }
    }
?>
<?php
    include_once '../config/key.php';
    include_once '../config/database.php';
    include_once '../vendor/autoload.php';
    include_once '../modal/auth.php';

    use Firebase\JWT\JWT;

    class Topup
    {
        public function __construct()
        {
            date_default_timezone_set('Asia/Jakarta');
        }
        
        public function topup($input = NULL)
        {
            $auth = new Auth;
            $token = $auth->auth();
            $database = new Database;
            $conn = $database->connect();
            if($token->data->role == "admin"){
                if($input == NULL || $input['number'] == NULL || $input['amount'] == NULL)
                {
                    http_response_code(400);
                    $res = [
                        'status' => 400,
                        'msg' => "Perlu data berupa nomor dan jumlah uang untuk topup ke akun user."
                    ];
                    echo json_encode($res);
                    exit;
                }
                else
                {
                    $stmt = $conn->prepare("SELECT * FROM users WHERE users_number = ?");
                    $stmt->bind_param('s', $input['number']);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $result = $res->fetch_assoc();
                    $num_rows = $res->num_rows;
                    if($num_rows === 1)
                    {
                        $balance = $result['users_balance'] + $input['amount']; 
                        $stmt = $conn->prepare("UPDATE users SET users_balance = ? WHERE users_number = ?");
                        $stmt->bind_param('si', $balance, $input['number']);
                        try {
                            $stmt->execute();
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
                        
                        $date = date("Y-m-d H:i:s");
                        $stmt = $conn->prepare("INSERT INTO history_topup(history_topup_number, history_topup_name, history_topup_amount, history_topup_date) VALUE (?, ?, ?, ?)");
                        $stmt->bind_param('ssis', $input['number'], $result['users_name'], $input['amount'], $date);
                        try {
                            $stmt->execute();
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
                        
                        http_response_code(200);
                            $res = [
                                "status" => 200,
                                "msg" =>  "Topup berhasil dilakukan."
                            ];
                        echo json_encode($res);
                        exit;
                    }
                    else {
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
                http_response_code(401);
                $res = [
                    'status' => 401,
                    'msg' => "Tidak memiliki hak akses."
                ];
                echo json_encode($res);
                exit;
            }
        }
    }
?>
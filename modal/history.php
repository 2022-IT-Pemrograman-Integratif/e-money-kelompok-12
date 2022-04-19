<?php
    include_once '../config/key.php';
    include_once '../config/database.php';
    include_once '../vendor/autoload.php';
    include_once '../modal/auth.php';

    use Firebase\JWT\JWT;

    class History
    {
        public function __construct()
        {
            date_default_timezone_set('Asia/Jakarta');
        }
        
        public function history($number = NULL)
        {
            $auth = new Auth;
            $token = $auth->auth();
            $database = new Database;
            $conn = $database->connect();
            if($token->data->role == "admin"){
                if($number == NULL)
                {
                    $sql_history_topup = "SELECT history_topup_number, history_topup_amount, history_topup_date FROM history_topup";
                    $query_history_topup = $conn->query($sql_history_topup);
                    $history_topup = $query_history_topup->fetch_all(MYSQLI_ASSOC);
                    
                    $sql_history_transfer = "SELECT history_transfer_number, history_transfer_tujuan, history_transfer_amount, history_transfer_date FROM history_transfer";
                    $query_history_transfer = $conn->query($sql_history_topup);
                    $history_transfer = $query_history_transfer->fetch_all(MYSQLI_ASSOC);

                    http_response_code(200);
                    $result = [
                        'history_topup' => $history_topup,
                        'history_transfer' => $history_transfer
                    ];
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
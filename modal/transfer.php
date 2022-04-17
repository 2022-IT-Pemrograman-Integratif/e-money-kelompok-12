<?php
    include_once '../config/key.php';
    include_once '../config/database.php';
    include_once '../vendor/autoload.php';
    include_once '../modal/auth.php';

    use Firebase\JWT\JWT;

    class Transfer
    {
        public function __construct()
        {
            date_default_timezone_set('Asia/Jakarta');
        }
        
        public function transfer($input = NULL)
        {
            $auth = new Auth;
            $token = $auth->auth();
            $database = new Database;
            $conn = $database->connect();
            
            if($input['tujuan'] == $token->data->number){
                http_response_code(400);
                $res = [
                    'status' => 400,
                    'msg' => "Tidak bisa transfer ke nomor sendiri."
                ];
                echo json_encode($res);
                exit;
            }
            
            if($input == NULL || $input['tujuan'] == NULL || $input['amount'] == NULL)
            {
                http_response_code(400);
                $res = [
                    'status' => 400,
                    'msg' => "Perlu data berupa tujuan dan jumlah uang untuk transfer ke akun lain."
                ];
                echo json_encode($res);
                exit;
            }
            else
            {
                $stmt_asal = $conn->prepare("SELECT * FROM users WHERE users_number = ?");
                $stmt_asal->bind_param('s', $token->data->number);
                $stmt_asal->execute();
                $res_asal = $stmt_asal->get_result();
                $result_asal = $res_asal->fetch_assoc();
                $num_rows_asal = $res_asal->num_rows;
                
                $stmt_tujuan = $conn->prepare("SELECT * FROM users WHERE users_number = ?");
                $stmt_tujuan->bind_param('s', $input['tujuan']);
                $stmt_tujuan->execute();
                $res_tujuan = $stmt_tujuan->get_result();
                $result_tujuan = $res_tujuan->fetch_assoc();
                $num_rows_tujuan = $res_tujuan->num_rows;

                if($num_rows_asal === 1 && $num_rows_tujuan === 1)
                {
                    if($result_asal['users_balance'] - $input['amount'] < 0){
                        http_response_code(400);
                        $res = [
                            'status' => 400,
                            'msg' => "Balance tidak memenuhi."
                        ];
                        echo json_encode($res);
                        exit;
                    }
                    else {
                        $balance_asal = $result_asal['users_balance'] - $input['amount']; 
                        $balance_tujuan = $result_tujuan['users_balance'] + $input['amount']; 

                        $stmt = $conn->prepare("UPDATE users SET users_balance = ? WHERE users_number = ?");
                        $stmt->bind_param('si', $balance_asal, $token->data->number);
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

                        $stmt = $conn->prepare("UPDATE users SET users_balance = ? WHERE users_number = ?");
                        $stmt->bind_param('si', $balance_tujuan, $input['tujuan']);
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
                                "msg" =>  "Transfer berhasil dilakukan."
                            ];
                            echo json_encode($res);
                        exit;
                    }
                }
                else {
                    http_response_code(400);
                    $res = [
                        'status' => 400,
                        'msg' => "Nomor tujuan tidak ditemukan."
                    ];
                    echo json_encode($res);
                    exit;
                }
            }  
        }
    }
?>
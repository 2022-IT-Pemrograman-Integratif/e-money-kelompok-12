<?php
    include_once '../config/key.php';
    include_once '../config/database.php';
    include_once '../vendor/autoload.php';
    include_once '../modal/auth.php';

    use Firebase\JWT\JWT;

    class Buskidicoin
    {
        public function __construct()
        {
            date_default_timezone_set('Asia/Jakarta');
        }
        
        public function buskidicoin($input = NULL)
        {
            $auth = new Auth;
            $token = $auth->auth();
            $database = new Database;
            $conn = $database->connect();
            
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
            
                if($num_rows_asal === 1)
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
                        $ch = curl_init();

                        $url = "https://arielaliski.xyz/e-money-kelompok-2/public/buskidicoin/publics/login";
                        $data = [
                            "username" => "PeacePay",
                            "password" => "PeacePay"
                        ];
                    
                        curl_setopt_array($ch, [
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => $data,
                            CURLOPT_HTTPHEADER => [
                                "Content-Type: multipart/form-data"
                            ]
                        ]);
                    
                        $res = curl_exec($ch);
                    
                        if($e = curl_error($ch)) {
                            echo $e;
                        }
                        else {
                            $result = json_decode($res);
                            $jwt = $result->message->token;
                        }
                        curl_close($ch);
                                            
                        $ch = curl_init();

                        $url = "https://arielaliski.xyz/e-money-kelompok-2/public/buskidicoin/admin/transfer";
                        $data = [
                            "nomer_hp" => "082169420720",
                            "nomer_hp_tujuan" => $input['tujuan'],
                            "e_money_tujuan" => "Buski Coins",
                            "amount" => $input['amount']
                        ];
                    
                        curl_setopt_array($ch, [
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => $data,
                            CURLOPT_HTTPHEADER => [
                                "Content-Type: multipart/form-data",
                                "Authorization: Bearer " . $jwt
                            ]
                        ]);
                    
                        $res = curl_exec($ch);
                        $result = json_decode($res);
                        $status = $result->status;
                    
                        if($e = curl_error($ch)) {
                            echo $e;
                        }
                        else {
                            curl_close($ch);
                            if($status == 201) {
                                $balance = $result_asal['users_balance'] - $input['amount'];
                                $number = $token->data->number;

                                $stmt = $conn->prepare("UPDATE users SET users_balance = ? WHERE users_number = ?");
                                $stmt->bind_param('ss', $balance, $number);
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
                                $emoney = "Buskidicoin";
                                $stmt = $conn->prepare("INSERT INTO history_transfer(history_transfer_number, history_transfer_number_name, history_transfer_tujuan, history_transfer_tujuan_name, history_transfer_amount, history_transfer_date) VALUE (?, ?, ?, ?, ?, ?)");
                                $stmt->bind_param('ssssis', $number, $result_asal['users_name'], $input['tujuan'], $emoney, $input['amount'], $date);
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
                                
                                $admin = "082140605035";
                                $stmt = $conn->prepare("SELECT * FROM users WHERE users_number = ?");
                                $stmt->bind_param('s', $admin);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                $result = $res->fetch_assoc();

                                $balance = $result['users_balance'] + $input['amount'];
                                $stmt = $conn->prepare("UPDATE users SET users_balance = ? WHERE users_number = ?");
                                $stmt->bind_param('ss', $balance, $admin);
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
                            else 
                            {
                                http_response_code(200);
                                $res = [
                                    "status" => 400,
                                    "msg" =>  "Transfer gagal dilakukan."
                                ];
                                echo json_encode($res);
                                exit;
                            }
                        }
                    }
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
        }
    }
?>
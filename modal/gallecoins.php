<?php
    include_once '../config/key.php';
    include_once '../config/database.php';
    include_once '../vendor/autoload.php';
    include_once '../modal/auth.php';

    use Firebase\JWT\JWT;

    class Gallecoins
    {
        public function __construct()
        {
            date_default_timezone_set('Asia/Jakarta');
        }
        
        public function gallecoins($input = NULL)
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

                        $url = "https://gallecoins.herokuapp.com/api/users";
                        $data = [
                            "username" => "PeacePay",
                            "password" => "PeacePay"
                        ];
                        $encode_data = json_encode($data);
                    
                        curl_setopt_array($ch, [
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => $encode_data,
                            CURLOPT_HTTPHEADER => [
                                "Accept: application/json",
                                "Content-Type: application/json"
                            ]
                        ]);
                    
                        $res = curl_exec($ch);
                    
                        if($e = curl_error($ch)) {
                            echo $e;
                        }
                        else {
                            $result = json_decode($res);
                            $jwt = $result->token;
                        }
                        curl_close($ch);
                                            
                        $ch = curl_init();

                        $url = "https://gallecoins.herokuapp.com/api/transfer";
                        $data = [
                            "amount" => $input['amount'],
                            "phone" => $input['tujuan'],
                            "description" => "Transfer from " . $token->data->number . " using PeacePay. Amount: " . $input['amount'] . "."
                        ];
                        $encode_data = json_encode($data);
                    
                        curl_setopt_array($ch, [
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => $encode_data,
                            CURLOPT_HTTPHEADER => [
                                "Content-Type: application/json",
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
                            if($status == 1) {
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
                                $emoney = "Gallecoins";
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
                                http_response_code(400);
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
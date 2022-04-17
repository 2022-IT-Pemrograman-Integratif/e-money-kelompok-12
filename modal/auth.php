<?php
    include_once '../config/key.php';
    include_once '../config/database.php';
    include_once '../vendor/autoload.php';

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    class Auth
    {
        public function __construct()
        {
            date_default_timezone_set('Asia/Jakarta');
        }
        
        public function auth()
        {
            $header = getallheaders();
            if(!isset($header['Authorization']))
            {
                http_response_code(401);
                $res = [
                    'status' => 401,
                    'msg' => "Token salah."
                ];
                echo json_encode($res);
                exit;
            }
            else
            {
                try
                {
                    $jwt_token = str_replace("Bearer ", "", $header['Authorization']);
                    $data = JWT::decode($jwt_token, new Key(KEY, 'HS256'));
                    if($data->exp - time() < 0)
                    {
                        http_response_code(401);
                        $res = [
                            'status' => 401,
                            'msg' => "Token kadaluarsa."
                        ];
                        echo json_encode($res);
                        exit;
                    }
                    return $data;
                }
                catch (Exception $e)
                {
                    http_response_code(401);
                    $res = [
                        'status' => 401,
                        'msg' => "Token salah."
                    ];
                    echo json_encode($res);
                    exit;
                }
            }
        }
    }
?>
<?php
    include '../modal/gallecoins.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $gallecoins = new Gallecoins;
        $input = json_decode(file_get_contents("php://input"), true);
        $gallecoins->gallecoins($input);
    }
    else {
        http_response_code(405);
        $res = [
            'status' => 405,
            'msg' => "Request method tidak ditersedia."
        ];
        echo json_encode($res);
    }
?>
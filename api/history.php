<?php
    include '../modal/history.php';
    
    if($_SERVER["REQUEST_METHOD"] == "GET") {
        $history = new History;
        $input = json_decode(file_get_contents("php://input"), true);
        $history->history(isset($_GET['number']) ? ($_GET['number']) : NULL);
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
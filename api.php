<?php
$key = "1cdbb159-0d61-42df-9d4c-0785416f1cda";
require_once('jsonrpcphp/includes/jsonRPCClient.php');
$bitcoin ="";

$port = "";
if (!isset($_GET['type'])) {
    if ($_GET['type'] == "bitcoin") {
        $port = 8332;
    }
    if ($_GET['type'] == "litecoin") {
        $port = 9332;
    }
    if ($_GET['type'] == "dogecoin") {
        $port = 22555;
    }
}

$bitcoin = new jsonRPCClient('http://bitcoinrpc:EuiuZEVwNBngVzZoer1GpVWADESf91gCFXw3N1Uxa1YN@127.0.0.1:'.$port.'/');

if (!isset($_GET['key'])) {
    $data = array("error"=>1,"errormsg"=>"Invalid Key","time"=>time());
    header('Content-Type: application/json');
    echo json_encode($data);
	exit;
}
$gkey = $_GET['key'];
$gkey = mb_convert_encoding($gkey, 'UTF-8', 'UTF-8');
if ($gkey != $key) {
    $data = array("error"=>1,"errormsg"=>"Invalid Key","time"=>time());
    header('Content-Type: application/json');
    echo json_encode($data);
	exit;
}
if (!isset($_GET['command'])) {
    $data = array("error"=>2,"errormsg"=>"Invalid Command","time"=>time());
    header('Content-Type: application/json');
    echo json_encode($data);
	exit;
}
$command = $_GET['command'];
$command = mb_convert_encoding($command, 'UTF-8', 'UTF-8');
if ($command == "getBalance") {
    $data = array("error"=>0,"errormsg"=>"","time"=>time(),"command"=>$command,"response"=>$bitcoin->getbalance());
    header('Content-Type: application/json');
    echo json_encode($data);
} else if ($command="sendFrom") {
    if (!isset($_GET['qr'])) {
        $data = array("error"=>3,"errormsg"=>"Invalid Wallet Address","time"=>time());
        header('Content-Type: application/json');
        echo json_encode($data);
	    exit;
    }
    $qr = $_GET['qr'];
    if (trim($qr) == "") {
        $data = array("error"=>3,"errormsg"=>"Invalid Wallet Address","time"=>time());
        header('Content-Type: application/json');
        echo json_encode($data);
	    exit;
    }
    if (!isset($_GET['amount'])) {
        $data = array("error"=>4,"errormsg"=>"Invalid Amount","time"=>time());
        header('Content-Type: application/json');
        echo json_encode($data);
	    exit;
    }
    $amount = $_GET['amount'];
    if (trim($amount) == "") {
        $data = array("error"=>4,"errormsg"=>"Invalid Amount","time"=>time());
        header('Content-Type: application/json');
        echo json_encode($data);
	    exit;
    }
    if (!is_numeric($amount)) {
        $data = array("error"=>4,"errormsg"=>"Invalid Amount","time"=>time());
        header('Content-Type: application/json');
        echo json_encode($data);
	    exit;
    }
    //ok lets sends some coin
    try {
        $txid = @$bitcoin->sendfrom("john",(string)$qr,(float)$amount);
        $data = array("error"=>0,"errormsg"=>"","time"=>time(),"command"=>$command,"txid"=>$txid);
        header('Content-Type: application/json');
        echo json_encode($data);
	    exit;
    }  catch (Exception $e) {
        $data = array("error"=>5,"errormsg"=>"Error Processing Transaction","time"=>time());
        header('Content-Type: application/json');
        echo json_encode($data);
	    exit;
    }
    print_r($output);
} else {
    $data = array("error"=>2,"errormsg"=>"Invalid Command","time"=>time());
    header('Content-Type: application/json');
    echo json_encode($data);
	exit;
}
?>

<?php
//送られてきたPOSTデータを受け取って，JSONデータをデコードして$inに入れる．
$json_string = file_get_contents('php://input');
$data = json_decode(stripslashes($json_string),true);

//接続するデータベースの設定
$dsn = 'mysql:dbname=iotdata;host=XXX.XXX.X.XXX';
$user = 'XXXXXXX';
$password = 'XXXXXXXX';


//送られてきたデータを取り出す
$device_id = $data["device_id"];
$volume = $data["volume"];
$light = $data["light"];
$temp = $data["temp"];
$humi =$data ["humi"];

//データベースと接続
try {
    $dbh = new PDO($dsn, $user, $password);
    echo "DBに接続成功\n";
} catch (PDOException $e) {
    echo "接続失敗: " . $e->getMessage() . "\n";
    exit();
}

//sqlの発行
$sql = "INSERT INTO measure_data(device_id, datetime, volume, light, temp, humi) VALUES (:device_id, now(), :volume, :light, :temp, :humi)";

$sth = $dbh -> prepare($sql);
$sth -> bindValue(':device_id', $device_id);
$sth -> bindValue(':volume', $volume);
$sth -> bindValue(':light', $light);
$sth -> bindValue(':temp', $temp);
$sth -> bindValue(':humi', $humi);

$check = $sth -> execute();
print_r($sth -> errorInfo());

if ($check){
	print("sqlは成功\n");
} else {
	print("sqlは失敗\n");
}

//切断を閉じる
$sth = null;
$dbh = null;

?>

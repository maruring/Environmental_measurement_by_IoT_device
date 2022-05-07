<?php
//接続するデータベースの設定
$dsn = 'mysql:dbname=iotdata;host=192.168.2.117';
$user = 'maru3745';
$password = 'maruring';


$device_name = $_GET['device_name'];

//データベースと接続
try {
    $dbh = new PDO($dsn, $user, $password);
    //echo "DBに接続成功\n";
} catch (PDOException $e) {
    echo "接続失敗: " . $e->getMessage() . "\n";
    exit();
}


// getで受け取ったdevice_nameからdevice_idを取得
$sql = "SELECT device_id FROM device_info WHERE device_name=:device_name";
$sth = $dbh -> prepare($sql);
$sth -> bindValue(':device_name', $device_name);
$sth -> execute();
//$sth = $dbh -> query($sql);
$device_id = $sth->fetch(PDO::FETCH_ASSOC);
$device_id = $device_id['device_id'];
print_r($device_id);

// device_idから測定データを取得
$sql = null;
$sth = null;

$sql = "SELECT datetime, light, volume, temp, humi FROM measure_data WHERE device_id=:device_id";
$sth = $dbh -> prepare($sql);
$sth -> bindValue(':device_id', $device_id);
$sth->execute();
$sth = $dbh -> query($sql);
$result = $sth->fetchAll(PDO::FETCH_ASSOC);

print_r($result);


//切断を閉じる
$sth = null;
$dbh = null;

?>

<?php
//接続するデータベースの設定
$dsn = 'mysql:dbname=iotdata;host=192.168.2.117';
$user = 'maru3745';
$password = 'maruring';


$device_name = $_POST['device_name'];

//データベースと接続
try {
    $dbh = new PDO($dsn, $user, $password);
    echo "DBに接続成功\n";
} catch (PDOException $e) {
    echo "接続失敗: " . $e->getMessage() . "\n";
    exit();
}

// mysqlに保存されているdevice_id一覧を取得
$sql = "SELECT device_id FROM device_info";
$sth = $dbh -> query($sql);
$result = $sth->fetchAll(PDO::FETCH_ASSOC);

while(true){
    $device_id = rand(100000, 999999);

    if(in_array($device_id, $result)){
        echo "device_idが被っています。再度、device_idを取得します";
    } else {
        echo $device_id;
        break;
    }
}


//切断を閉じる
$sql = null;
$sth = null;

$sql = "INSERT INTO device_info(device_id, device_name) VALUES (:device_id, :device_name)";

$sth = $dbh -> prepare($sql);
$sth -> bindValue(':device_id', $device_id);
$sth -> bindValue(':device_name', $device_name);
$check = $sth -> execute();

if ($check){
	print("sqlは成功\n");
} else {
	print("sqlは失敗\n");
}

//切断を閉じる
$sth = null;
$dbh = null;

?>

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
$result = $sth->fetch(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    var_dump($row);
}


// 既存のdevice_idと被らないdevice_idをつける
// mysqlにdevice_nameとdevice_idを保存する
// 保存が成功した場合と失敗した場合で返却する画面を変化させる

?>

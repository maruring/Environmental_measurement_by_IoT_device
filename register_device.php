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
$sql = "SELECT * FROM device_info";
$sth = $dbh -> query($sql);
$result = $sth->fetchAll(PDO::FETCH_ASSOC);


while(true){
    // device_nameが重複していないか確認
    if(in_array($device_name, array_column($result, "device_name"))){
        // register_page.htmlにリダイレクトして、device_nameが被っていることを通知する
        header('Location: http://192.168.2.117/register_page.html');
        exit;
    } else {
        echo $device_name;
        echo "device_nameにかぶりはありません";
    }

    $device_id = rand(100000, 999999);
    // device_idが重複していないか確認
    if(in_array($device_id, array_column($result, "device_id"))){
        echo "device_idが被っています。再度、device_idを取得します";
    } else {
        echo $device_id;
        echo "device_idにかぶりはありません";
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
    header('Location: http://192.168.2.117/index.html');
} else {
	print("sqlは失敗しました\n");
}

//切断を閉じる
$sth = null;
$dbh = null;

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>reference_page</title>
    </head>
    <body>
        <h2>デバイスの名前を参照</h2>
        <form action="fetch_measure_data.php" method="get">
            <label for="device_name">デバイス名を入力</label>
            <?php
            //接続するデータベースの設定
            $dsn = 'mysql:dbname=iotdata;host=XXX.XXX.X.XXX';
            $user = 'XXXXXXX';
            $password = 'XXXXXXXX';

            //データベースと接続
            try {
                $dbh = new PDO($dsn, $user, $password);
                // echo 'DBに接続成功\n';
            } catch (PDOException $e) {
                echo '接続失敗: ' . $e -> getMessage() . '\n';
                exit();
            }

            $sql = "SELECT device_name FROM device_info";
            $sth = $dbh -> query($sql);
            $device_names = $sth -> fetchAll(PDO::FETCH_ASSOC);
            $device_names = array_column($device_names, 'device_name');

            //inputタグの作成
            $device_name_list = "<input type=\"text\" name=\"device_name\" autocomplete=\"on\" list=\"device_names\">\n"; 
            $device_name_list .= "<datalist id=\"device_names\">\n";
            for ( $i = 0; $i < count( $device_names ); $i++ ) {
                $device_name_list .= "\t<option value=\"{$device_names[$i]}\">{$device_names[$i]}</option>\n";
            }
            $device_name_list .= "</datalist>\n";
            echo "{$device_name_list}";
            ?>
            <input type='submit' value='送信'>
        </form>
        
        <a href='index.html'>初期ページ</a>
    </body>
</html>

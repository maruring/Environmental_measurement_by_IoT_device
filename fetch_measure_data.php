<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>fetch_measure_data</title>
    </head>
    <body>
        <h2><?php echo htmlspecialchars($_GET['device_name']); ?>のデータ</h2>

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
        $device_id = $sth->fetch(PDO::FETCH_ASSOC);
        $device_id = $device_id['device_id'];

        // device_idから測定データを取得
        $sql = null;
        $sth = null;

        $sql = "SELECT datetime, light, volume, temp, humi FROM measure_data WHERE device_id=:device_id";
        $sth = $dbh -> prepare($sql);
        $sth -> bindValue(':device_id', $device_id);
        $sth -> execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        $datetimes = array_column($result, 'datetime');
        $lights = array_column($result, 'light');
        $temps = array_column($result, 'temp');
        $humis = array_column($result, 'humi');

        print_r($datetimes);
        print_r($lights);
        print_r($temps);
        print_r($humis);

        $kari = (1,2;)

        //データを表で表示
        $data_table = "<table>\n";
            $data_table .= "<thead>\n";
                $data_table .= "<tr>\n";
                    $data_table .= "<th count( $kari )>データ一覧</th>\n";
                $data_table .= "</tr>\n";
            $data_table .= "</thead>\n";
            $data_table .= "<tbody>\n";
                $data_table .= "<tr>\n";
                    $data_table .= "<td>aa</td>\n";
                    $data_table .= "<td>bb</td>\n";
                $data_table .= "</tr>\n";
            $data_table .= "</tbody>\n";
        $data_table = "</table>\n";

        //切断を閉じる
        $sth = null;
        $dbh = null;

        ?>

        <a href='index.html'>初期ページ</a>
    </body>
</html>

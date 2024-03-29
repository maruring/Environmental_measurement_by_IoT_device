<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>fetch_measure_data</title>
    </head>
    <body>
        <h2><?php echo htmlspecialchars($_GET['device_name']); ?>のデータ</h2>

        <table width="80%" border="1">
            <tr>
                <th scope="col">時間</th>
                <th scope="col">照度</th>
                <th scope="col">音量</th>
                <th scope="col">気温</th>
                <th scope="col">湿度</th>
            </tr>
            <?php
                //接続するデータベースの設定
                $dsn = 'mysql:dbname=iotdata;host=XXX.XXX.X.XXX';
                $user = 'XXXXXXXXX';
                $password = 'XXXXXXXX';

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
                $volumes = array_column($result, 'volume');
                $temps = array_column($result, 'temp');
                $humis = array_column($result, 'humi');
                
                $data_table = null;
                for( $i = 0; $i < count( $datetimes ); $i++ ){
                    $data_table .= "<tr>\n";
                        $data_table .="<td>$datetimes[$i]</td>\n";
                        $data_table .="<td>$lights[$i]</td>\n";
                        $data_table .="<td>$volumes[$i]</td>\n";
                        $data_table .="<td>$temps[$i]</td>\n";
                        $data_table .="<td>$humis[$i]</td>\n";
                    $data_table .= "</tr>\n";
                }
                echo "{$data_table}";

                //切断を閉じる
                $sth = null;
                $dbh = null;
                ?>
                </table>
            <a href='reference_page.php'>データ参照ページ</a>
            <a href='index.html'>初期ページ</a>
    </body>
</html>

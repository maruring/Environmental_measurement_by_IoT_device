#include <rpcWiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <Wire.h>
#include "SHT31.h"
#include <SPI.h>

const char* ssid = "SSID";
const char* password =  "Password";
const char* postURL = "http://XXX.XXX.X.XXX:80/recive_data.php";
const int device_id = YYYY;

float volume;
float light;
float temp;
float humi;

SHT31 sht31 = SHT31();

StaticJsonDocument<JSON_OBJECT_SIZE(6)> data;
char json_string[255];

void setup() {
    Serial.begin(115200);
    delay(10);

    // We start by connecting to a WiFi network

    Serial.println();
    Serial.println();
    Serial.print("Connecting to ");
    Serial.println(ssid);

    WiFi.begin(ssid, password);

    while (WiFi.status() != WL_CONNECTED)
    {
        Serial.print("Connecting to ");
        Serial.println(ssid);
        WiFi.begin(ssid, password);
    }

    Serial.println("");
    Serial.println("WiFi connected");
    Serial.println("IP address: ");
    Serial.println(WiFi.localIP());

    //光センサ
    pinMode(WIO_LIGHT, INPUT);
    //マイク
    pinMode(WIO_MIC, INPUT);
    //室温度計セットアップ
    sht31.begin();
    
}

void loop() {
  if(WiFi.status()== WL_CONNECTED){
    volume = get_volume();
    light = get_light();
    temp = get_temp();
    humi = get_humi();
  
    data["device_id"] = device_id;
    data["volume"] = volume;
    data["light"] = light;
    data["temp"] = temp;
    data["humi"] = humi;
  
   // JSONフォーマットの文字列に変換する
    serializeJson(data, json_string, sizeof(json_string));
    Serial.println(json_string);
  
    HTTPClient http;
    // HTTPClinetでPOSTする
    http.begin(postURL);
    // postするのはjsonなので、Content-Typeをapplication/jsonにする
    http.addHeader("Content-Type", "application/json");
    
    // POSTしてステータスコードを取得する
    int status_code = http.POST((uint8_t*)json_string, strlen(json_string));
    Serial.println(status_code);
    if (status_code == 200)
    {
      Serial.printf("[POST]Send to server (URL:%s)", postURL);
      Serial.println();
    }
    else
    {
      Serial.printf("[POST]failed to send to server (URL:%s)", postURL);
      Serial.println();
    }
    delay(3000);
    // HTTPClinetを終了する
    http.end();

    }else{
    Serial.println("Error in WiFi connection");
  }
  delay(60000);
}

float get_volume(){
  float MIC = analogRead(WIO_MIC);
  return volume;
}

float get_light(){
  float light = analogRead(WIO_LIGHT);
  return light;
}

float get_temp(){
  float temp = sht31.getTemperature();
  return temp;
}

float get_humi(){
  float humi = sht31.getHumidity();
  return humi;
}

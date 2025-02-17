// Include the necessary libraries
#include <OneWire.h>
#include <DallasTemperature.h>
#include <LiquidCrystal_I2C.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <time.h>  // Library untuk NTP

// Ganti dengan kredensial WiFi kamu
#define WIFI_SSID "JOGLOBTP"
#define WIFI_PASSWORD ""

// Ganti dengan Firebase Project credentials untuk masing-masing Firestore
#define FIRESTORE_RESULT_PROJECT_ID "fishervice-result"
#define FIRESTORE_NTU_PROJECT_ID "ntu-container"
#define FIRESTORE_PH_PROJECT_ID "ph-container-73dbc"
#define FIRESTORE_TEMP_PROJECT_ID "temperature-container"

// API Key masing-masing Firestore
#define FIRESTORE_RESULT_API_KEY "AIzaSyCHQZyc8WbHVmuRiE0Z-V2icYScgRhjKck"
#define FIRESTORE_NTU_API_KEY "AIzaSyA7LZUxNcMybJmrlndExNXHZhlO2Yy1yAo"
#define FIRESTORE_PH_API_KEY "AIzaSyDrvM0uqhKR25Qd8va-5Ts_5zcMPYMv0h8"
#define FIRESTORE_TEMP_API_KEY "AIzaSyDl8An5AV1bFSSQREsWt70AYdnvAwkbL9w"

// Ganti dengan email pengguna yang akan menjadi nama koleksi
String userEmail = "khairiyahnisrina@gmail.com"; // Ganti sesuai dengan email pengguna
String documentPath = userEmail + "/latestData"; // Struktur koleksi

LiquidCrystal_I2C lcd(0x27, 20, 4); // I2C address 0x27, 20 columns, and 4 rows

unsigned long lastLCDUpdate = 0;
unsigned long lastFirebaseUpdate = 0;

const unsigned long LCDInterval = 10000; // 10 detik
const unsigned long FirebaseInterval = 600000; // 10 menit

// Data wire is plugged into port 5 on the Arduino
#define ONE_WIRE_BUS 5
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

int pHSense = 33;
int samples = 10;
float adc_resolution = 4095.0; // ESP32 ADC Resolution
const int turbiditySensorPin = 34;

// pH calculation function
float ph(float voltage) {
  return 7 + ((2.50 - voltage) / 0.18);
}

const char* ntpServer = "pool.ntp.org";
const long gmtOffset_sec = 3600 * 7; // Sesuaikan dengan zona waktu (WIB = UTC+7)
const int daylightOffset_sec = 0;

// Function to get the current time from NTP server
String getTime() {
  struct tm timeinfo;
  if (!getLocalTime(&timeinfo)) {
    Serial.println("Failed to obtain time");
    return "unknown-time";
  }
  char timeStringBuff[50];
  strftime(timeStringBuff, sizeof(timeStringBuff), "%Y-%m-%d-%H-%M-%S", &timeinfo); // Format: YYYY-MM-DD-HH-MM-SS
  return String(timeStringBuff);
}

// Function to send data to Firestore Result
void uploadToFirestore(String projectId, String apiKey, String collectionPath, float tempC, float pHValue, int ntu) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    StaticJsonDocument<200> jsonDoc;

    // Data yang dikirim sesuai collectionPath
    String currentTime = getTime();
    jsonDoc["fields"]["timestamp"]["stringValue"] = currentTime;
    jsonDoc["fields"]["tempC"]["doubleValue"] = tempC;
    jsonDoc["fields"]["pHValue"]["doubleValue"] = pHValue;
    jsonDoc["fields"]["ntu"]["integerValue"] = ntu;

    String encodedEmail = urlEncode(userEmail);
    String url = "https://firestore.googleapis.com/v1/projects/" + projectId + "/databases/(default)/documents/" + encodedEmail + "/latestData?key=" + apiKey;

    // Menggunakan metode set untuk mengganti data
    http.begin(url);
    http.addHeader("Content-Type", "application/json");
    String requestBody;
    serializeJson(jsonDoc, requestBody);
    int httpResponseCode = http.PATCH(requestBody);  // PATCH digunakan untuk mengganti seluruh dokumen
    Serial.print("PATCH Response Code for result: ");
    Serial.println(httpResponseCode);

    http.end();
  } else {
    Serial.println("Error in WiFi connection");
  }
}

void uploadNTUToFirestore(String projectId, String apiKey, String collectionPath, int ntu) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    StaticJsonDocument<200> jsonDoc;

    // Timestamp
    String currentTime = getTime();

    // Isi data dokumen
    jsonDoc["fields"]["NTU"]["doubleValue"] = ntu;
    jsonDoc["fields"]["timestamp"]["stringValue"] = currentTime;

    String encodedEmail = urlEncode(userEmail);
    String url = "https://firestore.googleapis.com/v1/projects/" + projectId + "/databases/(default)/documents/" + encodedEmail + "?key=" + apiKey;

    // Konfigurasi HTTP
    http.begin(url);
    http.addHeader("Content-Type", "application/json");
    String requestBody;
    serializeJson(jsonDoc, requestBody);

    // Kirim POST request
    Serial.println("Request Body:");
    Serial.println(requestBody);  // Untuk debug
    int httpResponseCode = http.POST(requestBody);
    Serial.print("POST Response Code for NTU Data: ");
    Serial.println(httpResponseCode);

    http.end();
  } else {
    Serial.println("Error in WiFi connection");
  }
}

void uploadTempToFirestore(String projectId, String apiKey, float tempC) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    StaticJsonDocument<200> jsonDoc;

    // Timestamp
    String currentTime = getTime();

    // Isi data dokumen
    jsonDoc["fields"]["TempC"]["doubleValue"] = tempC;
    jsonDoc["fields"]["timestamp"]["stringValue"] = currentTime;

   // Generate URL (tanpa Document ID untuk POST ke koleksi)
    String encodedEmail = urlEncode(userEmail);
    String url = "https://firestore.googleapis.com/v1/projects/" + projectId + "/databases/(default)/documents/" + encodedEmail + "?key=" + apiKey;

    // Konfigurasi HTTP
    http.begin(url);
    http.addHeader("Content-Type", "application/json");
    String requestBody;
    serializeJson(jsonDoc, requestBody);

    // Kirim POST request
    Serial.println("Request Body:");
    Serial.println(requestBody);  // Untuk debug
    int httpResponseCode = http.POST(requestBody);
    Serial.print("POST Response Code for Temp Data: ");
    Serial.println(httpResponseCode);

    http.end();
  } else {
    Serial.println("Error in WiFi connection");
  }
}

void uploadPHToFirestore(String projectId, String apiKey, float pHValue) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    StaticJsonDocument<200> jsonDoc;

    // Timestamp
    String currentTime = getTime();

    // Isi data dokumen
    jsonDoc["fields"]["pHValue"]["doubleValue"] = pHValue;
    jsonDoc["fields"]["timestamp"]["stringValue"] = currentTime;

    // Generate URL (tanpa Document ID untuk POST ke koleksi)
    String encodedEmail = urlEncode(userEmail);
    String url = "https://firestore.googleapis.com/v1/projects/" + projectId + "/databases/(default)/documents/" + encodedEmail + "?key=" + apiKey;

    // Konfigurasi HTTP
    http.begin(url);
    http.addHeader("Content-Type", "application/json");
    String requestBody;
    serializeJson(jsonDoc, requestBody);

    // Kirim POST request
    Serial.println("Request Body:");
    Serial.println(requestBody);  // Untuk debug
    int httpResponseCode = http.POST(requestBody);
    Serial.print("POST Response Code for pH Data: ");
    Serial.println(httpResponseCode);

    http.end();
  } else {
    Serial.println("Error in WiFi connection");
  }
}

// Function to URL encode the email
String urlEncode(String str) {
  String encoded = "";
  for (int i = 0; i < str.length(); i++) {
    char c = str.charAt(i);
    if (isalnum(c) || c == '-' || c == '_' || c == '.' || c == '~') {
      encoded += c;
    } else {
      encoded += '%';
      encoded += String((int)c, HEX);
    }
  }
  return encoded;
}

void setup() {
  Serial.begin(115200);
  sensors.begin();
  Wire.begin(18, 19);
  lcd.init();
  lcd.backlight();
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(500);
  }
  Serial.println("Connected to Wi-Fi");
  configTime(gmtOffset_sec, daylightOffset_sec, ntpServer);
}

void loop() {
  unsigned long currentMillis = millis();

  // Update LCD setiap 10 detik
  if (currentMillis - lastLCDUpdate >= LCDInterval) {
    lastLCDUpdate = currentMillis;

    sensors.requestTemperatures();
    float tempC = sensors.getTempCByIndex(0);
    int measurings = 0;
    for (int i = 0; i < samples; i++) {
      measurings += analogRead(pHSense);
      delay(10);
    }
    float voltage = 3.3 / adc_resolution * measurings / samples;
    float pHValue = ph(voltage);
    int turbidityValue = analogRead(turbiditySensorPin);
    int ntu = map(turbidityValue, 0, 2200, 100, 0);
    ntu = max(ntu, 0);

    String status;
    if (ntu < 20) {
      status = "CLEAR";
    } else if (ntu >= 20 && ntu < 50) {
      status = "CLOUDY";
    } else if (ntu >= 50) {
      status = "DIRTY";
    }

    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Suhu: "); lcd.print(tempC); lcd.print((char)223); lcd.print("C");
    lcd.setCursor(0, 1);
    lcd.print("pH: "); lcd.print(pHValue);
    lcd.setCursor(0, 2);
    lcd.print("NTU: "); lcd.print(ntu);
    lcd.setCursor(0, 3);
    lcd.print("Status: "); lcd.print(status);
  }

  // Kirim data ke Firebase setiap 10 menit
  if (currentMillis - lastFirebaseUpdate >= FirebaseInterval) {
    lastFirebaseUpdate = currentMillis;

    sensors.requestTemperatures();
    float tempC = sensors.getTempCByIndex(0);
    int measurings = 0;
    for (int i = 0; i < samples; i++) {
      measurings += analogRead(pHSense);
      delay(10);
    }
    float voltage = 3.3 / adc_resolution * measurings / samples;
    float pHValue = ph(voltage);
    int turbidityValue = analogRead(turbiditySensorPin);
    int ntu = map(turbidityValue, 0, 2200, 100, 0);
    ntu = max(ntu, 0);

    uploadToFirestore(FIRESTORE_RESULT_PROJECT_ID, FIRESTORE_RESULT_API_KEY, "Result", tempC, pHValue, ntu);
    uploadNTUToFirestore(FIRESTORE_NTU_PROJECT_ID, FIRESTORE_NTU_API_KEY, "ntu-page", ntu);
    uploadPHToFirestore(FIRESTORE_PH_PROJECT_ID, FIRESTORE_PH_API_KEY, pHValue);
    uploadTempToFirestore(FIRESTORE_TEMP_PROJECT_ID, FIRESTORE_TEMP_API_KEY, tempC);
  }
}
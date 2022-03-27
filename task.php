<?php 
class Task{
    protected $token;
    public $application_id;
    public $secret;
    public $whetherAppId;
    public $lat;
    public $lon;
    public $phone;

    public function createAccessToken(){
        $base64 = base64_encode($this->application_id.":".$this->secret);
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://auth.routee.net/oauth/token",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "grant_type=client_credentials",
          CURLOPT_HTTPHEADER => array(
            "authorization: Basic ".$base64,
            "content-type: application/x-www-form-urlencoded"
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          return false;
        } else {
          $token = json_decode($response);
          $this->token =  $token->access_token;
          return true;
        }
    }

    public function getDataFromWeatherMap(){
        $api_data = 'https://api.openweathermap.org/data/2.5/weather?lat='.$this->lat.'&lon='.$this->lon.'&appid='.$this->whetherAppId;
        $json = file_get_contents($api_data);
        $decode_json = json_decode($json);
        $mainTemp = $decode_json->main->temp;
        // $K_to_C  Kelvin to Celius
        $K_to_C = $mainTemp - 273;
       if($K_to_C > 20){
        return 'Kaleemullah and Temperature more than 20C. <'.$K_to_C.'>';
       }else{
        return  'Kaleemullah and Temperature less than 20C. <'.$K_to_C.'>';
       }
    }

    public function sendMessageToUser(){
    $curl = curl_init();
    $text = $this->getDataFromWeatherMap();
    $this->createAccessToken(); 
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://connect.routee.net/sms",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{ \"body\": \"".$text."\",\"to\" : \"".$this->phone."\",\"from\": \"Kaleemullah\"}",
      CURLOPT_HTTPHEADER => array(
        "authorization: Bearer ".$this->token,
        "content-type: application/json"
      ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
      return false;
    } else {
      return true;
    }
    }
}

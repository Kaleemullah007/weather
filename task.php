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

        // echo $this->application_id ."<br>";
        // echo $this->secret ."<br>";
        // echo $this->lat ."<br>";
        // echo $this->lon ."<br>";
        // echo $this->whetherAppId ."<br>";
        // echo $this->phone ."<br>";
        // die();
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
    //   echo $response;
      return true;
    }
    }
}
// $task = new Task();
// $task->application_id = '62401e082d985400016d1a92:';
// $task->secret = 'toVqY7OVMN';
// $task->lat= 35;
// $task->lon = 139;
// $task->whetherAppId = 'b385aa7d4e568152288b3c9f5c2458a5';
// $check = $task->sendMessageToUser();
// $message =  "Message sent to user";
// if($check == false){
//     $message =  "Message didn't send to user";
// }












    
?>
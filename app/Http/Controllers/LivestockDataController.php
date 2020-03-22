<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LivestockData;
use SmsGatewayCredentials;

class LivestockDataController extends Controller
{
    //Function to send OTP
    function sendToPhone($phone = null, $message = null){
		if(($phone != null) & ($message != null)){
			#Send the message through the API
			$username = urlencode(SmsGatewayCredentials::$username);
			$password = urlencode(SmsGatewayCredentials::$password);
			$senderID = "IoT Systems";

			$url = "http://portal.nigeriabulksms.com/api/?username=" . $username . "&password=" . $password . "&message=". $message ."&sender=" . $senderID ."&mobiles=" . $phone;

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_HEADER, 0);

			$response = curl_exec($curl);
			
			curl_close($curl);

			return $response;
		}else{
			return false;
		}
    }
    
    public function store(Request $request, $nfc_uuid, $temperature, $humidity, $pulse_rate, $loc_latitude, $loc_longitude){
        
        //Create a new LivestockData instance to be stored in the database
        $livestockData = LivestockData::create([
            'nfc_uuid' => $nfc_uuid,
            'temperature' => $temperature,
            'humidity' => $humidity,
            'pulse_rate' => $pulse_rate,
            'loc_latitude' => $loc_latitude,
            'loc_longitude' => $loc_longitude
        ]);

        if($livestockData != null){
            //Prepare data to return
            // $data = [
            //     'status' => 'LIVESTOCK_DATA_STORED',
            //     'message' => 'The livestock data was added to the remote database',
            //     'data' => $livestockData
            // ];

            //Prepare to send SMS to the administrator
            $adminPhone = "08039207982,08135439547"; 
            // $googleMapURL = urlencode("https://www.google.com/maps/search/?api=1&query=" . $loc_latitude . "," . $loc_longitude . "&query_place_id=ChIJDWlWr43EQhARIGWERwJtADoE");
            $googleMapURL = rawurlencode("https://bit.ly/2ZBc13c");
            $healthParameters = "Livestock Data: " . $nfc_uuid . ", " . $temperature . "degCel., " . $humidity . "g/m^3, " . $pulse_rate . "bpm, Lat: " . $loc_latitude . ", Long: " . $loc_longitude;
            $adminMessage = $healthParameters . "; view on map via " . $googleMapURL;
            $this->sendToPhone($adminPhone, $adminMessage);

            $data = [
                'status' => 'LIVESTOCK_DATA_STORED_AND_SENT'
            ];

            //Return the response
            return response()->json([$adminMessage]);

        }else{
            //Prepare data to return
            $data = [
                'status' => 'LIVESTOCK_DATA_NOT_STORED',
                'message' => 'The livestock data could not be added to remote database',
                'data' => []
            ];

            //Return the response
            return response()->json($data);
        }
    }

    public function showOne(Request $request, $nfc_uuid){

        $livestockData = LivestockData::where('nfc_uuid', $nfc_uuid)->get();

        if(!$livestockData->isEmpty()){
            //Prepare data to return
            $data = [
                'status' => 'LIVESTOCK_FOUND',
                'message' => 'Livestock with UUID was found',
                'data' => $livestockData
            ];

            //Return the response
            return response()->json($data);

        }else{
            //Prepare data to return
            $data = [
                'status' => 'LIVESTOCK_NOT_FOUND',
                'message' => 'Livestock with UUID was not found',
                'data' => []
            ];

            //Return the response
            return response()->json($data);
        }
        
    }

    public function showAll(){
        $livestockData = LivestockData::all();

        if(!$livestockData->isEmpty()){
            //Prepare data to return
            $data = [
                'status' => 'LIVESTOCKS_FOUND',
                'message' => 'Livestocks were found',
                'data' => $livestockData
            ];

            //Return the response
            return response()->json($data);

        }else{
            //Prepare data to return
            $data = [
                'status' => 'LIVESTOCKS_NOT_FOUND',
                'message' => 'Livestock were not found',
                'data' => []
            ];

            //Return the response
            return response()->json($data);
        }
    }
}

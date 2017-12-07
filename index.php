<?php
		function requestMaker($city,$type,$api_key) {
    			$request = 'http://api.openweathermap.org/data/2.5/' . $type . '?q='. $city . '&appid='. $api_key . '&units=metric';
			return $request;
		}
	$api_key = '79d5a2d4dec4a5e84034fc6357fa111c';
	$city = 'Maribor';
	
	$request = requestMaker($city,'weather',$api_key);
	$json_today= file_get_contents($request,true);
	$contents_today = json_decode($json_today);
	$temp = $contents_today->main->temp;
	$status = $contents_today->weather[0]->description;
	$visibility = $contents_today->visibility;
	$wind_speed = $contents_today->wind->speed;
	echo($temp);
	echo($status);
	echo($visibility);
	echo("wind speed" . $wind_speed);
	$request = requestMaker($city,'forecast/daily',$api_key,true);
	$json_prognosis = file_get_contents($request);
	$contents_prognosis = json_decode($json_prognosis);
	//temperature = float(w['list'][0]['temp']['max'])

	$hour = date('H');
	//BASED ON THE CURRENT HOUR OF THE SERVER WE WILL DECIDE WHICH VALUES TO PRINT OUT
	//EX: IF IT IS CURRENTLY 21.xx WE WILL ONLY PRINT OUT THE NIGHT TEMPERATURE OF TODAY
	//TOMORROW ALWAYS GETS PRINTED FULLY
	
	$temps_to_readToday = array($contents_prognosis->list[0]->temp->morn,$contents_prognosis->list[0]->temp->day,$contents_prognosis->list[0]->temp->eve,$contents_prognosis->list[0]->temp->night);
	if($hour>12){
		unset($temps_to_readToday[0]);

	}
	if($hour>18){
		unset($temps_to_readToday[1]);

	}
	if($hour>21){
		unset($temps_to_readToday[2]);


	}
	//TOMORROW'S TEMPERATURES, LIST STARTS WITH 0 FOR TODAY
	$tempMornTomorrow = $contents_prognosis->list[1]->temp->morn;
	$tempDayTomorrow = $contents_prognosis->list[1]->temp->day;
	$tempEveTomorrow = $contents_prognosis->list[1]->temp->eve;
	$tempNightTomorrow = $contents_prognosis->list[1]->temp->night;
	echo('Today');
	for($x=0;$x<5;$x++){
	echo($temps_to_readToday[$x]. " ");	
	}	
		echo('Tomorrow:');
	$temps_to_readTomorrow = array($tempMornTomorrow,$tempDayTomorrow,$tempEveTomorrow,$tempNightTomorrow);
	for($x=0;$x<5;$x++){
	echo($temps_to_readTomorrow[$x]. " ");	
	}	
	$filename1= 'todayTemps.vml';
	$filename2= 'tomorrowsTemps.vml';
	$addedContent = '<?xml version="1.0?><vxml version="1.0"><prompt><audio src="pozdrav.raw" /></prompt></vxml>'
	file_put_contents($filename1,$addedContent);


?>

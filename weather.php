<?php
	//set variables for URI of weather and area of the icons
	$imagelocation = "http://www.reesenews.org/wp-content/themes/reesenews/weather-icons/";
	$uri = 'http://xoap.weather.com/weather/local/27514?cc=*&dayf=5&link=xoap&prod=xoap&unit=s&par=1202937883&key=e62d2089516e379f';
	
	//parse the xml data from weather.com
	$data = file_get_contents($uri);
	$weather = new SimpleXMLElement($data);
	$location = $weather->loc->dnam;
	
	//check the current conditions
	$current_tmp = $weather->cc->tmp;
	$current_icon = $weather->cc->icon;
	$current_feels = $weather->cc->flik;
	$current_icon_href = $imagelocation . $current_icon . '.png';
	
	echo "<div id='current_conditions'>";
	printf("<span>%s&deg;</span> <img src='%s' />", $current_tmp, $current_icon_href);
	
	echo "</div>";
	
	echo '<div id="weatherDrawer">';
	
		echo '<div id="weatherTop"></div>';
				
			echo "<div id='forecast'>";
			//4 day loop diplaying the day, hi, low, and icon
			//setting i=1 means we are checking for tomorrow. 0 would be today.
			for($i=0; $i <= 4; $i++) {
				$day = $weather->dayf->day[$i]->attributes()->t;
				$dayShort = substr($day,0,3);
						
				//get the hi
				$hi = $weather->dayf->day[$i]->hi;
				
				//get the low
				$low = $weather->dayf->day[$i]->low;
				
				//get the icon
				$icon = $weather->dayf->day[$i]->part[0]->icon;
				
				$iconhref = $imagelocation . $icon . '.png';
				
				printf("<div class='weather'>");
			
				printf("<span class='red'>%s</span><br />", $dayShort);
						
				printf("<span class='black'>%s&deg;</span>", $hi);
				
				echo " <span class='black'>/</span> ";
				
				printf("%s&deg;<br />", $low);
						
				printf('<img src="%s" /><br /><br />', $iconhref);
				
				echo "</div>";
			}
			
			echo "</div>";
		
		echo '<div id="weatherBottom"></div>';
	
	echo '</div>';
	
?>
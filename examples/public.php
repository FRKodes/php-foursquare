<?php 
require_once("../src/FoursquareAPI.class.php");
$client_key = "ACE2TDS2WTM41CWK3L2JVUNAFXF4C33GUGKFMIFB0E2O2ZT1";
$client_secret = "HZRAIRJHQ1GJ1532QXYMGEA0EORAGJR1MY22V3B5ECMNK3D3";
if($client_key=="" or $client_secret==""){
    echo 'Load client key and client secret from <a href="https://developer.foursquare.com/">foursquare</a>';
    exit;
}
$foursquare = new FoursquareAPI($client_key,$client_secret);
$location = array_key_exists("location",$_GET) ? $_GET['location'] : "Guadalajara, MX";?>
<!doctype html>
<html>
<head>
	<title>PHP-Foursquare :: Unauthenticated Request Example</title>
	<style>
		div.venue{ float: left; padding: 10px; background: #efefef; height: 120px; margin: 10px; width: 340px; }
		div.venue .left{ float: left; width: 88px; }
		div.venue .right{ float: left; width: 245px; padding-left: 5px}
		div.venue a{ color:#000; text-decoration: none; } 
	    div.venue .icon{ background: #000; width: 88px; height: 88px; float: left; margin: 0px 10px 0px 0px; }
	</style>
</head>
<body>
	<h1>Basic Request Example test</h1>
	<p>
		Search for venues near...
		<form action="" method="GET">
			<input type="text" name="location" />
			<input type="submit" value="Search!" />
		</form>
	<p>Searching for venues near <?php echo $location; ?></p>
	<hr /><?php 
	list($lat,$lng) = $foursquare->GeoLocate($location);// Generate a latitude/longitude pair using Google Maps API
	$params = array("ll"=>"$lat,$lng");// Prepare parameters
	$params_ = array("ll"=>"".$_GET['lat'].",".$_GET['lng']."");// Prepare parameters custom
	$response = $foursquare->GetPublic("venues/search",$params_);// Perform a request to a public resource
	$venues = json_decode($response);
	var_dump($venues);
	foreach($venues->response->venues as $venue): ?>
		<div class="venue">
			<div class="left"><?php 
				if(isset($venue->categories['0'])){ echo '<image class="icon" src="'.$venue->categories['0']->icon->prefix.'88.png"/>';
				}else echo '<image class="icon" src="https://foursquare.com/img/categories/building/default_88.png"/>';?>
			</div>
			<div class="right"><?php
				echo '<a href="https://foursquare.com/v/'.$venue->id.'" target="_blank"/><b>';
				echo $venue->name;
				echo "</b></a><br/>";
				echo $venue->location->lat.",".$venue->location->lng."<br/>";
				echo $venue->location->id."<br/>";
				
				if(isset($venue->categories['0'])){
					if(property_exists($venue->categories['0'],"name")){ echo ' <i> '.$venue->categories['0']->name.'</i><br/>'; }
				}
				if(property_exists($venue->hereNow,"count")){ echo ''.$venue->hereNow->count ." people currently here <br/> ";}
		        echo '<b><i>History</i></b> :'.$venue->stats->usersCount." visitors , ".$venue->stats->checkinsCount." visits "; ?>
			</div>
		</div>	<?php 
	endforeach; ?>	
</body>
</html>

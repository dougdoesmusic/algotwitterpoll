
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
body {
    margin: auto;
    width: 444px;
    height: 444px;
    font-family: courier;
    background-color: black;

    
}


</style>
    </head>
    <body align=center>
        

<?php 

//This is all you need to configure.
$app_key = 'YOURKEYS';
$app_token = 'YOURTOKEN';

//These are our constants.
$api_base = 'https://api.twitter.com/';
$bearer_token_creds = base64_encode($app_key.':'.$app_token);

//Get a bearer token.
$opts = array('http' =>
   array(
       
    'method' => 'POST',
    'header' => 'Authorization: Basic '.$bearer_token_creds."\r\n".
               'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
    'content' => 'grant_type=client_credentials'
  )
  );

$context = stream_context_create($opts);
$json = file_get_contents($api_base.'oauth2/token',false,$context);

$result = json_decode($json,true);

if (!is_array($result) || !isset($result['token_type']) || !isset($result['access_token'])) {
  die("Something went wrong. This isn't a valid array: ".$json);
}

if ($result['token_type'] !== "bearer") {
  die("Invalid token type. Twitter says we need to make sure this is a bearer.");
}


//Set our bearer token. Now issued, this won't ever* change unless it's invalidated by a call to /oauth2/invalidate_token.
//*probably - it's not documentated that it'll ever change.
$bearer_token = $result['access_token'];

//Try a twitter API request now.
$opts = array(
  'http'=>array(
    'method' => 'GET',
    'header' => 'Authorization: Bearer '.$bearer_token
  )
);

$context = stream_context_create($opts);
$json = file_get_contents($api_base.'2/tweets?ids=1457945949553119233&expansions=attachments.poll_ids&poll.fields=duration_minutes,end_datetime,id,options,voting_status',true,$context);

$tweets = json_decode($json,true);


$votea = ($tweets['includes']['polls'][0]['options'][0]['votes']);
$voteb = ($tweets['includes']['polls'][0]['options'][1]['votes']);

if($votea > $voteb){
    echo '<video width="416" height="234" controls preload="auto">
  <source src="https://ipfs.io/ipfs/QmTBEEcE5nj4S8J9GptYGnvb9iAZfkVKCgjLxjoCj4cWbH" type="video/mp4">
</video>
<br><br>
<p align="center" style="color:white;"><a href="https://www.w3schools.com" style="color:#ffffff" target="_blank">instrumental version</a>
</p>';
    } 
    elseif ($votea < $voteb) {
    echo '<video width="416" height="234" controls preload="auto">
  <source src="https://ipfs.io/ipfs/QmYnqb8rQuwz81rzZ6GGDihsKzRaLrxBcgEZJi1dqMd2HY" type="video/mp4">
</video>
<br><br>
  <p align="center" style="color:white;"><a href="https://www.w3schools.com" style="color:#ffffff" target="_blank">vocal version</a>
</p>';
     }
     
     elseif ($votea = $voteb) {
    echo '<img src="https://ipfs.io/ipfs/QmY7ZJD9oYFzpiioYDDomxozp9HxjtmzwhqGr1fFKuFbsX" width="300" height="300" preload="auto">
<p align="center" style="color:white;"><a href="https://www.w3schools.com" style="color:#ffffff" target="_blank">the vote is tied...</a>
</p>';
     }
?>

  
    </body>
</html>
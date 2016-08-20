$user_agent = "IsitupForSlack/1.0 (https://github.com/mccreath/istiupforslack; mccreath@gmail.com)";

$command = $_POST['command']; //retrieve text of the command from isitup, this creates a variable

$domain = $_POST['text']; //get the text entered into command -> send the domain to isitup

$token = $_POST['token']; //token - additional identifier, sent with slash command. verifies what's calling script, is actually your slash command.
                          //find token on slash command config page

if($token != 'VMDkvuha26Pxpfy8dsgygK4H'){ 
    $msg = "The token for the slash command doesn't match. Check your script.";
    die($msg);
    echo $msg;
}                        // if statement for token - returns msg. to user if token doesn't match & says token needs to be updated

$url_to_check = "https://isitup.org/".$domain.".json";
//take text typed by user, rely on isitup to check. if not valid isitup responds with 3. want JSON response

//now send url to isitup

$ch = curl_init($url_to_check);    //initialise cURL and tell it what url to open

//set some cURL options to handle specific tasks with opening the URL

curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);     //sends the user agent string - created first line

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        // tells cURL we expect information back from URL, we want that info

//now call the url

$ch_response = curl_exec($ch);                 //assign the call to a variable - so have place to store info returned from isitup.org

curl_close($ch);                              //close cURL connection


// response is JSON but easier/efficient to work with PHP array - so convert data into PHP array
// pass string to PHP function with json_decode()
$response_array = json_decode($ch_response, TRUE);

//using an array allows access to response values from isitup -> to be like the variables set before for $_POST
$response_array['domain']
$response_array['status_code']

//take the values from $response_array and put together a msg back to the user
//check if script can reach isitup.org -> so check $ch_response
if($ch_response === FALSE){

  # isitup.org could not be reached 
  $reply = "Ironically, isitup could not be reached.";

}else{

    if ($response_array['status_code'] == 1){

        $reply = ":thumbsup: I am happy to report that *<http://".$response_array["domain"]."|".$response_array["domain"].">* is *up*!";

    }else if ($response_array['status_code'] == 2){

        $reply = ":disappointed: I am sorry to report that *<http://".$response_array["domain"]."|".$response_array["domain"].">* is *down*!";

    }else if($response_array['status_code'] == 3){

        $reply  = ":interrobang: *".$domain."* does not appear to be a valid domain. ";
        $reply .= "Please enter both the domain name AND the suffix (ex: *amazon.com* or *whitehouse.gov*).";

    }

}



//if we reached isitup.org - see what response back
// 1 means site up, 2 means site not up, 3 means person who wrote text forgot the end bit eg. .com
// if statement used to test response back
if ($response_array['status_code'] == 1){

    $reply = "Good news! ".$response_array["domain"]." is up!";

}else if ($response_array['status_code'] == 2){

    $reply = "Oh no! ".$response_array["domain"]." is down!";

}else if($response_array['status_code'] == 3){

    $reply  = "The domain you entered, ".$domain.", does not appear to be a valid domain. ";
    $reply .= "Please enter both the domain name AND suffix (ex: amazon.com or whitehouse.gov).";

}

// *bold statement*
// turn any slackbot msg into link put angle brackets around it <http://google.com>
// format for linking text in an incoming message: domain + | + text
// eg. <http://domain.com/to/link/|text to link>


//linking the text of the domain itself
<http://".$response_array["domain"]."|".$response_array["domain"].">

//last statement! echo the reply string and cURL will take that reply, post it back to Slack user
echo $reply;






















































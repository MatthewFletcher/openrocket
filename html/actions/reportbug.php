<?
$mailaddr = "openrocket-bugs@lists.sourceforge.net";
//$mailaddr = "sampo.niskanen@gmail.com";

$version = $_POST["version"];
$content = $_POST["content"];


// Parse headers
if (!function_exists('getallheaders')) {
    function getallheaders() {
       foreach ($_SERVER as $name => $value) {
           if (substr($name, 0, 5) == 'HTTP_') {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           }
       }
       return $headers;
    }
}

$headers = "\n\n\n";
foreach (getallheaders() as $header => $value) {
	$headers = $headers . "$header: $value\n";
}

// Set HTTP content-type header
header("Content-type: text/plain; charset=utf-8");


// Check for valid submission
if (preg_match("/^[a-zA-Z0-9. -]{1,30}$/", $version) &&
    strlen($content) > 0) {

  if (mail($mailaddr, "Automatic bug report for OpenRocket " . $version,
    	$content . $headers, 
    	"From: Automatic Bug Reports <".$mailaddr.">\r\n".
    	"Content-Type: text/plain; charset=utf-8")) {
    	
    	// Success
    	header("HTTP/1.0 201 Created");
    	echo "201 Created:  Bug report successfully sent.";
//    	echo "\nContent:\n$content";
    	
    } else {
    	
    	// Sending mail failed
    	header("HTTP/1.0 503 Service Unavailable");
    	echo "503 Service Unavailable:  Unable to send bug report.";
    	
    }

} else {
	
	// Bad request
	header("HTTP/1.0 400 Bad Request");
	echo "400 Bad Request:  Illegal request.\n";
	
}

?>
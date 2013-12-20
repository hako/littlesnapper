<?php
/*

Copyright (c) 2013 Wesley Hill <hakobyte@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the “Software”), to
deal in the Software without restriction, including without limitation the
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
sell copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

                                           ``` .oo- 
                            /++++++++++++++++++++++o/
                            s:.....................:s
                            s:.....................:s
                            s:....+osssssoosyos/...:s
                            s:...-///////+yddhhy...:s
                            s:...sssssyhmMNmMmNd:..:s
                            s:...MMMMdNMMMMhMMMMo..:s
                            s:...mMMMMMMMMMMMMMN/..:s
                            s:.../MMMMMdmdNMMMMy...:s
                            s:...-dMMMMMNMMMMMN/...:s
                            s:......----------.....:s
                            s:.....................:s
                            oo+++++++++++++++++++++oo
                                |               |     
                                |     - -  -    |     
                                |_  __ _______  |   
                               |                 |
                               |                 |
                               |      <snap>     |
                               |                 |
                               \ _ _________ _ _ /
                                

                            L I T T L E S N A P P E R


littlesnapper: captures and prints snapchat pictures to a connected BERG Little Printer.

*/


// main program.

require('imageCrop/image.php');
require('sender.php');
require('imageserver.php');
require('vendor/autoload.php');

function main()
{

    $snaptotal = 0; // total number of images collected.
    $count = 0;     // how many snaps littlesnapper collected.
    $url; 	        // image url.

    echo 'littlesnapper v1.3 [c] 2013 hako';
    echo "\n";
    echo "\n";

    $config = parse_ini_file("config.ini.php"); // load config file

    $usr = $config["usr"];
    $pass = $config["pass"];

    if(!$config) {

        echo "failed to parse config file.";
        exit();

    }

    if($config["delete"] == "true")

    {
        (bool) $config["delete"] = true;
        
    } elseif ($config["delete"] == "false") {

        (bool) $config["delete"] = false;
        
    }

    // instantiating objects.
    $s  = new Snapchat($usr, $pass);
    $i  = new SimpleImage();
    $lp = new Sender();

    // check login credentials.
    if ($s->auth_token == false){
        echo "bad username or password";
        exit();
    }

    //cast an array to the recieved snaps.
    $result = (array) $s->getSnaps();


    // check for new snaps.
    if (empty($result) || empty($result[0]->id)) {
        echo "retrieved $count snaps.";
        echo "\n";
        echo "nothing to print.";
        echo "\n";
        $s->logout();
        exit;
    }
        
    // loop through each retrieved snap (if any)
	foreach($result as $snap) { 

        // Fetch a new snap if it exists.
        if ($snap->status == Snapchat::STATUS_DELIVERED) {

	      $blob_data = $s->getMedia($snap->id);
            if ($blob_data) {
                echo "fetching image $snap->id.jpg from $snap->sender\n";
                $snaptotal = ++$count;
                if ($blob_data == Snapchat::MEDIA_IMAGE)
                    $ext = '.jpg';
                else {
                    $ext = 'mp4';
                }
                
                // save the contents of the captured snap as an image
                file_put_contents($snap->id . $ext, $blob_data);
                $i->load($snap->id . $ext);
                $i->resizeToWidth(400);
                $i->save($snap->id . $ext);
                echo "\n \n";
                
                // host the image on the server.
                showimage($snap->id . $ext);
                
                $url = ($snap->id . $ext);
                
                if ($url == '') {
                    
                    exit();
                    
                }
                
            }           
}
            // stop littlesnapper if there is nothing to print.
            if ($count < 1) {
                echo "retrieved $count snaps.";
                echo "\n";
                echo "nothing to print.";
                echo "\n";
                $snap->logout();
                exit;
            }
            
        }
    
    echo "retrieved $snaptotal snap(s). \n";
    
    // Check if $url is empty before printing.
    if(empty($url) || empty($config["server_url"]) || empty($config["api_key"]))
		
		{
            $snap->logout();
			exit();
		}
    
    // Send picture to the little printer to print.
    echo "Sending image to the little printer...";

    $lp->sendtoprinter($url, $config["api_key"], $config["server_url"], $config["delete"], $config["time_to_delete"]);
    $snap->logout();

    if(!$lp->sendtoprinter($url, $config["api_key"], $config["server_url"], $config["delete"], $config["time_to_delete"]))
    {

        echo "failed to send image to the little printer.";
        $snap->logout();
        exit();
    }

}

main();

?>

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


littlesnapper:	captures and prints snapchat pictures to a connected BERG Little Printer.


*/

// main program.

require('snaphax/snaphax.php');
require('imageCrop/image.php');
require('sender.php');
require('imageserver.php');

function main()
{
    
    $snaptotal = 0; // total images collected.
    $count     = 0; // how many snaps littlesnapper collected.
    $url; // image url.
    
    echo 'littlesnapper v1.2 [c] 2013 hako';
    echo "\n";
    echo "\n";
    
    $account = array();
    
    $account['username'] = ""; // username
    $account['password'] = ""; // password
    
    // instantiating objects.
    $s  = new Snaphax($account);
    $i  = new SimpleImage();
    $lp = new Sender();
    
    $result = $s->login();
    
    // check for new images.
    if (empty($result) || empty($result['snaps'])) {
        echo "retrieved $count snaps.";
        echo "\n";
        echo "nothing to print.";
        echo "\n";
        exit;
    }
    
    
    // get images (if any)
    foreach ($result['snaps'] as $snap) {
        
        // Fetch a snap if it exists.
        
        if ($snap['st'] == SnapHax::STATUS_NEW) {
            $blob_data = $s->fetch($snap['id']);
            if ($blob_data) {
                echo "fetching image $snap[id].jpg from $snap[sn]\n";
                $snaptotal = ++$count;
                
                if ($snap['m'] == SnapHax::MEDIA_IMAGE)
                    $ext = '.jpg';
                else {
                    $ext = 'mp4';
                }
                
                
                // Put the contents of the captured image in a file
                file_put_contents($snap['id'] . $ext, $blob_data);
                $i->load($snap['id'] . $ext);
                $i->resizeToWidth(400);
                $i->save($snap['id'] . $ext);
                echo "\n \n";
                
                // show the image on the server.
                showimage($snap['id'] . $ext);
                
                $url = ($snap['id'] . $ext);
                
                if ($url == '') {
                    
                    exit();
                    
                }
                
            }
            
            // stop littlesnapper if there is nothing to print.
            if ($count < 1) {
                echo "retrieved $count snaps.";
                echo "\n";
                echo "nothing to print.";
                echo "\n";
                exit;
            }
            
        }
        
    }
    
    echo "retrieved $snaptotal snap(s). \n";
    
    // Check if $url is empty before printing.
    
    if(empty($url))
		
		{
			exit();
		}
    
    // Send picture to the little printer to print.
    
    echo "Sending image to the little printer...";
    $lp->sendtoprinter($url);
}
main();

?>

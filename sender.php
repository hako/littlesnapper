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

//sends the image url to little printer.

class Sender
{
    
    function sendtoprinter($url)
    {
        
        $delete = true; // override this to false to keep snapchat images.
        
        $lp_key = ""; // YOUR LITTLE PRINTER ID (required)
        $lp_api = 'http://remote.bergcloud.com/playground/direct_print/' . $lp_key . '?';
        
        echo "\n";
        
        // HTTP POST arguments 
        $postargs = array(
            'html' => $url
        );
        
        $cur = curl_init();
        
        curl_setopt($cur, CURLOPT_POST, true);
        curl_setopt($cur, CURLOPT_POSTFIELDS, $postargs);
        curl_setopt($cur, CURLOPT_URL, $lp_api . '&html=<html><body><center><h1>littlesnapper<h1/><img%20class="dither"%20src="' . 'DIRECTORY_TO_LITTLE_SNAPPER' . $postargs['html'] . '"</img></center></body></html>');

        curl_exec($cur);
        
        //returns 'OK' if successful or 'Invalid code' for unsuccessful.
        $info = curl_getinfo($cur);
        
        echo "\n";
        
        echo ($info['http_code']);
        
        echo "\n";
        echo "\n";
        
        if ($delete == true) {
            
            // Delete the image from the server, just like snapchat ;)
            
            echo "Deleting image in 45s\n";
            sleep(45);
            showimage("");
            unlink($url);
            
            exit();
        }
        
    }
    
}

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

//sends the image url to little printer.

class Sender
{

    function ditherorthreshold($var)
    {

        //TODO ADD MORE IMAGE MODES. (using two for now.)

        switch ($var) {
            case '0':
                return "dither";
                break;
            case '1':
                return " "; // threshold.
                break;
            
            default:
                break;
        }


    }
    
    function sendtoprinter($url, $api_key, $server_url, $delete_option, $delete_time)
    {

        if (!is_numeric($delete_time)) {

             echo "\n";
             echo "Warning! config error, time_to_delete is not a number.";
             echo "\n";
             echo "defaulting to 45 seconds";
             $delete_time = 45;

        } elseif (is_int($delete_time)) {
            
           $delete_time = $delete_time;

        } elseif ($delete_time == "null" || is_null($delete_time) || $delete_time == 0) {

            // if none is provided, default.
            $delete_time = 45;
        }
        
        $delete_snaps = $delete_option;
        
        $lp_key = $api_key;
        $lp_api = 'http://remote.bergcloud.com/playground/direct_print/' . $lp_key . '?';
        
        echo "\n";
        
        // HTTP POST arguments 
        $postargs = array(
            'html' => $url
        );
        
        $cur = curl_init();
        
        curl_setopt($cur, CURLOPT_POST, true);
        curl_setopt($cur, CURLOPT_POSTFIELDS, $postargs);
        curl_setopt($cur, CURLOPT_URL, $lp_api . '&html=<html><body><center><h1>littlesnapper<h1/><img%20class="dither"%20src="' . $server_url . $postargs['html'] . '"</img></center></body></html>');

        curl_exec($cur);
        
        //returns 'OK' if successful or 'Invalid code' for unsuccessful.
        $info = curl_getinfo($cur);
        
        echo "\n";
        
        echo ($info['http_code']);
        
        echo "\n";
        echo "\n";
        
        if ($delete_snaps == true) {
            
            // Deletes the snap from the server, just like snapchat ;)

            $timer = (int) $delete_time;
            
            echo "Deleting captured image in " . $timer . "s\n";
            sleep($timer);
            showimage("");
            unlink($url);
            echo "Snap deleted.";
            
            exit();
        }

        else
        {

            echo "Snap saved. Your littleprinter will print the captured snap.";
            exit();

        }
        
    }
    
}

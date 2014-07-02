<?php

/*

Copyright (c) 2014 Wesley Hill <wesley@hakobaito.co.uk>

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

class Sender {

	function sendToPrinter($url, $data) {
		$api_key       = $data['api_key'];
		$server_url    = $data['server_url'];
		$delete_option = $data['delete'];
		$ditherType    = $data['dither'];
		$delete_time   = $data['time_to_delete'];

		//configuration checks.
		if (!is_numeric($delete_time)) {

			echo "\n";
			echo "Warning! config error, time_to_delete is not a number.";
			echo "\n";
			echo "defaulting to 45 seconds";
			$delete_time = 45;

		} elseif (is_int($delete_time)) {

			$delete_time = $delete_time;

		} elseif ($delete_time == "null" || is_null($delete_time) || $delete_time == 0) {

			// if none is provided, default to 45.
			$delete_time = 45;
		}
		// dither check.
		switch ($ditherType) {
			case true:
				$ditherType = "dither";
				break;
			case false:
				$ditherType = "threshold";
				break;
			default:
				break;
		}

		$delete_snaps = $delete_option;

		$lp_key = $api_key;
		$lp_api = 'http://remote.bergcloud.com/playground/direct_print/'.$lp_key.'?';

		echo "\n";

		// HTTP POST arguments.
		$postargs = array(
			'html' => $url,
		);

		$cur = curl_init();

		curl_setopt($cur, CURLOPT_POST, true);
		curl_setopt($cur, CURLOPT_POSTFIELDS, $postargs);
		curl_setopt($cur, CURLOPT_URL, $lp_api.'&html=<html><body><center><img%20src="'.$server_url.'img/logo.PNG"</img><br><br><img%20class="'.$ditherType.'"%20src="'.$server_url.$postargs['html'].'"</img></center></body></html>');

		curl_exec($cur);
		echo "\n";
		curl_close($cur);

		if ($delete_snaps == true) {

			// Deletes the snap from the server, just like snapchat ;)
			$timer = (int) $delete_time;

			echo "\033[1mDeleting captured snap in ".$timer."s\033[0m\n";
			sleep($timer);
			showimage("");
			unlink($url);
			echo "snap deleted. 👻 \n";
			exit();
		} else {

			echo "\033[1msnap saved. Your littleprinter will print the captured snap.\033[0m";
			exit();
		}

	}

}

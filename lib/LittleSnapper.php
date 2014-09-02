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

require ('ImageCrop/ImageCrop.php');
require ('Sender.php');
require ('ImageServer.php');
require ('vendor/autoload.php');

class LittleSnapper extends Snapchat {
	private static $snaptotal = 0;// total number of images collected.
	private static $count     = 0;// how many snaps littlesnapper collected.

	private static $url;
	private $s;
	private $i;

	public function __construct($username=NULL, $password=NULL) {

		$config = LittleSnapper::loadConfig();
		
		// check if constructor parameters are still NULL,
		// if so the credentials are loaded from config file
		// instead of the constructor.

		if ($username == NULL && $password == NULL) {

			$username = $config["usr"];
			$password = $config["pass"];
		}

		$s    = new Snapchat($username, $password);
		$i    = new SimpleImage();
		$sndr = new Sender();

		if ($this->checkLogin($s) == true) {
			$this->username  = $username;
			$this->logged_in = $this->checkLogin($s);
			$this->user      = $s;
			$this->i         = $i;
			$this->config    = $config;
			$this->sndr      = $sndr;
			return 0;
		} else {
			echo "\033[31mbad username or password\033[0m";
			echo "\n";
			exit();
		}
	}

	// check login credentials.
	private function checkLogin($s) {
		return $s->auth_token?true:false;
	}
	// get snap count.
	private static function getCount() {
		return LittleSnapper::$count;
	}
	// get total snaps
	private static function getTotal() {
		return LittleSnapper::$snaptotal;
	}

	// check for new snaps.
	public function checkForNewSnaps() {
		$snaps = NULL;

		//cast an array to the recieved snaps.
		$result = (array) $this->user->getSnaps();

		foreach ($result as $snap) {

			// check for new snaps.
			if (empty($snap) || $snap->status == self::STATUS_OPENED) {
				$this->user->logout();
				$this->printResults();
				return $snaps = false;
			} else {
				return $snap;
			}

		}

	}
	// fetch a snap or snap(s) from logged in snapchat user.
	public function getSnapFromHost($snap) {
		$blob_data = $this->user->getMedia($snap->id);

		if ($blob_data) {
			echo "\033[1mfetching image $snap->id.jpg from $snap->sender\033[0m\n";
			$snaptotal = ++LittleSnapper::$count;

			if ($blob_data == self::MEDIA_IMAGE) {
				$ext = '.jpg';
			} elseif ($blob_data == self::MEDIA_VIDEO || self::MEDIA_VIDEO_NOAUDIO) {
				echo "littlesnapper cannot print out videos, (yet)";
				$this->user->logout();
				exit();
			}

			// littlesnapper can only do images.
			file_put_contents('img/'.$snap->id.$ext, $blob_data);
			$this->i->load('img/'.$snap->id.$ext);
			$this->i->resizeToWidth(400);
			$this->i->save('img/'.$snap->id.$ext);

			// host the image on the server.
			showimage('img/'.$snap->id.$ext);
			self::$url = ('img/'.$snap->id.$ext);

			// print results.
			$this->printResults();

			if (self::$url == '') {
				$this->user->logout();
				exit();
			}

		}

	}
	// print snaps by sending it to the little printer.
	public function printSnap() {

		// Check if $url et.al are empty before printing.
		if (empty(self::$url) || empty($this->config["server_url"]) || empty($this->config["api_key"])) {
			echo "\n\033[31m\033[1mSome configuration parameters are empty!! (cowardly refusing to print to the little printer!)\033[0m\n";
			unlink(self::$url);
			$this->user->logout();
			exit();
		} else {
			// Send picture to the little printer to print.
			echo "\n\033[1mSending image to the little printer...\033[0m";

			$this->sndr->sendToPrinter(self::$url, $this->config);

			$this->user->logout();

			if (!$this->sndr->sendToPrinter(self::$url, $this->config)) {
				echo "\033[31mfailed to send image to the little printer.\033[0m";
				$s->logout();
				exit();
			}

		}

	}

	// configuration file
	private function loadConfig() {

		$c = parse_ini_file("config/config.ini.php");// load config file

		if (!$c) {

			echo "\033[31mfailed to parse config file.\033[0m";
			exit();
		}

		if ($c["delete"] == "true") {
			(bool) $c["delete"] = true;

		} elseif ($c["delete"] == "false") {

			(bool) $c["delete"] = false;

		}

		if ($c["dither"] == "true") {
			(bool) $c["dither"] = true;

		} elseif ($c["dither"] == "false") {

			(bool) $c["dither"] = false;

		}

		return $c;

	}

	// print the results of captured snaps.
	private function printResults() {
		if (LittleSnapper::getCount() < 1) {
			echo "retrieved ".LittleSnapper::getCount()." snap(s).";
			echo "\n";
			echo "nothing to print.";
			echo "\n";
		} else {
			echo "retrieved ".LittleSnapper::getCount()." snap(s).";

		}

	}

}

?>

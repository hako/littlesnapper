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

//	show and an image to print, then save it.

function showimage($image) {

	$file = 'index.php';

	file_get_contents($file);

	//	fill the $contents with HTML including the $image string.

	$contents = '<html><center><img src="img/logo.PNG"/><br><br><img class="dither" src="'.$image.'"/></body></center><html/>';

	//	save the file.

	file_put_contents($file, $contents);

}

?>

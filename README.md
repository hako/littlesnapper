# littlesnapper

![](https://github-camo.global.ssl.fastly.net/4d0742ffd11e2a077adb62839c1bc283a4fa60fb/687474703a2f2f7331332e706f7374696d672e6f72672f647a79666e346666622f494d475f303036322e6a7067)


#### a tool to capture and print snapchat pictures to a connected inkless BERG Little Printer.

BERG Little Printer
<http://bergcloud.com/littleprinter/>

littlesnapper captures unread snapchat images, hosts the image for 45 seconds to allow the little printer to parse and print the image and then deletes it.  

littlesnapper's old name was *'whippersnapper'* 

note: due to the printer being a thermal printer, the images it will print will be black and white.

_yu wer expekting culurs rite?_

_srry._

## requirements

to run littlesnapper you would need:

-   php 5.1+

-   php-curl

-   mcrypt

-   A BERG Little Printer

-   A server or vps

(I've tried heroku and i'm working on it in the future.)

## how to use

first of all, replace the following in the source:

-   'username' = "" - where "" is your snapchat username

-   'password' = "" - where "" is your snapchat password

-   \$lp\_key = "" - where "" is your little printer key.

-   'DIRECTORY\_TO\_LITTLE\_SNAPPER' - your url or domain name where littlesnapper is hosted.

and now using php in the command-line, type:

    php littlesnapper.php
    
here's a few of mine.

![](http://s16.postimg.org/turdte4d1/IMG_0063.jpg)

It is possible to use cron jobs on a server for littlesnapper.

just don't turn it into a cron job disaster like mine was.

<https://twitter.com/hakobyte/status/399846094852861953/photo/1>

## future

config file.

implement dithering algorithms (for shading)

support for printing multiple snaps.

## license

MIT

## credits

littlesnapper was made by [@hakobyte][@hakobyte]

snaphax - a reverse engineered snapchat library to interface with the snapchat api by

[@tlack][@tlack]

&

[@adamcaudill][@adamcaudill]

imagecrop - an image cropping library made by [abraham daniel][@abrahamdaniel]


Team Snapchat

and

BERG Cloud for creating Little Printer!

http://hakob.yt/e/

  [@hakobyte]: https://twitter.com/hakobyte
  [@tlack]: https://twitter.com/tlack
  [@adamcaudill]: https://twitter.com/adamcaudill
  [@abrahamdaniel]:https://github.com/abrahamdaniel/imageCrop

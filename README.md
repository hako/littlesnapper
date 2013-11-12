# littlesnapper

littlesnapper *alias* 'whippersnapper' - captures and prints snapchat pictures to a connected inkless BERG Little Printer.

little printer

<http://bergcloud.com/littleprinter/>

littlesnapper captures unread snapchat images, hosts the image for 45 seconds to allow the little printer to parse and print the image and then deletes it.  

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

littlesnapper was made by [@hakobyte][]

snaphax - a reverse engineered snapchat library to interface with the snapchat api by

[@tlack][@hakobyte]

&

[@adamcaudill][@hakobyte]

imagecrop - an image cropping library made by [abraham daniel][@hakobyte]

and team snapchat!

http://hakob.yt/e/

  [@hakobyte]: https://github.com/abrahamdaniel/imageCrop

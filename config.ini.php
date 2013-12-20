<?php exit; ?> THIS LINE MUST NOT BE REMOVED.


;---------------------------
;littlesnapper example configuration
;---------------------------

;snapchat configuration settings

;usr = your snapchat username.eg: Evan
;pass = your snapchat password. eg: Ghostface
;delete = do you want to delete or keep captured snaps? eg: true

[snapchat]
usr=
pass=
delete="true"
;debug=false

;littleprinter configuration settings

;api_key = your littleprinter api key.

;(to obtain an api key you need a little printer)
;(get one here)http://remote.bergcloud.com/developers/littleprinter/direct_print_codes

;server_url = your servers root directory that littlesnapper is hosted on.

;time_to_delete = the time you want to delete the image in seconds (delete must be true.)
; if you type null or a strange value, littlesnapper defaults to 45 seconds.

[littleprinter]
api_key = ""
server_url = ""
time_to_delete = 

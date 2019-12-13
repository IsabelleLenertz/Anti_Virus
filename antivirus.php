<?php
     function isPE($filename){
        $fh = fopen($filename, "rb");
        $data = fread($fh, 2);
        $u_data = unpack("A2signature", $data);
        if(u_data[0] != 'M' || u_data[1] != 'Z'){
            return false;
        }
        // find PE?
    }
?>
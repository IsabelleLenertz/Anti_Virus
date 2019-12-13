<?php

    class MicrosftPE{
        
        const DOS_HEADER_O = 0;
        const OFF_PE_HEADER_O = 0x3C;
        const MAGIC_DOS = "MZ";
        const MAGIC_PE = "PE";
        const WORD = 2; // in bytes
        const DWORD = 4;
        
        private $filename;
        private $pe_header_offsert;
        private $dos_signature;
        private $pe_signature;
        
        function __construct($filename) {
            $fh = fopen($filename, "rb")
                    or die("File does not exist or you lack permission to open it");
;
            
            // Getting dos signature
            fseek($fh, self::DOS_HEADER_O);
            $data = fread($fh, self::WORD);
            $this->dos_signature = unpack("A2signature", $data);
            
            // Getting pe header offset
            fseek($fh, self::OFF_PE_HEADER_O);
            $data = fread($fh, self::DWORD);
            $this->pe_header_offsert = unpack("V", $data);
            
            // Getting pe signature
            fseek($fh, $this->pe_header_offsert[1]);
            $data = fread($fh, self::WORD);
            $this->pe_signature = unpack("A2signature", $data);

            
        }
        
        
        
        
        function isPE($filename){
            // Checking dos signature
            if($this->dos_signature['signature'] != self::MAGIC_DOS) { return false; }
            
            // Checking pe signature
            if($this->pe_signature['signature'] != self::MAGIC_PE) {return false; }
            return true;
        }
    }
     
?>
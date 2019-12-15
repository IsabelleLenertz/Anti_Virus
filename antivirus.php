<?php

    class MicrosftPE{
        
        const DOS_HEADER_O = 0;
        const OFF_PE_HEADER_O = 0x3C;
        const RELATVE_SIZE_CODE_O = 0x1C;
        const MAGIC_DOS = "MZ";
        const MAGIC_PE = "PE";
        const WORD = 2; // in bytes
        const DWORD = 4;
        
        private $filename;
        private $pe_header_offsert;
        private $dos_signature;
        private $pe_signature;
        private $size_of_code;
        
        function __construct($filename) {
            $fh = fopen($filename, "rb")
                    or die("File does not exist or you lack permission to open it");
;
            
            // Getting dos signature
            fseek($fh, self::DOS_HEADER_O);
            $data = fread($fh, self::WORD);
            $arr = unpack("A2signature", $data);
            $this->dos_signature = $arr['signature'];
            
            // Getting pe header offset
            fseek($fh, self::OFF_PE_HEADER_O);
            $data = fread($fh, self::DWORD);
            $arr = unpack("V", $data);
            $this->pe_header_offsert = $arr[1];
            
            // Getting pe signature
            fseek($fh, $this->pe_header_offset);
            $data = fread($fh, self::WORD);
            $arr = unpack("A2signature", $data);
            $this->pe_signature = $arr['signature'];

            
        }
        
        
        
        
        function isPE($filename){
            // Checking dos signature
            if($this->dos_signature != self::MAGIC_DOS) { return false; }
            
            // Checking pe signature
            if($this->pe_signature != self::MAGIC_PE) {return false; }
            return true;
        }
        
        /**function sanity_checks(){
            fseek($fh, self::OFF_PE_HEADER_O);
            $data = fread($fh, self::DWORD);
            
            $size_code_offest = unpack("V", $data);
            fseek($fh, self::OFF_PE_HEADER_O + )
            $this->size_of_code = 0;
            
            return true;
        }*/
    }
     
?>
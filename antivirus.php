<?php

    class MicrosftPE{
        
        // Standards offsests
        const DOS_HEADER_O = 0;
        const OFF_PE_HEADER_O = 0x3C;
        // relative to the begining of the PE header
        const RELATVE_SIZE_CODE_O = 0x1C;  
        const RELATIVE_NUM_SEC_O = 0x6; 
        const RELATIVE_SIZE_INIT_DATA_O = 0x20; 
        const RELATIVE_SIZE_IMG_O = 0x50;
        const RELATIVE_SIZE_OPTIONAL_HEADER = 0x14;
        const RELATIVE_START_SECION_TABLE = 0xF8;
        const RELATIVE_DLL_CHAR_O = 0x5E; 
        
        // Standards flags
        const CONTAINS_CODE_FLAG = 0x00000020;
        const SECTION_EXECUTABLE_FLAG = 0x20000000;
        const SECITON_READABLE_FLAG = 0x40000000;
        const SECTION_WRITABLE_FLAG = 0x80000000;
        const STANDARD_SECIONT_NAMES = ['.text', '.bss', '.rdata', '.data',
                                '.rsrc', '.edata', '.idata', '.pdata', '.debug'];
        const MAGIC_DOS = "MZ";
        const MAGIC_PE = "PE";
        const WORD = 2; // in bytes
        const DWORD = 4;
        const SIZE_SECTION_HEADER = 0x28;
        
        // private datafields
        private $filename;
        private $pe_header_offsert;
        private $dos_signature;
        private $pe_signature;
        private $size_of_code;
        private $size_of_initialized_data;
        private $section_headers = array();
        
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
            
            // Get the size of the code section as recorder in the PE header
            fseek($fh, $this->pe_header_offsert);
            $data = fread($fh, self::DWORD);
            $size_code_offest = unpack("V", $data);
            
            fseek($fh, $this->pe_header_offsert + $size_code_offest);
            $data = fread($fh, self::DWORD);
            $arr = unpack("V", $data);
            $this->size_of_code = arr[1];
            
            // Get the size of initilized data (used for statisitcal virus check
            fseek($fh, $this->pe_header_offsert + self::RELATIVE_SIZE_INIT_DATA_O);
            $data = fread($fh, self::DWORD);
            $arr = unpack("V", $data);
            $this->size_of_initialized_data = $arr[1];
            
            // Get number of sections
            fseek($fh, self::RELATIVE_NUM_SEC_O + $this->pe_header_offsert);
            $data = fread($fh, self::WORD);
            $arr = unpack('c', $data);
            $number_sections = arr[1];
            
            // Get size of optional header
            fseek($fh, self::RELATIVE_SIZE_OPTIONAL_HEADER + $this->pe_header_offsert);
            $data = fread($fh, self::DWORD);
            $arr = unpack("V", $data);
            $optional_header_size = arr[1];
                
        
            // fille out the array with interesting info about sections
            $current_offset = self::RELATIVE_START_SECION_TABLE + $this->pe_header_offsert;
            $i = 0;
            while($current_offset < $this->pe_header_offsert + $optional_header_size
                    && $i < $number_sections){
                
            }

            
        }
        
        private function addSectionInfo(){
        }
        
        
        function isPE($filename){
            // Checking dos signature
            if($this->dos_signature != self::MAGIC_DOS) { return false; }
            
            // Checking pe signature
            if($this->pe_signature != self::MAGIC_PE) {return false; }
            return true;
        }
        
        function sanity_checks(){
                       
            // Get the acctual size of the code sections(s).
            
            
            
            
            return true;
        }
    }
     
?>
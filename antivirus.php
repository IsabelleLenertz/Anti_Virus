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
        const RELATIVE_DLL_CHAR_O = 0x5E;  //WORD
        const RELATIVE_MAJOR_IMG_VER_O = 0x44; // WORD
        const RELATIVE_CHECKSU_O = 0x58; //DWORD
        // Relative to the begining of section header
        const RELATIVE_SECTION_NAME_O = 0x0; // QWORD (8 bytes)
        const RELATIVE_SECTION_CHAR_O = 0x24; //DWORD
        
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
        const QWORD = 8;
        const SIZE_SECTION_HEADER = 0x28;
        
        // private datafields
        private $filename;
        private $pe_header_offsert;
        private $dos_signature;
        private $pe_signature;
        private $size_of_code;
        private $size_of_initialized_data;
        private $section_headers = array();
        private $dll_char;
        private $major_img_version;
        private $checksum;
        
        function __construct($filename) {
            $fh = fopen($filename, "rb")
                    or die("File does not exist or you lack permission to open it");
            
            // Getting dos signature
            fseek($fh, self::DOS_HEADER_O);
            $this->dos_signature  = fread($fh, self::WORD);
            
            // Getting pe header offset
            fseek($fh, self::OFF_PE_HEADER_O);
            $data = fread($fh, self::DWORD);
            $arr = unpack("V", $data);
            $this->pe_header_offsert = $arr[1];
            
            // Getting pe signature
            fseek($fh, $this->pe_header_offsert);
            $this->pe_signature = fread($fh, self::WORD);
            
            // Get the size of the code section as recorder in the PE header
            fseek($fh, $this->pe_header_offsert);
            $data = fread($fh, self::DWORD);
            $size_code_offest = unpack("V", $data);
            
            fseek($fh, $this->pe_header_offsert + $size_code_offest); //crashes her
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
            $arr = (int) unpack('c', $data);
            $number_sections = arr[1];
            
            // Get size of optional header
            fseek($fh, self::RELATIVE_SIZE_OPTIONAL_HEADER + $this->pe_header_offsert);
            $data = fread($fh, self::DWORD);
            $arr = unpack("V", $data);
            $optional_header_size = arr[1];
                
        
            // fill out the array with interesting info about sections
            $current_offset = self::RELATIVE_START_SECION_TABLE + $this->pe_header_offsert;
            $i = 0;
            while($current_offset < $this->pe_header_offsert + $optional_header_size
                    && $i < $number_sections){
                $this->section_headers[$i] = getSectionInfo($fh, $current_offset);
                $current_offset += self::SIZE_SECTION_HEADER;
                $i++;
            }

            // get DLL characteristics
            fseek($fh, self::RELATIVE_DLL_CHAR_O + $this->pe_header_offsert);
            $data = fread($fh, self::WORD);
            $arr = (int) unpack('c', $data);
            $this->dll_char = $arr[1];
            
            // get major image version
            fseek($fh, self::RELATIVE_MAJOR_IMG_VER_O + $this->pe_header_offsert);
            $data = fread($fh, self::WORD);
            $arr = unpack('c', $data);
            $this->major_img_version = (int) arr[1];
            
            // get checksum
            fseek($fh, self::RELATIVE_MAJOR_IMG_VER_O + $this->pe_header_offsert);
            $data = fread($fh, self::DWORD);
            $arr = unpack('V', $data);
            $this->checksum = $arr[1];
            
            
        }
        
        private function getSectionInfo($fh, $beg){
            // Get name of the section
            $section = arr();
            fseek($fh, $beg + self::RELATIVE_SECTION_NAME_OE);
            $data = fread($fh, self::QWORD);
            $arr = unpack('A8name', $data);
            $section['name'] = $arr['name'];
            
            // Get characteristics of the section
            fseek($fh, $beg+self::RELATIVE_SECTION_CHAR_O);
            $data = fread($fh, self::DWORD);
            $arr = unpack("V", $data);
            $section['characteristics'] = $arr[1];
            
            return $section;
        }
        
        function isPE($filename){
            // Checking dos signature
            if($this->dos_signature != self::MAGIC_DOS) { return false; }
            
            // Checking pe signature
            if($this->pe_signature != self::MAGIC_PE) {return false; }
            return true;
        }
        
        // Returns false if the files is likely a virus based on header info
        // No signature check at the point
        function sanity_checks(){
                       
            /** PART 1: 
             * Algorithm based on: http://cobweb.cs.uga.edu/~liao/PE_Final_Report.pdf
             * Read the file
             * if SizeOfInitializedData == 0 then
             * return malware
             * else if UnknowSectionName then
             * return malware
             * else if (DLLCharacteristics == 0
             * and MajorImageVersion == 0
             * and CheckSum == 0) then
             * return malware
             * else
             * return benign
             * end if
             */
            
            if($this->size_of_initialized_data == 0){
                return false;
            } else if (unknownSectionName()){
                return false;
            } else if ($this->dll_char == 0 
                    && $this->major_img_version == 0
                    && $this->checksum == 0){
                return false;
            }
            
            // TODO: some more sanity checks such as those based on the article:
            // http://www.darkblue.ch/programming/PE_Format.pdf
            // ie, make sure code sections are read only
            // make sure size of a section matches actual size of the section
            
            return true;
        }
        
        // returns true if a section name is not
        // contained the self::STANDARD_SECTION_NAMES
        private function unknownSectionName(){
            for($i; $i < sizeof($this->section_headers); $i++){
                if(!in_array($this->section_headers[$i]['name'],
                        self::STANDARD_SECIONT_NAMES)){
                    return true;
                }
            }
            return false;
        }
    }
     
?>
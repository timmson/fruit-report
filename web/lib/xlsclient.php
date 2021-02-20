<?
class XLSCreator {

	private $buffer = '';

	public function __construct() {
		$this->xlsBOF();
	}

	public function putCell($Row, $Col, $Value) {
		if (is_numeric($value)) {
			$this->xlsWriteNumber($Row, $Col, $Value);
		} else {
			$this->xlsWriteLabel($Row, $Col, $Value);
		}
	}

	public function flush() {
	    $this->xlsEOF();
	    return $this->buffer;
	}
	
	private function xlsWriteNumber($Row, $Col, $Value) { 
	    $this->buffer .= pack("sssss", 0x203, 14, $Row, $Col, 0x0); 
	    $this->buffer .= pack("d", $Value); 
	    return; 
	} 

	private function xlsWriteLabel($Row, $Col, $Value ) { 
	    $L = strlen($Value); 
	    $this->buffer .= pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L); 
	    $this->buffer .= $Value; 
	return; 

	}

	private function xlsBOF() { 
	    $this->buffer .= pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);  
	    return; 
	} 

	private function xlsEOF() { 
	    $this->buffer .= pack("ss", 0x0A, 0x00); 
	    return; 
	} 
}
?>

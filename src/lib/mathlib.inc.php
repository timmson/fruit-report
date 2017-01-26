<?
class Math {
	
   public static function trend($data) {
        $xsum = 0;
        $ysum = 0;
        $x2sum = 0;
        $xysum = 0;
        $size = count($data);

        for ($x = 0; $x<$size; $x++) {
            $xsum += $x;
            $ysum += $data[$x];
            $x2sum += $x*$x;
            $xysum += $x*$data[$x];
        }
        
        $xsr=$xsum/$size;
        $ysr=$ysum/$size;
        
        $b=($xysum-$size*$xsr*$ysr)/($x2sum-$size*$xsr*$xsr);
        $a=$ysr-$b*$xsr;
        
        $result = array();
        for ($x = 0; $x<$size; $x++) {
            $result[$x] = round($a + $b*$x);
        }
        return $result;
    }
    
        
    public static function percentil($data, $p) {
        $result = array_values($data);
        sort($result);
        return $result[round(count($data)*$p)];
    }
    
    public static function rollingAvg($data, $w) {
		$result = array();
		for ($i = 0; $i<count($data); $i++) {
			$result[$i] = 0;
			for ($j = $i-round($w/2); $j<=$i+round($w/2); $j++) {
				$result[$i] += $data[$j];
			}
			$result[$i] = round($result[$i]/$w);
			
		}
		return $result;
	}
}
?>

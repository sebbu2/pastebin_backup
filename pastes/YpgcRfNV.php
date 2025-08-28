<?php
function array_splice_INV(array $array , int $offset , int $length = NULL , mixed $replacement = NULL ) {
	return array_merge(array_slice($array, 0, $offset), $replacement, array_slice($array, $offset+$length));
}
interface Graph {
	//points / vertices / nodes
	public function addPoint($name);
	public function countPoints();
	public function pointExists($name);
	public function delPoint($name);
	//arcs / edges / links / lines
	public function addLine($src, $dst);
	public function countLines();
	public function lineExists($src, $dst);
	public function delLine($src, $dst);
	//browsing
	public function listLinesFrom($src);
	public function listLinesTo($dst);
	//starts and ends
	public function listSources();
	public function listSinks();
	//iterators ?
};

class AdjencyList implements Graph {
	private $names;
	private $points;
	private $lines;
	public function dump() {
		echo '$names=';var_dump($this->names);
		echo '$points=';var_dump($this->points);
		echo '$lines=';var_dump($this->lines);
	}
	public function __construct() {
		$this->names=array();
		$this->points=array();
		$this->lines=array();
	}
	//names
	public function getName($index) {
		return $this->names[$index];
	}
	//points
	public function addPoint($name) {
		$i=count($this->names);
		$this->names[$i]=$name;
		$this->points[$i]=0;
		return $i;
	}
	public function countPoints() {
		assert(count($this->names)==count($this->points));
		return count($this->points);
	}
	public function pointExists($name) {
		return array_search($name, $this->names, true);
	}
	public function delPoint($name) {
		$i=$this->pointExists($name);
		if($i===false) return false;
		$pos1=array_sum(array_slice($this->points,0,$i));
		$pos2=$this->points[$i];
		$nb=count($this->lines);
		$ar=array_splice($this->lines, $pos1, $pos2);
		assert(count($ar)==$this->points[$i]) or die('delPoint inconsistency');
		$this->points[$i]=0;
		foreach($this->points as $k=>$v) {
			$this->delLine($this->getName($k), $name);//easyness
		}
		unset($this->names[$i]);
		unset($this->points[$i]);
	}
	//lines
	public function addLine($src, $dst) {
		$s=$this->pointExists($src);
		$d=$this->pointExists($dst);
		if($s===false || $d===false) return false;
		$pos1=array_sum(array_slice($this->points,0,$s));
		$pos2=$this->points[$s];
		//throw new Exception('not yet implemented.');
		$begin=array_slice($this->lines, 0, $pos1);
		$ext=array_slice($this->lines, $pos1, $pos2);
		$end=array_slice($this->lines, $pos1+$pos2);
		assert(array_merge($begin,$ext,$end)===$this->lines) or die('addLine inconsistency');
		$ext[]=$d;
		$ext=array_unique($ext);
		sort($ext);
		$this->lines=array_merge($begin,$ext,$end);
		$this->points[$s]=count($ext);
		return true;
	}
	public function countLines() {
		return count($this->lines);
	}
	public function lineExists($src, $dst) {
		$s=$this->pointExists($src);
		$d=$this->pointExists($dst);
		if($s===false || $d===false) return false;
		$pos1=array_sum(array_slice($this->points,$s));
		$pos2=$pos1+$this->points[$s];
		for($i=$pos1;$i<$pos2;$i++) {
			if($this->lines[$i]==$d) return true;
		}
		return false;
	}
	public function delLine($src, $dst) {
		$s=$this->pointExists($src);
		$d=$this->pointExists($dst);
		if($s===false || $d===false) return false;
		$pos1=array_sum(array_slice($this->points,0,$s));
		$pos2=$this->points[$s];
		//throw new Exception('not yet implemented.');
		$begin=array_slice($this->lines, 0, $pos1);
		$ext=array_slice($this->lines, $pos1, $pos2);
		$end=array_slice($this->lines, $pos1+$pos2);
		assert(array_merge($begin,$ext,$end)===$this->lines) or die('delLine inconsistency');
		$pos=array_search($d, $ext);
		if($pos===false) return false;
		$ar=array_splice($ext, $pos, 1);
		assert($ar==array($d)) or die('delLine removal inconsistency');
		$ext=array_unique($ext);
		sort($ext);
		$this->lines=array_merge($begin,$ext,$end);
		$this->points[$s]=count($ext);
		return true;
	}
	//browsing
	public function listLinesFrom($src) {
		$s=$this->pointExists($src);
		if($s===false) return false;
		$pos1=array_sum(array_splice_INV($this->points,$s));
		$pos2=$this->points[$s];
		return array_slice(array_slice($this->points, 0, $pos1),$pos2);
	}
	public function listLinesTo($dst) {
		$d=$this->pointExists($dst);
		$res=array();
		$j=0;
		$k=0;
		for($i=0;$i<count($this->points);$i++) {
			$n=$this->points[$i];
			$k+=$n;
			for(;$j<$k;$j++) {
				if($this->lines[$j]==$d) {
					$res[]=$i;
					break;
				}
			}
		}
		return $res;
	}
	//starts and ends
	public function listSources() {
		$res=array();
		for($i=0;$i<count($this->points);$i++) {
			if(array_search($i, $this->lines, true)===false) $res[]=$i;
		}
		return $res;
	}
	public function listSinks() {
		$res=array();
		for($i=0;$i<count($this->points);$i++) {
			if($this->points[$i]==0) $res[]=$i;
		}
		return $res;
	}
};
$graph=new AdjencyList();
$graph->addPoint('A');
$graph->addPoint('B');
$graph->addPoint('C');
$graph->addLine('A','B');
$graph->addLine('B','C');
$graph->addLine('C','A');
$graph->addLine('C','B');
$graph->addLine('A','C');
$graph->addLine('B','A');
$graph->dump();
?>

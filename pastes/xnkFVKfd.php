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

class AdjencyList2D implements Graph {
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
		unset($this->names[$i]);
		unset($this->points[$i]);
		unset($this->lines[$i]);
		foreach($this->lines as $k=>$v) {
			$s=array_search($i, $v);
			if($s!==false) {
				$ar=array_splice($this->lines[$k], $s, 1);
				assert($ar==array($i));
			}
		}
	}
	//lines
	public function addLine($src, $dst) {
		$s=$this->pointExists($src);
		$d=$this->pointExists($dst);
		if($s===false || $d===false) return false;
		if(!array_key_exists($s,$this->lines)) $this->lines[$s]=array();
		$ext=&$this->lines[$s];
		$ext[]=$d;
		$ext=array_unique($ext);
		sort($ext);
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
		return array_search($d, $this->lines[$s], true);
	}
	public function delLine($src, $dst) {
		$s=$this->pointExists($src);
		$d=$this->pointExists($dst);
		if($s===false||$d===false) return false;
		$p=array_search($d, $this->lines[$s]);
		if($p===false) return false;
		$ar=array_splice($this->lines[$s], $p, 1);
		assert($ar==array($d));
	}
	//browsing
	public function listLinesFrom($src) {
		$s=$this->pointExists($src);
		if($s===false) return false;
		return $this->lines[$s];
	}
	public function listLinesTo($dst) {
		$d=$this->pointExists($dst);
		$res=array();
		foreach($this->lines as $k=>$v) {
			if(in_array($d, $v)) $res[]=$k;
		}
		return $res;
	}
	//starts and ends
	public function listSources() {
		$res=array();
		for($i=0;$i<count($this->points);$i++) {
			foreach($this->lines as $k=>$v) {
				if(in_array($i, $v)) continue(2);
			}
			$res[]=$i;
		}
		return $res;
	}
	public function listSinks() {
		$res=array();
		foreach($this->points as $p) {
			if(!array_key_exists($p, $this->lines) || count($this->lines[$p])==0) $res[]=$p;
		}
		return $res;
	}
};
$graph=new AdjencyList2D();
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

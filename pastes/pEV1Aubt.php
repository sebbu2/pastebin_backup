<!DOCTYPE html>
<html>
<head>
<title>Chess</title>
<meta charset="UTF-8"/>
</head>
<body>

<?php
function is_black($c) {
	return ($c==='B'||$c==='Black');
}
function is_white($c) {
	return ($c==='W'||$c==='White');
}
abstract class Piece {
	public $x;
	public $y;
	public $color;
	public $parent;
	public function __construct(&$parent=NULL) {
		$this->parent=$parent;
	}
	public function can_move($diff_x, $diff_y) { return false; }
	public function can_eat($diff_x, $diff_y) { return $this->can_move($diff_x, $diff_y); }
}
class Pawn extends Piece {
	public function can_move($diff_x, $diff_y) {
		/*if(is_black($c)) {
			if($y===1) return ($diff_y>=1 && $diff_y<=2);
			else return ($diff_y==1);
		}
		elseif(is_white($c)) {//*/
			if($this->y===6) return ($diff_y<=-1 && $diff_y>=-2);
			else return ($diff_y==-1);
		/*}
		return false;//*/
	}
	public function can_eat($diff_x, $diff_y) {
		/*if(is_black($c)) {
			return (abs($diff_x)==1 && $diff_y==1);
		}
		elseif(is_white($c)) {//*/
			return (abs($diff_x)==1 && $diff_y==-1);
		/*}
		return false;//*/
	}
	public function __toString() {
		return 'Pawn';
	}
}
class Rook extends Piece {
	public function can_move($diff_x, $diff_y) {
		return ( ($diff_x==0 && abs($diff_y)>0) || ($diff_y==0 && abs($diff_x)>0) );
		//return ($diff_x^$diff_y>0);
	}
	public function can_eat($diff_x, $diff_y) {
		return (
			$this->can_move($diff_x, $diff_y) &&
			$this->parent->is_free($this->x, $this->y, $this->x+$diff_x, $this->y+$diff_y)
		);
	}
	public function __toString() {
		return 'Rook';
	}
}
//black up (y=0), white down (y=7)
class board {
	public $grid;
	public $width;
	public $height;
	public function __construct() {
		$this->grid=array();
		$this->width=8;
		$this->height=8;
		for($i=0;$i<$this->height;++$i) {
			$this->grid[$i]=array();
			for($j=0;$j<$this->width;++$j) {
				$this->grid[$i][$j]='&nbsp;';
			}
		}
	}
	public function is_empty($x, $y) {
		return ( ! ($grid[$y][$x] instanceof Piece) );
	}
	public function is_free($src_x, $src_y, $dst_x, $dst_y) {
		assert( $src_x==$dst_x || $src_y==$dst_y || (($src_x-$dst_x)==($src_y-$dst_y)) );
		$diff_x=$dst_x-$src_x;
		$diff_y=$dst_y-$src_y;
		$px=$diff_x/abs($diff_x);
		$py=$diff_y/abs($diff_y);
		for($i=1; $i<max(abs($diff_x),abs($diff_y)); ++$i) {
			if($this->get_piece($src_x+$px*$i, $src_y+$py*$i) instanceof Piece) return false;
		}
		return true;
	}
	public function pos_exists($x, $y) {
		return ($x>=0 && $x<$this->width && $y>=0 && $y<=$this->height);
	}
	public function get_piece($x, $y) {
		return $this->grid[$y][$x];
	}
};
$board=new board();
//var_dump($board->grid);
$board->grid[7][0]='Rook';
$board->grid[7][1]='Knight';
$board->grid[7][2]='Bishop';
$board->grid[7][3]='Queen';
$board->grid[7][4]='King';
$board->grid[7][5]='Bishop';
$board->grid[7][6]='Knight';
for($i=0;$i<8;++$i) {
	$board->grid[6][$i]=new Pawn($board);
}
$board->grid[7][7]='Rook';
//var_dump($board->grid);
var_dump($board->grid[6][0]->parent->grid[7][7]);
$test=new Rook($board);
$test->x=0;
$test->y=7;
$board->grid[7][0]=clone $test;
$test->x=7;
$board->grid[7][7]=clone $test;
var_dump($board->grid[7][0]->x);
?><table border="1"><?php
for($y=0;$y<8;++$y) {
?>
	<tr>
<?php
	for($x=0;$x<8;++$x) {
?>		<td><?php echo $board->get_piece($x, $y); ?></td>
<?php
	}
?>	</tr><?php
}
?>
</table>

</body>
</html>

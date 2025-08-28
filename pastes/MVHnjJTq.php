<?php
class table_parser
{
	public $data;
	public $total_rows=0;
	public $total_cols=0;
	public $rows;
	public $cols;
	public $cels;

	public function __construct( $data ) {
		$this->data=$data;
		$this->count();
	}

	public function count()
	{
		$this->total_rows=substr_count($this->data,'</tr>');
		$pos2=0;
		$matches=array();
		
		for($i=0;$i<$this->total_rows;$i++) {
			$pos1=strpos($this->data,'<tr',$pos2);
			$pos2=strpos($this->data,'</tr>',$pos1);
			$matches[]=substr($this->data,$pos1,$pos2+5-$pos1);
		}
		/* get number of columns */
		foreach($matches as $value)
		{
			$cols=substr_count($value,'</td>');
			if($cols>$this->total_cols)
			{
				$this->total_cols=$cols;
			}
		}
		/* get number of lines */
		$this->total_rows=count($matches);
		
		/* initialize array */
		$this->cels = array();
		for($i=0; $i<$this->total_rows; $i++)
		{
			$this->cels[] = array();
			for($j=0; $j<$this->total_cols; $j++)
			{
				$this->cels[$i][] = false;
			}
		}
		return array($this->total_rows,$this->total_cols);
	}

	public function parse() {
		// sauter la premiere ligne
		//$pos2=strpos($this->data,'</tr>');
		$pos2=0;
		$rows=array();
		for($i=0;$i<$this->total_rows;$i++)
		{
			$this->cols=0;
			$this->rows=$i;
			/* get line content */
			$pos1=strpos($this->data,'<tr',$pos2);
			$pos2=strpos($this->data,'</tr>',$pos1);
			$rows[$i]=substr($this->data,$pos1,$pos2+5-$pos1);
			$data=$rows[$i];
			$cols=substr_count($data,'</td>');
			$pos_2=0;$matches=array();
			for($j=0;$j<$cols;$j++)
			{
				/* isolate cell content */
				$pos_1=strpos($data,'<td',$pos_2);
				$pos_2=strpos($data,'</td>',$pos_1);
				$matches[$j]=substr($data,$pos_1,$pos_2+5-$pos_1);
				$pos__2=0;$pos__1=0;
				/* get cell size */
				if(substr_count($matches[$j],'colspan=')==1)
				{
					$pos__1=strpos($matches[$j],'colspan="',$pos__2)+9;
					$pos__2=strpos($matches[$j],'"',$pos__1);
					$colspan=substr($matches[$j],$pos__1,$pos__2-$pos__1);
				}
				else
				{
					$colspan="1";
				}
				$pos__2=0;
				if(substr_count($matches[$j],'rowspan=')==1)
				{
					$pos__1=strpos($matches[$j],'rowspan="',$pos__2);
					$pos__1+=9;
					$pos__2=strpos($matches[$j],'"',$pos__1);
					$rowspan=substr($matches[$j],$pos__1,$pos__2-$pos__1);
				}
				else
				{
					$rowspan="1";
				}
				/* get cell content */
				$pos__2=0;
				//$pos__1=strpos($matches[$j],'<a ',$pos__2);
				$pos__1=strpos($matches[$j],'>',$pos__1);
				//$pos__2=strpos($matches[$j],'</a>',$pos__1);
				$pos__2=strpos($matches[$j],'</td>',$pos__1);
				$cour=trim(substr($matches[$j],$pos__1+1,$pos__2-$pos__1-1));
				if($cour===false) $cour='';
				/* fill array */
				$num_1=0;$num_2=0;$num_1b=0;$num_2b=0;
				$num2add=0;
				for($num_1=0;$num_1<$rowspan;$num_1++)
				{
					$num_1b=$i+$num_1;
					for($num_2=0;$num_2<$colspan;$num_2++)
					{
						$num_2b=$this->cols+$num_2+$num2add;
						while($this->cels[$num_1b][$num_2b]!==false)
						{
								$num2add++;
								$num_2b=$num_2+$this->cols+$num2add;
						}
						//if($num_2b>23) die('érreur1');
						if($this->cels[$num_1b][$num_2b]!='')
						{
							var_dump($num_1b,$num_2b);
							die('érreur2');
						}
						$this->cels[$num_1b][$num_2b]=$cour;
					}
				}
				$this->cols+=$colspan-1;
				$cour='';
				$this->cols++;
			}
		}
		return $this->cels;
	}

	public function get_data() {
		return $this->data;
	}
}
?>

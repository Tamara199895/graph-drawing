<?php 
class Graph{
	public	$nodes = [];
	public $edges = [];
	public function add_node($node){
		array_push($this->nodes,$node);
	}
	public function add_nodes($nodes){
		for($i = 0;$i < count($nodes);$i++){
			array_push($this->nodes,$nodes[$i]);
		}
	}
	public function add_edge(array $edge){
		array_push($this->edges,$edge);
	}
	public function add_edges($edges){
		for($i = 0;$i < count($edges); $i++){
			array_push($this->edges,$edges[$i]);
		}
	}
	
	public function nodes(){
		return $this->nodes;
	}
	public function number_of_nodes(){
		return count($this->nodes);
	}
	public function neighbors($n){
		$arr = [];
		for($i = 0;$i < count($this->edges);$i ++){
			if (in_array($n,$this->edges[$i])) {
				for($j = 0;$j < 2;$j++){
					if ($this->edges[$i][$j] != $n) {
						array_push($arr,$this->edges[$i][$j]);
					}
				}
			}
		}
		return $arr;
	}

	
	public function remove_node($node){
		if (!in_array($this->nodes, $node)) {
			print_r('Նշված գագաթը չկա գրաֆում');
		}
		for($i = 0;$i<count($this->nodes);$i++){
			if ($this->nodes[$i]==$node) {
				array_splice($this->nodes,$i, 1);
			}
		}
		for($i = 0;$i < count($this->edges);$i++){
			if (in_array($node, $this->edges[$i])) {
				//հեռացնում է այն եզրերը որոնք կազմված էին տրված հանգույցով
				array_splice($this->edges,$i,1);
				$i--;
			}
		}
	}
	public function remove_edge($u,$v){
		for($i = 0;$i < count($this->edges);$i++){
			if (in_array($u, $this->edges[$i]) && in_array($v, $this->edges[$i])) {
				array_splice($this->edges,$i,1);
			}
		}
	}
	
	public function draw(){ 
        $node=$this->nodes;
        $edge=$this->edges;
        $arr=[];
        for($i=0;$i<count($node);$i++){//գեներացնում է կոորդինատներ գագաթների համար;
        $angle = deg2rad(mt_rand(0, 360));
        $pointRadius = mt_rand(0, 360);
        $point = array(
           'x' => sin($angle) * $pointRadius,
           'y' => cos($angle) * $pointRadius,
           'name' => $node[$i]
        );
        array_push($arr,$point);
        }
        ?>
        <html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
</head>
<body>
  <canvas id="myCanvas" width="650px" height="550px"></canvas>
  <script>
    var canvas = document.body.querySelector('#myCanvas');
    var ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
</script>
  <?php  
    for($i=0;$i<count($edge);$i++){
            for($j=0;$j<count($arr);$j++){//գտնում է եզրերի կոորդինատները;
                if ($edge[$i][0]==$arr[$j]['name']) {
                    $x1=$arr[$j]['x'];
                    $y1=$arr[$j]['y'];
                    
                }
                else if($edge[$i][1]==$arr[$j]['name']){
                    $x2=$arr[$j]['x'];
                    $y2=$arr[$j]['y'];
                }
            }
//             print "<pre>";
// print_r($arr);
  ?>                
<script>
function drawLine (X1, Y1, X2, Y2){//գծում է եզրերը
  ctx.save();
  ctx.beginPath();
  ctx.translate(canvas.width/2, canvas.height/2)
  ctx.moveTo(X1, Y1);
  ctx.lineTo(X2, Y2);
  ctx.stroke();
  ctx.restore();
  
  
}
function drowNodes(X1,Y1){//գծում է գագաթները
    ctx.save()
    ctx.beginPath();
    ctx.translate(canvas.width/2, canvas.height/2)
    ctx.arc(X1, Y1, 7, 0, 2 * Math.PI);
    ctx.stroke();
  ctx.restore();
  ctx.fillStyle = "#B34EE9";
  ctx.fill();
   
}
drowNodes(<?=$x1 ?>,<?=$y1 ?>);
drowNodes(<?=$x2 ?>,<?=$y2 ?>)
drawLine(<?=$x1 ?> ,<?= $y1?> ,<?= $x2?> ,<?=$y2 ?>)
  </script>
  <?php      
        }
  ?>
</body>
</html>
<?php
}
}
?>


<?php
$G = new Graph();
$G->add_nodes([1,2,3,4,5,6]);
$G->add_edges([[2,3],[1,3],[3,4],[3,6],[3,5],[4,5]]);
$G->draw();
?>

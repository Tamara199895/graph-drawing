
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
				//հեռացնում է այն կողերը որոնք կազմված էին տրված հանգույցով
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
	public function create_empty_copy(){
		$g = new Graph();
		$g->nodes = $this->nodes;
		$g->edges = [];
		return $g;
	}
	public function is_empty(){
		if (count($this->edges) == 0) {
			return true;
		}
		return false;
	}
	public function subgraph($nbunch=[]){
		$graph = new Graph();
		if ($nbunch) {
			$graph->nodes = $nbunch;
		}
		else{
			$graph->nodes = $this->nodes;
		}
		return $graph; 
	}
	public function edge_subgraph($edges){
		$graph = new Graph();
		$graph->add_edges($edges);
		return $graph; 
	}
	public function common_neighbors($u,$v){
		$arr = [];
		$u_neighbors = $this->neighbors($u);
		$v_neighbors = $this->neighbors($v);
		for($i = 0;$i < count($u_neighbors);$i++){
			if (in_array($u_neighbors[$i], $v_neighbors)) {
				array_push($arr,$u_neighbors[$i]);
			}
		}
		return $arr;
	}
	public function edges(){
		return $this->edges;
	}
	public function number_of_edges(){
		return count($this->edges);
	}

		
	public function get_layout_random(){
	    $arr=[];
		for($i=0;$i<count($this->nodes);$i++){//գեներացնում է կոորդինատներ գագաթների համար;
    		$angle = deg2rad(mt_rand(0, 360));
    		$pointRadius = mt_rand(0, 360);
    		$point = array(
    		   'x' => sin($angle) * $pointRadius,
    		   'y' => cos($angle) * $pointRadius,
    		   'name' => $this->nodes[$i]
    		);
    		$arr[$i] = $point;
		}
		return $arr;
	}

	 public function get_layout_spiral($dim=2,$resolution=0.35,$equistant=true){
        if (count($this->nodes())==0) {
            return [];
        }
        if (count($this->nodes())==1) {
            return $this->nodes[0];
        }
        $pos=[];
        if ($equistant) {
            $chord=80;
            $step=0.5;
            $theta=$resolution;
            for($i=0;$i < count($this->nodes);$i++){
                $r=$step * $theta;
                $theta +=$chord/$r;
                $point = array(
                    'x' => sin($theta)*$r,
                    'y' => cos($theta)*$r,
                    'name' => $this->nodes[$i]
                );
                $pos[$i]=$point;
            }
        }
        else{
            $step=80;
            $angle=0.0;
            $dist=0.0;
            $radius=count($this->nodes);
            for($i=0;$i<count($this->nodes);$i++){
                $point=array(
                    'x' => sin($angle)*$dist,
                    'y' => cos($angle)*$dist,
                    'name' =>$this->nodes[$i]
                );
                $pos[$i]=$point;
                $dist+= $step;
                $angle+=$resolution;
            }
        }
        return $pos;
    }
	public function draw(){ 
		$node=$this->nodes;
		$edge=$this->edges;
        $arr = $this->get_layout_random();
		?>
	
  <script>
    var canvas = document.body.querySelector('#myCanvas');
    var ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    
    function drawEdges (X1, Y1, X2, Y2){//գծում է կողերը
  ctx.save();
  ctx.beginPath();
  ctx.translate(canvas.width/2, canvas.height/2)
  ctx.moveTo(X1, Y1);
  ctx.lineTo(X2, Y2);
  ctx.stroke();
  ctx.restore();
  ctx.beginPath();
}

function drawNodes(X1,Y1,name){//գծում է գագաթները
	ctx.save();
	ctx.beginPath();
	ctx.translate(canvas.width/2, canvas.height/2)
	ctx.arc(X1, Y1, 8, 0, 2 * Math.PI);
	drawText(X1,Y1,name);
  ctx.stroke();
  ctx.restore();
  ctx.fillStyle = "black";
  ctx.fill();
}
function drawText(X1,Y1,name){//տպում է գագաթի անունը
ctx.font = "25px Arial";
ctx.fillStyle = "black";
ctx.fillText(name, X1+8, Y1-5);
}
function canvas_arrow(context, fromx, fromy, tox, toy){
  var headlen = 20;
  var dx = tox - fromx;
  var dy = toy - fromy;
  var angle = Math.atan2(dy, dx);
  context.moveTo(fromx, fromy);
  context.lineTo(tox, toy);
  context.lineTo(tox - headlen * Math.cos(angle - Math.PI / 6), toy - headlen * Math.sin(angle - Math.PI / 6));
  context.moveTo(tox, toy);
  context.lineTo(tox - headlen * Math.cos(angle + Math.PI / 6), toy - headlen * Math.sin(angle + Math.PI / 6));
}
  <?php  
  for($i=0; $i<count($node); $i++){
      $x1 = $arr[$i]['x'];
      $y1 = $arr[$i]['y'];
      $name = $arr[$i]['name'];
    ?>555
drawNodes(<?=$x1 ?>,<?=$y1 ?>,<?=$name ?>);
    <?php
  }
    for($i=0;$i<count($edge);$i++){
			for($j=0;$j<count($arr);$j++){//գտնում է կողերի կոորդինատները;
				
				if ($edge[$i][0]==$arr[$j]['name']) {
					$x1=$arr[$j]['x'];
					$y1=$arr[$j]['y'];
				// 	$name=$node[$i];
				}
				else if($edge[$i][1]==$arr[$j]['name']){
					$x2=$arr[$j]['x'];
					$y2=$arr[$j]['y'];
				// 	$name=$node[$i];
				}
			}
  ?>				
drawEdges(<?=$x1 ?> ,<?= $y1?> ,<?= $x2?> ,<?=$y2?>);
  <?php  	
		}
  ?>
</script>

<?php } } ?>


<?php
$G = new Graph();
$G->add_nodes([1,2,3,4]);
$G->add_edges([[1,2],[2,3],[1,3],[3,4]]);
$G->draw();
?>

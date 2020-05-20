<?php 
class DiGraph{
    public    $nodes = [];
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
    public function info($n=0){
        if ($n==0) {
            print "nodes ".count($this->nodes)." edges ".count($this->edges); 
        }
        if($n){
            print "node ".$n." degree ".$this->degree();
        }
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
    public function clear(){
        $this->nodes = [];
        $this->edges = [];
    }
    public function has_node($node){
        if (in_array($node, $this->nodes)) {
            return true;
        }
        return false;
    }
    public function has_edge($u,$v){
        for($i=0;$i<count($this->edges);$i++){
            if (in_array($u, $this->edges[$i]) && in_array($v, $this->edges[$i])) {
                return true;
            }
        }
        return false;
    }

    public function is_selfloop(){
       $arr=[];
    for($i = 0;$i < count($this->edges);$i++){
      if ($this->edges[$i][0]==$this->edges[$i][1]) {
        array_push($arr,$this->edges[$i][1]);
      }
    }
    if (isEmpty($arr)) {
       return false;
     } 
     return true;
  }

    public function shortestPath($source, $target) {
   
    $d = array();
    $pi = array();
    $Q = new SplPriorityQueue();
    foreach ($this->graph as $v => $adj) {
      $d[$v] = INF; 
      $pi[$v] = null;
      foreach ($adj as $w => $cost) {
        $Q->insert($w, $cost);
      }
    }
    $d[$source] = 0;
    while (!$Q->isEmpty()) {
      $u = $Q->extract();
      if (!empty($this->graph[$u])) {
        foreach ($this->graph[$u] as $v => $cost) {
 
          $alt = $d[$u] + $cost;
          if ($alt < $d[$v]) {
            $d[$v] = $alt; 
            $pi[$v] = $u;  
                           
          }
        }
      }
    }
    $S = new SplStack();
    $u = $target;
    $dist = 0;
    while (isset($pi[$u]) && $pi[$u]) {
      $S->push($u);
      $dist += $this->graph[$u][$pi[$u]]; 
      $u = $pi[$u];
    }
    if ($S->isEmpty()) {
      echo "No route from $source to $target";
    }
    else {
      $S->push($source);
      echo "$dist:";
      $sep = '';
      foreach ($S as $v) {
        echo $sep, $v;
        $sep = '->';
      }
      echo "<br>";
    }
  }
    public function draw() {
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
            for($j=0;$j<count($arr);$j++){//գտնում է կողերի կոորդինատները;
                
                if ($edge[$i][0]==$arr[$j]['name']) {
                    $x1=$arr[$j]['x'];
                    $y1=$arr[$j]['y'];
                    $name=$node[$i];
                }
                else if($edge[$i][1]==$arr[$j]['name']){
                    $x2=$arr[$j]['x'];
                    $y2=$arr[$j]['y'];
                    $name=$node[$i];
                }
            }
  ?>                
<script>
function drawEdges(X1,Y1,X2,Y2){
ctx.save();
ctx.beginPath();
ctx.translate(canvas.width/2, canvas.height/2);
canvas_arrow(ctx, X1, Y1, X2, Y2);
canvas_arrow(ctx, X1, Y1, X2, Y2);
canvas_arrow(ctx, X1, Y1, X2, Y2);
canvas_arrow(ctx, X1, Y1, X2, Y2);
ctx.stroke();
ctx.restore();
}
function drawNodes(X1,Y1,name){  //գծում է գագաթները
    ctx.save();
    ctx.beginPath();
    ctx.translate(canvas.width/2, canvas.height/2)
    ctx.arc(X1, Y1, 7, 0, 2 * Math.PI);
    // drawText(X1,Y1,name);
  ctx.stroke();
  ctx.restore();
  ctx.fillStyle = "#B34EE9";
  ctx.fill();
}
function drawText(X1,Y1,name){//տպում է գագաթի անունը
ctx.font = "30px Arial";
ctx.fillText(name, X1, Y1);
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
drawNodes(<?=$x1 ?>,<?=$y1 ?>,<?=$name ?>);
drawNodes(<?=$x2 ?>,<?=$y2 ?>, <?=$name ?>);
drawEdges(<?=$x1 ?> ,<?= $y1?> ,<?= $x2?> ,<?=$y2 ?>)
// 
  </script>
  <?php      
        }
  ?>
</body>
</html>
<?php  }


}
?>

<?php
$DG = new DiGraph();
$DG->add_nodes([1,2,3,4]);
$DG->add_edges([[2,3],[1,3],[3,4]]);
$DG->draw();
?>
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

  public function nodes_with_selfloops(){
    $arr=[];
    for($i = 0;$i < count($this->edges);$i++){
      if ($this->edges[$i][0]==$this->edges[$i][1]) {
        array_push($arr,$this->edges[$i][1]);
      }
    }
    return $arr;
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
    

function draw_Edge(X1,Y1,X2,Y2){//գծում է կողերը ուղղորդված գրաֆի համար
ctx.save();
ctx.beginPath();
ctx.translate(canvas.width/2, canvas.height/2)
canvas_arrow(ctx, X1, Y1, X2, Y2);
ctx.stroke();
ctx.restore();
}
function drawNodes(X1,Y1,name){//գծում է գագաթները
  ctx.save();
  ctx.beginPath();
  ctx.translate(canvas.width/2, canvas.height/2)
  ctx.arc(X1, Y1, 17, 0, 2 * Math.PI);
  drawText(X1,Y1,name);
  ctx.stroke();
  ctx.restore();
  ctx.fillStyle = "#007CF8";
  ctx.fill();
}
function drawText(X1,Y1,name){//տպում է գագաթի անունը
ctx.font = "30px Tahoma";
ctx.strokeText(name, X1+25, Y1-5);
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
    ?>
drawNodes(<?=$x1 ?>,<?=$y1 ?>,<?=$name ?>);
    <?php
  }
    for($i=0;$i<count($edge);$i++){
      for($j=0;$j<count($arr);$j++){//գտնում է կողերի կոորդինատները;
        
        if ($edge[$i][0]==$arr[$j]['name']) {
          $x1=$arr[$j]['x'];
          $y1=$arr[$j]['y'];
        //  $name=$node[$i];
        }
        else if($edge[$i][1]==$arr[$j]['name']){
          $x2=$arr[$j]['x'];
          $y2=$arr[$j]['y'];
        //  $name=$node[$i];
        }
      }
  ?>        
draw_Edge(<?=$x1 ?> ,<?= $y1?> ,<?= $x2?> ,<?=$y2?>);
  <?php   
    }
  ?>
</script>

<?php } } ?>


<?php
$DG = new DiGraph();
$DG->add_nodes([1,2,3,4,5,6]);
$DG->add_edges([[1,5],[2,4],[6,3],[4,2],[2,5],[2,4],[5,6]]);
$DG->draw();
?>

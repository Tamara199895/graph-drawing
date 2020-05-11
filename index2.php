
<!-- Կարճագույն ճանապարհը -->
<?php
$graph = array(
  'A' => array('B' => 2, 'D' => 1, 'F' => 6),
  'B' => array('A' => 3, 'D' => 5, 'E' => 3),
  'C' => array('E' => 2, 'F' => 4),
  'D' => array('A' => 3, 'B' => 1, 'E' => 1, 'F' => 2),
  'E' => array('B' => 3, 'C' => 5, 'D' => 1, 'F' => 5),
  'F' => array('A' => 6, 'C' => 3, 'D' => 2, 'E' => 3),
);
class Graph
{
  protected $graph;

  public function __construct($graph) {
    $this->graph = $graph;
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
}

$g = new Graph($graph);

$g->shortestPath('D', 'C');  // 3:D->E->C
$g->shortestPath('C', 'A');  // 6:C->E->D->A
$g->shortestPath('B', 'F');  // 3:B->D->F
$g->shortestPath('F', 'A');  // 5:F->D->A 
$g->shortestPath('A', 'G');  // No route from A to G

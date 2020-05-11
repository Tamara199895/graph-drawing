<!-- Գրաֆների տեսության հայտնի ալգորիթմերից է 2 հանգույցների միջև գագաթների նվազագույն քանակը հաշվելը։ -->


<!-- Հիմական ալգորիթմն այսպիսինն է։
1Ստեղծել հերթ
2Նշել հերթի արմատային գագաթը եւ համաել այն այցելված
 Քանի դեռ հերթը դատարկ չէ՝ 
   3 ա։ հեռացնել ընթացիկ գագաթը
   3 բ։ եթե ներկայիս գագաթը մեկն է՝ որը մենք փնտրում ենք՝ ապա կանգ առնել
   3 ս։ այլապես ծածկել յուրաքանչյուր չտեսնված հարակից գագաթը և նշել՝ այցելված  -->
<?php
$graph = array(
  'A' => array('B', 'F'),
  'B' => array('A', 'D', 'E'),
  'C' => array('F'),
  'D' => array('B', 'E'),
  'E' => array('B', 'D', 'F'),
  'F' => array('A', 'E', 'C'),
);

class Graph
{
  protected $graph;
  protected $visited = array();

  public function __construct($graph) {
    $this->graph = $graph;
  }

  // 2 հանգույցների միջև գագաթների նվազագույն քանակը

  // (vertices)
  public function breadthFirstSearch($origin, $destination) {
    // mark all nodes as unvisited
    foreach ($this->graph as $vertex => $adj) {
      $this->visited[$vertex] = false;
    }

// ստեղծել դատարկ հերթ
    $q = new SplQueue();

    // enqueue the origin vertex and mark as visited
    $q->enqueue($origin);
    $this->visited[$origin] = true;

    // սա օգտագործվում է յուրաքանչյուր հանգույցից հետադարձ ուղին գտնելու համար
    $path = array();
    $path[$origin] = new SplDoublyLinkedList();
    $path[$origin]->setIteratorMode(
      SplDoublyLinkedList::IT_MODE_FIFO|SplDoublyLinkedList::IT_MODE_KEEP
    );

    $path[$origin]->push($origin);

    $found = false;
    //մինչ հերթը դատարկ չէ և նշանակման վայրը չի գտնվել
    while (!$q->isEmpty() && $q->bottom() != $destination) {
      $t = $q->dequeue();

      if (!empty($this->graph[$t])) {
       
        foreach ($this->graph[$t] as $vertex) {
          if (!$this->visited[$vertex]) {
            
            $q->enqueue($vertex);
            $this->visited[$vertex] = true;
           
            $path[$vertex] = clone $path[$t];
            $path[$vertex]->push($vertex);
          }
        }
      }
    }

    if (isset($path[$destination])) {
    	print '<pre>';
      echo "$origin to $destination in ", 
        count($path[$destination]) - 1,
        " route ";
      $sep = '';
      foreach ($path[$destination] as $vertex) {
        echo $sep, $vertex;
        $sep = '->';
      }

    }
    else {
      print '<pre>';
      echo " No route from $origin to $destination";
    }
  }
}
$g = new Graph($graph);

$g->breadthFirstSearch('D', 'C');


// B -> F
$g->breadthFirstSearch('B', 'F');


//  A -> C
$g->breadthFirstSearch('A', 'C');


 // A -> G
$g->breadthFirstSearch('A', 'G');


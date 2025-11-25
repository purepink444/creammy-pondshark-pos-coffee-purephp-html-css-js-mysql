<?php
// ========================================================================
//  𝙳𝙰𝚃𝙰 𝚂𝙴𝚃 𝙳𝙴𝙼𝙾
// ========================================================================
$data = [7, 2, 9, 4, 1, 8, 3, 6, 5];




// ========================================================================
//  QUICK SORT
// ========================================================================
function quick_sort($arr) {
    if (count($arr) < 2) return $arr;

    $pivot = $arr[0];
    $left = $right = [];

    for ($i = 1; $i < count($arr); $i++) {
        if ($arr[$i] <= $pivot) $left[] = $arr[$i];
        else $right[] = $arr[$i];
    }
    return array_merge(quick_sort($left), [$pivot], quick_sort($right));
}


// ========================================================================
//  LINEAR SEARCH
// ========================================================================
function linear_search($arr, $target) {
    foreach ($arr as $i => $v) {
        if ($v == $target) return "Found at index $i";
    }
    return "Not Found";
}


// ========================================================================
//  BINARY SEARCH TREE (BST)
// ========================================================================
class Node {
    public $value, $left, $right;
    function __construct($value) { $this->value = $value; }
}

class BST {
    public $root = null;

    function insert($value) {
        $this->root = $this->_insert($this->root, $value);
    }

    private function _insert($node, $value) {
        if (!$node) return new Node($value);
        if ($value < $node->value) $node->left = $this->_insert($node->left, $value);
        else $node->right = $this->_insert($node->right, $value);
        return $node;
    }

    function inorder($node, &$result) {
        if (!$node) return;
        $this->inorder($node->left, $result);
        $result[] = $node->value;
        $this->inorder($node->right, $result);
    }
}


// ========================================================================
//  GRAPH PATHFINDING — DIJKSTRA
// ========================================================================
function dijkstra($graph, $start) {
    $dist = [];
    $visited = [];

    foreach ($graph as $node => $edges) $dist[$node] = INF;
    $dist[$start] = 0;

    while (count($visited) < count($graph)) {
        $minNode = null;

        foreach ($dist as $node => $value) {
            if (!isset($visited[$node]) && ($minNode === null || $value < $dist[$minNode])) {
                $minNode = $node;
            }
        }

        foreach ($graph[$minNode] as $neighbor => $cost) {
            if ($dist[$minNode] + $cost < $dist[$neighbor]) {
                $dist[$neighbor] = $dist[$minNode] + $cost;
            }
        }

        $visited[$minNode] = true;
    }

    return $dist;
}

$graph = [
    'A' => ['B'=>4, 'C'=>2],
    'B' => ['A'=>4, 'D'=>5],
    'C' => ['A'=>2, 'B'=>1, 'D'=>8],
    'D' => ['B'=>5, 'C'=>8],
];


// ========================================================================
//  DYNAMIC PROGRAMMING — FIBONACCI
// ========================================================================
function fib_dp($n) {
    $dp = [0, 1];
    for ($i = 2; $i <= $n; $i++) $dp[$i] = $dp[$i-1] + $dp[$i-2];
    return $dp[$n];
}


// ========================================================================
//  BENCHMARK — เปรียบเทียบเวลา
// ========================================================================
function benchmark($fn, $times = 10000) {
    $start = microtime(true);
    for ($i = 0; $i < $times; $i++) $fn();
    return (microtime(true) - $start) * 1000;
}

$bench_quick = benchmark(function() use ($data) { quick_sort($data); }, 3000);
$bench_bubble = benchmark(function() use ($data) {
    for ($i = 0; $i < count($data)-1; $i++)
        for ($j = 0; $j < count($data)-$i-1; $j++)
            if ($data[$j] > $data[$j+1]) { $tmp = $data[$j]; $data[$j]=$data[$j+1]; $data[$j+1]=$tmp; }
}, 3000);
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Data Structure & Algorithm Lab</title>
<style>
body { font-family: Arial; padding: 20px; background: #f4f4f4; }
.box { background: white; padding: 20px; border-radius: 10px; margin-bottom: 25px; box-shadow: 0 0 10px #ccc; }
pre { background: #222; color: #0f0; padding: 15px; border-radius: 5px; }
</style>
</head>
<body>

<h1>🧪 DSA LAB — ตัวทดสอบโครงสร้างข้อมูลและอัลกอริทึม</h1>

<!-- QUICK SORT -->
<div class="box">
<h2>1. Quick Sort</h2>
<pre><?php print_r(quick_sort($data)); ?></pre>
</div>

<!-- LINEAR SEARCH -->
<div class="box">
<h2>2. Linear Search</h2>
<pre><?php echo linear_search($data, 8); ?></pre>
</div>

<!-- BST -->
<div class="box">
<h2>3. BST Simulation (Inorder Traversal)</h2>
<?php
$bst = new BST();
foreach ($data as $n) $bst->insert($n);
$inorder = [];
$bst->inorder($bst->root, $inorder);
?>
<pre><?php print_r($inorder); ?></pre>
</div>

<!-- GRAPH -->
<div class="box">
<h2>4. Graph Pathfinding (Dijkstra)</h2>
<pre><?php print_r(dijkstra($graph, 'A')); ?></pre>
</div>

<!-- DP -->
<div class="box">
<h2>5. Dynamic Programming (Fibonacci)</h2>
<pre><?php echo "fib(20) = " . fib_dp(20); ?></pre>
</div>

<!-- BENCHMARK -->
<div class="box">
<h2>6. Benchmark เปรียบเทียบอัลกอริทึม</h2>
<pre>
Quick Sort  : <?php echo round($bench_quick, 2); ?> ms (เร็วมาก)
Bubble Sort : <?php echo round($bench_bubble, 2); ?> ms (ช้ากว่าเยอะ)
</pre>
</div>

</body>
</html>

<?php
phpinfo();
class IdThread extends Thread
{
    private $start;
    private $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function run()
    {
        for ($i = $this->start; $i <= $this->end; $i++) {
            echo "Generated ID: $i in Thread " . $this->getThreadId() . "\n";
            // You can perform other tasks with the generated ID here
            // For example, store it in a database, process it, etc.
            usleep(100000); // Simulating some work being done
        }
    }
}

// Number of threads
$numThreads = 5;

// IDs per thread
$idsPerThread = 20;

// Create and start threads
$threads = [];
for ($i = 0; $i < $numThreads; $i++) {
    $startId = $i * $idsPerThread + 1;
    $endId = ($i + 1) * $idsPerThread;
    $threads[$i] = new IdThread($startId, $endId);
    $threads[$i]->start();
}

// Wait for threads to finish
foreach ($threads as $thread) {
    $thread->join();
}

?>

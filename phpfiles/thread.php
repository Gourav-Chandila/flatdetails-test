<?php 
class SynchronizedClass {
    private $semaphore;

    public function __construct() {
        // Create a semaphore
        $this->semaphore = sem_get(1234, 1, 0666, 1);
    }

    public function synchronizedFunction() {
        // Acquire the semaphore
        sem_acquire($this->semaphore);

        // Critical section

        // Release the semaphore
        sem_release($this->semaphore);
    }
}

$synchronizedObject = new SynchronizedClass();

// Call the synchronized function
$synchronizedObject->synchronizedFunction();

?>
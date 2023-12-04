import java.lang.management.ManagementFactory;
import java.lang.management.ThreadMXBean;
import java.util.HashSet;
import java.util.Set;

public class UnsafeUniqueIdGenerator {

    private static int counter = 0;
    private static Set<Integer> generatedIds = new HashSet<>();

    public static void main(String[] args) {
        int numThreads = 50; // Number of threads
        int totalIds = 20000; // Total IDs to generate

        Thread[] threads = new Thread[numThreads];

        // Calculate the number of IDs each thread should generate
        int idsPerThread = totalIds / numThreads;

        // Create and start threads
        for (int i = 0; i < numThreads; i++) {
            threads[i] = new Thread(() -> generateUniqueIds(idsPerThread));
            threads[i].start();
        }

        // Wait for all threads to complete
        for (Thread thread : threads) {
            try {
                thread.join();
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
        }

        // Print all generated IDs
        System.out.println("All generated IDs:");
        for (int id : generatedIds) {
            System.out.print(id + " ");
        }

        // Check for duplicates after all threads have completed
        checkForDuplicates();
    }

    private static void generateUniqueIds(int numIds) {
        ThreadMXBean threadMXBean = ManagementFactory.getThreadMXBean();

        for (int i = 0; i < numIds; i++) {
            int uniqueId = counter++;
            generatedIds.add(uniqueId);
            long threadId = threadMXBean.getCurrentThreadCpuTime(); // Get thread ID
            System.out.println("Thread " + threadId + ": Generated ID " + uniqueId);

            // Simulate some work
            try {
                Thread.sleep(100); // Simulating some processing time
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
        }
    }

    private static void checkForDuplicates() {
        System.out.println("\nChecking for duplicates...");

        Set<Integer> duplicateSet = new HashSet<>();
        for (int id : generatedIds) {
            if (!duplicateSet.add(id)) {
                System.out.println("Duplicate ID found: " + id);
            }
        }

        System.out.println("Duplicate check completed.");
    }
}

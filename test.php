<?php
class FileRotationTest {
    private $apiUrl = 'http://localhost:8005/index.php';
    private $dataDir = './data/';

    /**
     * Run all tests
     */
    public function runTests() {
        echo "Starting tests...\n";

        $this->setUp();
        $this->testFirstRequestCreates100();
        $this->testSecondRequestCreates99();
        $this->testRotationBackTo100();
        $this->testContentIsStoredCorrectly();
        $this->tearDown();

        echo "All tests completed.\n";
    }

    /**
     * Set up the test environment
     */
    private function setUp() {
        echo "Setting up test environment...\n";

        // Clean up any existing test files
        $this->cleanupFiles();

        // Create data directory if it doesn't exist
        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0755, true);
        }
    }

    /**
     * Clean up after tests
     */
    private function tearDown() {
        echo "Cleaning up test environment...\n";
        $this->cleanupFiles();
    }

    /**
     * Remove all test files
     */
    private function cleanupFiles() {
        $files = glob($this->dataDir . '*.txt');
        foreach ($files as $file) {
            unlink($file);
        }
    }

    /**
     * Send a request to the API
     */
    private function sendRequest($data = ['test' => 'data']) {
        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Test if first request creates 100.txt
     */
    public function testFirstRequestCreates100() {
        echo "Testing first request creates 100.txt...\n";

        $response = $this->sendRequest(['test' => 'first request']);

        if ($response['filename'] === '100.txt' && file_exists($this->dataDir . '100.txt')) {
            echo "✓ PASS: First request created 100.txt\n";
        } else {
            echo "✗ FAIL: First request did not create 100.txt\n";
        }
    }

    /**
     * Test if second request creates 99.txt
     */
    public function testSecondRequestCreates99() {
        echo "Testing second request creates 99.txt...\n";

        $response = $this->sendRequest(['test' => 'second request']);

        if ($response['filename'] === '99.txt' && file_exists($this->dataDir . '99.txt')) {
            echo "✓ PASS: Second request created 99.txt\n";
        } else {
            echo "✗ FAIL: Second request did not create 99.txt\n";
        }
    }

    /**
     * Test rotation back to 100.txt after reaching 1.txt
     */
    public function testRotationBackTo100() {
        echo "Testing rotation back to 100.txt after reaching 1.txt...\n";

        // Clean up existing files first
        $this->cleanupFiles();

        // Create file 1.txt manually
        file_put_contents($this->dataDir . '1.txt', '{"test":"data"}');

        // Send a request, should create 100.txt
        $response = $this->sendRequest(['test' => 'rotation test']);

        if ($response['filename'] === '100.txt' && file_exists($this->dataDir . '100.txt')) {
            echo "✓ PASS: Successfully rotated back to 100.txt after 1.txt\n";
        } else {
            echo "✗ FAIL: Did not rotate back to 100.txt after 1.txt\n";
        }
    }

    /**
     * Test if content is stored correctly as JSON
     */
    public function testContentIsStoredCorrectly() {
        echo "Testing if content is stored correctly as JSON...\n";

        $testData = ['key' => 'value', 'nested' => ['data' => true]];
        $response = $this->sendRequest($testData);

        $storedContent = file_get_contents($this->dataDir . $response['filename']);
        $storedData = json_decode($storedContent, true);

        if (isset($storedData['content']) &&
            isset($storedData['content']['key']) &&
            $storedData['content']['key'] === 'value') {
            echo "✓ PASS: Content stored correctly as JSON\n";
        } else {
            echo "✗ FAIL: Content not stored correctly\n";
        }
    }
}

// Run the tests
$test = new FileRotationTest();
$test->runTests();
?>
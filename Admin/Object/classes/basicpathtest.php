<?php

use PHPUnit\Framework\TestCase;

require_once 'Package.php';

class BasicPathTest extends TestCase {
    private $package;

    protected function setUp(): void {
        // Mock the database connection
        $conn = $this->createMock(mysqli::class);

        // Mock the result returned by the database query
        $resultMock = $this->createMock(mysqli_result::class);
        $resultMock->method('fetch_assoc')->willReturn([
            'packageID' => 1,
            'packageName' => 'Test Package',
            'airline' => 'Test Airline',
            'price' => 1000,
            'packageType' => 'Standard',
            'destinationID' => 1,
            'destinationName' => 'Paris'
        ]);
        
        // Mock the prepare method to return a statement mock
        $stmtMock = $this->createMock(mysqli_stmt::class);
        $stmtMock->method('bind_param')->willReturn(true);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('get_result')->willReturn($resultMock);

        // When prepare is called, return the mock statement
        $conn->method('prepare')->willReturn($stmtMock);

        // Initialize the Package class with the mocked database connection
        $this->package = new Package($conn, 1);  // Assuming 1 is the packageID
    }

    // Path 1: Check if the package price is returned correctly
    public function testPath1() {
        $this->assertEquals(1000, $this->package->getPrice());
    }

    // Path 2: Check if the package destination is returned correctly
    public function testPath2() {
        $this->assertEquals('Paris', $this->package->getDestinationName());
    }

    // Path 3: Check if package type is returned correctly
    public function testPath3() {
        $this->assertEquals('Standard', $this->package->getPackageType());
    }

    // Path 4: Test setting a new price for the package
    public function testPath4() {
        $newPrice = 1200;
        $this->package->setPrice($newPrice);
        $this->assertEquals($newPrice, $this->package->getPrice());
    }

    // Path 5: Check if package name is returned correctly
    public function testPath5() {
        $this->assertEquals('Test Package', $this->package->getPackageName());
    }

    // Path 6: Ensure packageID is set correctly
    public function testPath6() {
        $this->assertEquals(1, $this->package->getPackageID());
    }

    // Path 7: Ensure package airline is returned correctly
    public function testPath7() {
        $this->assertEquals('Test Airline', $this->package->getAirline());
    }
}

?>

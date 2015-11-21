<?php





require_once '../../Objects/ASDI Subsystem/CoordinateSet';

class CoordinateSetTest extends PHPUnit_Framework_TestCase
{
    public $testCoordinateSet;

    public $longitude = "test longitude";
    public $latitude = "test latitude";


    public function setUp()
    {
        $this->testCoordinateSet = new CoordinateSet($this->longitude, $this->latitude);

    }


    /*
     * getLongitude() returns String coordinateLongitude
     * @requires this.longitude != null
     * @ensures coordinateLongitude != null
     */
    public function testGetLongitude()
    {
        // @requires this.longitude != null
        $this->assertTrue($this->longitude != null);

        $coordinateLongitude = $this->testCoordinateSet->getLongitude();

        // @ensures coordinateLongitude != null
        $this->assertTrue($coordinateLongitude != null);

    }



    public function testGetLatitude()
    {

    }




}

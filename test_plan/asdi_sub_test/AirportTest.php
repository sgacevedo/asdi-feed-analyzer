<?php

/**
 * Testing Airport class
 */
require_once '../../Objects/ASDI Subsystem/Airport';

class AirportTest extends PHPUnit_Framework_TestCase
{

    public $testAirport;
    public $name = "";
    public $city = "";
    public $icaoCode = "";
    public $faaId = "";

    public function setUp()
    {

        $this->testAirport = new Airport($this->name, $this->city, $this->icaoCode, $this->faaId);

    }


    /*
     * getName() returns String airportName
     * @requires this.name != null
     * @ensures airportName != null && airportName.length > 0
     */
    public function testKnowName()
    {
        // @requires this.name != null
        $this->assertTrue($this->name != null);

        $airportName = $this->testAirport->getName();


        // @ensures airportName != null && airportName.length > 0
        $this->assertTrue(strlen($airportName) > 0);
    }

    /*
     * getCity() returns String airportCity
     * @requires this.city != null
     * @ensures airportCity != null && airportCity.length > 0
     */
    public function testGetCity()
    {
        // @requires this.city != null
        $this->assertTrue($this->city != null);

        $airportCity = $this->testAirport->getCity();


        // @ensures airportCity != null && airportCity.length > 0
        $this->assertTrue(strlen($airportCity) > 0);
    }


    /*
     * getICAOcode()  returns String icaoCode
     * @requires this.icao != null
     * @ensures icaoCode != null
     */
    public function testGetICAOcode()
    {
        // @requires this.icao != null
        $this->assertTrue($this->icaoCode != null);

        $icaoCode = $this->testAirport->getICAOcode();

        // @ensures icaoCode != null
        $this->assertTrue($icaoCode != null);
    }

    /*
     * getFAAid() returns String faaId
     * @requires this.faa != null
     * @ensures faaId != null && faaId.length > 0
     */
    public function testGetFAAid()
    {
        // @requires this.faa != null
        $this->assertTrue($this->faaId != null);

        $faaId = $this->testAirport->getFAAid();

        // @ensures faaId != null && faaId.length > 0
        $this->assertTrue(strlen($faaId) > 0);


    }
}

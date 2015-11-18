<?php
/**
 * Created by PhpStorm.
 * User: Juan
 * Date: 11/17/2015
 * Time: 12:02 PM
 */

require_once '../Objects/Request.php';
require_once(__DIR__ . '../phpunit.phar');

class RequestTest extends PHPUnit_Framework_TestCase
{
    public $test;

    public function setUp()
    {
        $this->test = new Request("theType", "theValue");
    }

    public function testAddParameter()
    {

    }

}
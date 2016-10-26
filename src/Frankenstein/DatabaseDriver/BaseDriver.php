<?php

namespace Frankenstein\DatabaseDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

abstract class BaseDriver
{
    protected $username;
    protected $password;
    protected $database;
    
    /** @var  RemoteWebDriver */
    protected $driver;
    
    public function __construct($username, $password, $database)
    {
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }
    
    public function setUp()
    {
        $this->driver = RemoteWebDriver::create('http://localhost:4444/wd/hub', DesiredCapabilities::chrome());
        $this->driver->get('http://localhost/phpmyadmin');
        $this->login();
    }
    
    public function tearDown()
    {
        $this->driver->close();
    }
    
    private function login()
    {
        $form = $this->driver->findElement(WebDriverBy::cssSelector('form[name=login_form]'));
        $form->findElement(WebDriverBy::cssSelector('#input_username'))->sendKeys($this->username);
        $form->findElement(WebDriverBy::cssSelector('#input_password'))->sendKeys($this->password);
        $form->findElement(WebDriverBy::cssSelector('#input_go'))->click();
    }
}
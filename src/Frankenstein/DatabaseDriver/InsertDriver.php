<?php

namespace Frankenstein\DatabaseDriver;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class InsertDriver extends BaseDriver
{
    
    public function insert($table, $columnValues)
    {
        parent::setUp();
        try {
            $this->driver->findElement(WebDriverBy::xpath("//a[text()='$this->database']"))->click();
            $this->driver->wait(30)->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::xpath("//a[text()='$table']")));
            $this->driver->findElement(WebDriverBy::xpath("//a[text()='$table']"))->click();
            $this->driver->wait(30)->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::xpath("//a[contains(text(), 'Insert')]")));
            $this->driver->findElement(WebDriverBy::xpath("//a[contains(text(), 'Insert')]"))->click();
            $this->driver->wait(30)->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('#insertForm')));
            $columnHashes = $this->getHashes($columnValues);
            foreach ($columnHashes as $column => $value) {
                $this->driver->findElement(WebDriverBy::cssSelector("[name*='fields[multi_edit][0][${value['hash']}]'"))->sendKeys($value['value']);
            }
            $this->driver->findElement(WebDriverBy::cssSelector('#insertForm input[type=submit]'))->click();
            $this->driver->wait(30)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('success')));
        } finally {
            parent::tearDown();
        }
    }
    
    private function getHashes($columnValues)
    {
        return array_map(function($k, $v){
            $columnName = $this->driver->findElement(WebDriverBy::cssSelector("input[value=$k]"))->getAttribute('name');
            $nameArray = explode('[', $columnName);
            $hash = str_replace(']', '', $nameArray[3]);
            return ['hash' => $hash, 'value' => $v];
        }, array_keys($columnValues), $columnValues);
    }
    
}
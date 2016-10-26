<?php

namespace Frankenstein\DatabaseDriver;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class CreateDriver extends BaseDriver
{
    public function create($tableName, $columns)
    {
        parent::setUp();
        try {
            $this->driver->findElement(WebDriverBy::xpath("//a[text()='$this->database']"))->click();
            $this->driver->wait(30)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector("input[name='table']")));
            $this->driver->findElement(WebDriverBy::cssSelector("input[name='table']"))->sendKeys($tableName);
            $this->driver->findElement(WebDriverBy::cssSelector("input[name='num_fields']"))->sendKeys(count($columns))->submit();#table_columns tbody tr:nth-child()
            $this->driver->wait(30)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector("#table_columns tbody")));
            for($i = 0; $i < count($columns); $i++) {
                $column = $columns[$i];
                foreach($column as $rowAttr => $rowValue) {
                    try{
                        $this->driver->wait(1)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('button[title=Close]')));
                        $this->driver->findElement(WebDriverBy::cssSelector('.ui-dialog-buttonset button:first-child'))->click();
                        $this->driver->wait(1)->until(!WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('ui-widget-overlay')));
                    } catch (\Exception $e ) { /* swallow if not present */ }
                    $element = $this->driver->findElement(WebDriverBy::name("{$rowAttr}[$i]"));
                    if($element->getAttribute('type') == 'checkbox') {
                        $element->click();
                    } else {
                        $element->sendKeys($rowValue);
                    }
                }
            }
            
            $this->driver->findElement(WebDriverBy::cssSelector('.create_table_form'))->submit();
            
            
//            $this->driver->findElement(WebDriverBy::xpath("//a[contains(text(), 'Insert')]"))->click();
//            $this->driver->wait(30)->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('#insertForm')));
//            $columnHashes = $this->getHashes($columnValues);
//            foreach ($columnHashes as $column => $value) {
//                $this->driver->findElement(WebDriverBy::cssSelector("[name*='fields[multi_edit][0][${value['hash']}]'"))->sendKeys($value['value']);
//            }
//            $this->driver->findElement(WebDriverBy::cssSelector('#insertForm input[type=submit]'))->click();
//            $this->driver->wait(30)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('success')));
        } catch(\Throwable $t){
            throw $t;
        } finally {
            parent::tearDown();
        }
    }
}
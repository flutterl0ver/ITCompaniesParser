<?php

namespace App\Services\Parsers;

use Facebook\WebDriver\Firefox\FirefoxOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

class NalogParser
{
    private RemoteWebDriver $webDriver;

    public function startBrowser(): void
    {
        $host = 'http://localhost:4444/';
        $capabilities = DesiredCapabilities::firefox();
        $firefoxOptions = new FirefoxOptions();
//        $firefoxOptions->addArguments(['--headless']);
        $capabilities->setCapability(FirefoxOptions::CAPABILITY, $firefoxOptions);
        $this->webDriver = RemoteWebDriver::create($host, $capabilities);
        $this->webDriver->manage()->window()->maximize();
        $this->webDriver->get('https://pb.nalog.ru/index.html');
    }

    public function getNalogInfo(string $inn)
    {
        $this->webDriver->findElement(WebDriverBy::cssSelector('input[type="text"]'))->sendKeys($inn);
        $this->webDriver->findElement(WebDriverBy::cssSelector('button[type="submit"]'))->click();
    }

    public function closeBrowser(): void
    {
        $this->webDriver->close();
    }
}

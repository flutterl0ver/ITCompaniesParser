<?php

namespace App\Services;

use Facebook\WebDriver\Firefox\FirefoxOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;

class CompanyApprovedService
{
    private RemoteWebDriver $webDriver;

    public function startBrowser(): void
    {
        $host = 'http://localhost:4444/';
        $capabilities = DesiredCapabilities::firefox();
        $firefoxOptions = new FirefoxOptions();
        $firefoxOptions->addArguments(['--headless']);
        $capabilities->setCapability(FirefoxOptions::CAPABILITY, $firefoxOptions);
        $this->webDriver = RemoteWebDriver::create($host, $capabilities);
        $this->webDriver->manage()->window()->maximize();
        $this->webDriver->get('https://www.gosuslugi.ru/itorgs');
    }

    public function isApproved(string $inn): bool
    {
        $wait = new WebDriverWait($this->webDriver, 30);

        $wait->until(
            WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::cssSelector('input[type="text"]')
            )
        );
        $input = $this->webDriver->findElement(WebDriverBy::cssSelector('input[type="text"]'));

        $input->clear();
        sleep(2);
        $input->sendKeys($inn);
        sleep(2);

        return count($this->webDriver->findElements(WebDriverBy::cssSelector('div[role="option"]'))) > 0;
    }

    public function closeBrowser(): void
    {
        $this->webDriver->close();
    }
}

<?php

namespace App\Services\Parsers;

use Facebook\WebDriver\Firefox\FirefoxOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;

class NalogParser
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
    }

    public function getNalogInfo(string $inn): array
    {
        $this->webDriver->get('https://pb.nalog.ru/index.html');
        $this->webDriver->findElement(WebDriverBy::cssSelector('input[type="text"]'))->sendKeys($inn);
        $this->webDriver->findElement(WebDriverBy::cssSelector('button[type="submit"]'))->click();
        $wait = new WebDriverWait($this->webDriver, 30);
        $wait->until(
            WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::cssSelector('div[class="pb-card pb-card--clickable"]')
            )
        );
        $url = $this->webDriver->findElement(WebDriverBy::cssSelector('div[class="pb-card pb-card--clickable"]'))->getAttribute('data-href');
        $this->webDriver->get('https://pb.nalog.ru/' . $url);
        $wait->until(
            WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::cssSelector('a[data-appeal-kind="SSCHR"]')
            )
        );

        $workers = $this->webDriver->findElement(WebDriverBy::cssSelector('a[data-type="sschr"]'))->getAttribute('data-val');
        $tax = $this->webDriver->findElement(WebDriverBy::cssSelector('a[data-type="taxpay"]'))->getAttribute('data-val');
        $incomeHeader = $this->webDriver->findElement(WebDriverBy::xpath("//span[text()='Доход']"));
        $expenseHeader = $this->webDriver->findElement(WebDriverBy::xpath("//span[text()='Расход']"));
        $income = $incomeHeader->findElement(WebDriverBy::xpath("following-sibling::*[1]"))->getDomProperty('innerText');
        $expense = $expenseHeader->findElement(WebDriverBy::xpath("following-sibling::*[1]"))->getDomProperty('innerText');

        return [
            'workers' => $workers,
            'tax' => $tax,
            'income' => $income,
            'expense' => $expense
        ];
    }

    private function formatInt($str): int
    {
        $lastSpace = strrpos($str, ' ');
        $str = str_replace(' ', '', substr($str, 0, $lastSpace));
        return intval($str);
    }

    public function closeBrowser(): void
    {
        $this->webDriver->close();
    }
}

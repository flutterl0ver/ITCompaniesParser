<?php

namespace App\Services\Parsers;

use App\Services\DTO\Company;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Http;

const BASE_URL = 'https://www.rusprofile.ru/codes/';

class RusprofileParser
{
    public function parseCompanies() : array
    {
        libxml_use_internal_errors(true);

        $codes = [
            620000, 630000
        ];
        $result = [];
        foreach($codes as $code)
        {
            $i = 9;
            $isEnd = true;
            do {
                $i++;
                $url = BASE_URL.$code;
                if($i > 1) $url .= '/'.$i;
                $result = array_merge($result, $this->parsePage($url, $isEnd));
            } while(!$isEnd);
        }

        libxml_use_internal_errors(false);
        return $result;
    }

    private function parsePage(string $url, bool& $isEnd) : array
    {
        $html = Http::get($url)->body();
        $dom = new DOMDocument();
        $dom->loadHTML($html);

        $xpath = new DOMXPath($dom);
        $names = $xpath->query("//a[@class='list-element__title']");
        $addresses = $xpath->query("//div[@class='list-element__address']");
        $infoNodes = $xpath->query("//div[@class='list-element__row-info']");

        $totalRecords = $names->length;

        $titles = $xpath->query("//div[@class='tile-item__title']");
        if($titles->length >= 2)
        {
            $title = $titles->item(1);
            echo $title->getNodePath();
            return [];
        }

        $result = [];
        for($i = 0; $i < $names->length; $i++)
        {
            $name = $names->item($i)->textContent;
            $address = $addresses->item($i)->textContent;
            $info = $infoNodes->item($i)->childNodes;
//            $inn = $this->formatInfo($info->item(1)->textContent);
//            $ogrn = $this->formatInfo($info->item(3)->textContent);
//            $regDate = $this->formatInfo($info->item(5)->textContent);
            $result[] = new Company($name, $address, "a", "a", new \DateTime("now"));
        }
        $isEnd = $xpath->query("//a[@class='nav-arrow nav-next']")->length == 0;
        return $result;
    }

    private function formatInfo(string $info) : string
    {
        $point = strpos($info, ':');
        return substr($info, $point + 2, strlen($info) - $point - 2);
    }
}

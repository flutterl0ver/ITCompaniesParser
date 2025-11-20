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

        $urlCodes = [
            620000
        ];
        $result = [];
        foreach($urlCodes as $code)
        {
            $i = 0;
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
            $titlePath = $titles->item(1)->getNodePath();
            $divName = basename(dirname($titlePath));
            $totalRecords = intval(substr($divName, 4, strlen($divName) - 5)) - 1;
        }

        $result = [];
        for($i = 0; $i < $totalRecords; $i++)
        {
            $name = $names->item($i)->textContent;
            $address = $addresses->item($i)->textContent;
            $info = $infoNodes->item($i)->childNodes;
            $this->getInfo($info, $inn, $ogrn, $regDate);
            $result[] = new Company($name, $address, $inn, $ogrn, new \DateTime($regDate));
        }
        $isEnd = $xpath->query("//a[@class='nav-arrow nav-next']")->length == 0;
        return $result;
    }

    private function getInfo(\DOMNodeList $info, &$inn, &$ogrn, &$regDate)
    {
        $inn = $ogrn = $regDate = null;

        $infos = [];
        for($i = 1; $i < $info->length; $i += 2)
        {
            $infos[] = $info->item($i)->textContent;
        }
        foreach($infos as $infoText)
        {
            if(str_starts_with($infoText, 'ИНН')) $inn = $this->formatInfo($infoText);
            if(str_starts_with($infoText, 'ОГРН')) $ogrn = $this->formatInfo($infoText);
            if(str_starts_with($infoText, 'Дата регистрации')) $regDate = $this->formatInfo($infoText);
        }
    }

    private function formatInfo(string $info) : string
    {
        $point = strpos($info, ':');
        return substr($info, $point + 2, strlen($info) - $point - 2);
    }
}

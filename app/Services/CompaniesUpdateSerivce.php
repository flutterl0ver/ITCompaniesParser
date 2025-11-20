<?php

namespace App\Services;

use App\Models\Company;
use App\Services\Parsers\RusprofileParser;

class CompaniesUpdateSerivce
{
    public function updateAll(): void
    {
        $parser = new RusprofileParser();
        $companies = $parser->parseCompanies();

        Company::query()->delete();

        $companiesData = [];
        foreach($companies as $company)
        {
            $companiesData[] = [
                'inn' => $company->inn,
                'name' => $company->name,
                'address' => $company->address,
                'ogrn' => $company->ogrn,
                'approved' => random_int(0, 2) == 0
            ];
        }
        $companyChunks = array_chunk($companiesData, 1000);
        foreach($companyChunks as $chunk)
        {
            Company::insert($chunk);
        }
    }
}

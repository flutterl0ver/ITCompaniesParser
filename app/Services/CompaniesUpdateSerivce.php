<?php

namespace App\Services;

use App\Models\Company;
use App\Services\Parsers\RusprofileParser;

class CompaniesUpdateSerivce
{
    public function updateAll(): array
    {
        $parser = new RusprofileParser();
        $companies = $parser->parseCompanies();

        $oldRegistry = Company::where('approved', true)->pluck('inn')->toArray();

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

        $newRegistry = Company::where('approved', true)->pluck('inn')->toArray();
        $this->compareRegistries($oldRegistry, $newRegistry, $addedArray, $removedArray);
        return [$addedArray, $removedArray];
    }

    private function compareRegistries($oldRegistry, $newRegistry, &$addedArray, &$removedArray): void
    {
        $addedArray = [];
        $removedArray = [];
        foreach($oldRegistry as $entry)
        {
            if(!in_array($entry, $newRegistry)) $removedArray[] = $entry;
        }

        foreach($newRegistry as $entry)
        {
            if(!in_array($entry, $oldRegistry)) $addedArray[] = $entry;
        }
    }
}

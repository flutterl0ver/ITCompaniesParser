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

        $oldRegistry = Company::where('approved', true)->pluck('inn')->toArray();

        Company::query()->delete();

        $companiesData = [];
        $approvedService = new CompanyApprovedService();
        $approvedService->startBrowser();
        foreach($companies as $company)
        {
            $isApproved = $approvedService->isApproved($company->inn);

            $updateStatus = 0;
            if($isApproved && !in_array($company->inn, $oldRegistry)) $updateStatus = 1;
            else if(!$isApproved && in_array($company->inn, $oldRegistry)) $updateStatus = -1;

            $companiesData[] = [
                'inn' => $company->inn,
                'name' => $company->name,
                'address' => $company->address,
                'ogrn' => $company->ogrn,
                'approved' => $isApproved,
                'updated_at' => new \DateTime(),
                'update_status' => $updateStatus
            ];
        }
        $approvedService->closeBrowser();
        $companyChunks = array_chunk($companiesData, 1000);
        foreach($companyChunks as $chunk)
        {
            Company::insert($chunk);
        }
    }
}

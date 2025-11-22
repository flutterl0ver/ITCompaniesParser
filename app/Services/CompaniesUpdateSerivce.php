<?php

namespace App\Services;

use App\Models\Company;
use App\Services\Parsers\NalogParser;
use App\Services\Parsers\RusprofileParser;

class CompaniesUpdateSerivce
{
    public function updateAll(): void
    {
        $globalInfo = json_decode(file_get_contents('globalInfo.json'), true);
        if($globalInfo['update_in_progress']) return;

        $globalInfo['update_in_progress'] = true;
        file_put_contents('globalInfo.json', json_encode($globalInfo, JSON_PRETTY_PRINT));

        set_time_limit(0);
        ignore_user_abort(true);

        $parser = new RusprofileParser();
        $companies = $parser->parseCompanies();

        $oldRegistry = Company::where('approved', true)->pluck('inn')->toArray();

        Company::query()->delete();

        $companiesData = [];
        $approvedService = new CompanyApprovedService();
        $approvedService->startBrowser();

        $nalogParser = new NalogParser();
        $nalogParser->startBrowser();

        foreach($companies as $company)
        {
            $isApproved = $approvedService->isApproved($company->inn);

            $updateStatus = 0;
            if($isApproved && !in_array($company->inn, $oldRegistry)) $updateStatus = 1;
            else if(!$isApproved && in_array($company->inn, $oldRegistry)) $updateStatus = -1;

            $nalogData = $nalogParser->getNalogInfo($company->inn);

            $companiesData[] = [
                'inn' => $company->inn,
                'name' => $company->name,
                'address' => $company->address,
                'ogrn' => $company->ogrn,
                'approved' => $isApproved,
                'updated_at' => new \DateTime(),
                'update_status' => $updateStatus,
                'workers_count' => $nalogData['workers'],
                'tax' => $nalogData['tax'],
                'income' => $nalogData['income'],
                'expense' => $nalogData['expense'],
                'simple_tax' => $nalogData['simple_tax'],
                'tax_issues' => false
            ];
        }
        $nalogParser->closeBrowser();
        $approvedService->closeBrowser();

        $companyChunks = array_chunk($companiesData, 1000);
        foreach($companyChunks as $chunk)
        {
            Company::insert($chunk);
        }

        $now = new \DateTime();
        $now = $now->format('j.m.Y H:i');
        $json = json_decode(file_get_contents(public_path('globalInfo.json')), true);
        $json['last_update'] = $now;
        file_put_contents(public_path('globalInfo.json'), json_encode($json, JSON_PRETTY_PRINT));

        $service = new \App\Services\MailService();
        $service->reportAllChanges();

        $globalInfo = json_decode(file_get_contents('globalInfo.json'), true);
        $globalInfo['update_in_progress'] = false;
        file_put_contents('globalInfo.json', json_encode($globalInfo, JSON_PRETTY_PRINT));
    }
}

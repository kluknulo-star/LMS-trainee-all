<?php

namespace App\Courses\Helpers;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ClientLRS
{
    public static function sendStatement($statement): Response
    {
        $tokenLRS = config('services.lrs.token');
        $domainLRS = config('services.lrs.domain');

        $response = Http::withHeaders([
            'Authorization' => $tokenLRS,
        ])->withBody($statement, 'application/json')
            ->post($domainLRS . '/api/statements');

        return $response;
    }

    public static function getStatements(string $userMail = "", string $verb = "", string $object = "", string $context = ""): Response
    {
        $filters = StatementsHelper::compileFilters(actor: $userMail, verb: $verb, object: $object, context: $context);

        $tokenLRS = config('services.lrs.token');
        $domainLRS = config('services.lrs.domain');

        $response = Http::withHeaders([
            'Authorization' => $tokenLRS,
        ])->get($domainLRS . '/api/statements', $filters);

        return $response;
    }

    public static function getProgressStudent(string $userMail, int $courseId) : array
    {
        $progressSections = [];

        $requestPassed = ClientLRS::getStatements(userMail: $userMail, verb: 'passed', context: $courseId);
        $requestLaunched = ClientLRS::getStatements(userMail: $userMail, verb: 'launched', context: $courseId);


        $bodyPassedRequest = json_decode($requestPassed->body())->body;
        $bodyLaunchedRequest = json_decode($requestLaunched->body())->body;

        $progressSections['passed'] = StatementsHelper::getStaticByVerbFromStatement($bodyPassedRequest, 'passed');
        $progressSections['launched'] = StatementsHelper::getStaticByVerbFromStatement($bodyLaunchedRequest, 'launched');

        return $progressSections;
    }
}

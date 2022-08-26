<?php

namespace App\Courses\Helpers;

use App\Courses\Models\Course;
use App\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ClientLRS
{
    /**
     * Simple sender xAPI statements
     *
     * @return Response $response
     */
    public static function sendStatement(User $user, string $verb, Model $course, mixed $section = null): Response
    {
        $statement = StatementHelper::compileStatement($user, $verb, $course, $section);
        $tokenLRS = config('services.lrs.token');
        $domainLRS = config('services.lrs.domain');

        $response = Http::withHeaders([
            'Authorization' => $tokenLRS,
        ])->withBody($statement, 'application/json')
            ->post($domainLRS . '/api/statements');

        return $response;
    }

    /**
     * Simple getter xAPI statements
     *
     * @return Response $response
     */
    public static function getStatements(array $userMails = [], array $verbs = [], array $objects = [], array $contexts = []): Response
    {
        $filters = StatementHelper::compileFilters(actors: $userMails, verbs: $verbs, objects: $objects, contexts: $contexts);
        $tokenLRS = config('services.lrs.token');
        $domainLRS = config('services.lrs.domain');

        $response = Http::withHeaders([
            'Authorization' => $tokenLRS,
        ])->withBody($filters, 'application/json')
            ->post($domainLRS . '/api/statements/get');

        return $response;
    }

    /**
     * Simple getter and converter xAPI statements to short user statistic for a specific course
     *
     * @return array $progressSections
     */
    public static function getProgressStudent(string $userMail, int $courseId) : array
    {
        $responsePassed = ClientLRS::getStatements(userMails:[$userMail],verbs:['passed'],contexts:[$courseId]);
        $responseLaunched = ClientLRS::getStatements(userMails:[$userMail],verbs:['launched'],contexts:[$courseId]);

        if ($responsePassed->status() != 200){
            dd($responsePassed->body());
        }

        $passedStatements = json_decode($responsePassed->body())->body;
        $launchedStatements = json_decode($responseLaunched->body())->body;
        $progressSections = [
            'passed' => StatementHelper::getIdSections($passedStatements),
            'launched' => StatementHelper::getIdSections($launchedStatements),
        ];

        return $progressSections;
    }
}

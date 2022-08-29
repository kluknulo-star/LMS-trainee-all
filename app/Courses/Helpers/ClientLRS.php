<?php

namespace App\Courses\Helpers;

use App\Courses\Models\Course;
use App\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ClientLRS
{
    const LAUNCHED = 'launched';
    const PASSED = 'passed';
    const FAILED = 'failed';
    const COMPLETED = 'completed';

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
    public static function getStatements(array $userMails = [], array $verbs = [], array $objects = [], array $contexts = []): array
    {
        $filters = StatementHelper::compileFilters(actors: $userMails, verbs: $verbs, objects: $objects, contexts: $contexts);
        $tokenLRS = config('services.lrs.token');
        $domainLRS = config('services.lrs.domain');
        $nextPage = $domainLRS . '/api/statements/get';
        $statementsPull = [];

        do {
            $response = Http::withHeaders([
                'Authorization' => $tokenLRS,
            ])->withBody($filters, 'application/json')
                ->post($nextPage);
            $nextPage = optional(json_decode($response->body()))->next_page_url;
            $statements = optional(json_decode($response->body()))->body;
            if ($statements) {
                array_walk($statements, function (&$item) {
                    $item = $item->content;
                });
                $statementsPull = array_merge($statementsPull, $statements);
            }
        } while ($nextPage);

        return $statementsPull;
    }

    /**
     * Simple getter and converter xAPI statements to short user statistic for a specific course
     *
     * @return array $progressSections
     */
    public static function getProgressStudent(string $userMail, int $courseId): array
    {
        $passedStatements = ClientLRS::getStatements(userMails: [$userMail], verbs: [self::PASSED], contexts: [$courseId]);
        $launchedStatements = ClientLRS::getStatements(userMails: [$userMail], verbs: [self::LAUNCHED], contexts: [$courseId]);

        $progressSections = [
            self::LAUNCHED => StatementHelper::getIdSections($launchedStatements),
            self::PASSED => StatementHelper::getIdSections($passedStatements),
        ];
        return $progressSections;
    }

    public static function getCoursesStatements(array $courseIds) : array
    {
        $launchedStatements = self::getStatements(verbs: [self::LAUNCHED], objects: $courseIds);
        $PassedStatements = self::getStatements(verbs: [self::PASSED], objects: $courseIds);

        $progressUsers = [
            self::LAUNCHED => StatementHelper::getEmailUsers($PassedStatements),
            self::PASSED => StatementHelper::getEmailUsers($launchedStatements),
        ];
        return $progressUsers;
    }
}

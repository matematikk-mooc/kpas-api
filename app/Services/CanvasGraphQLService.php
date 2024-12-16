<?php

namespace App\Services;

use App\Utils\SentryTrace;
use Exception;

class CanvasGraphQLService
{
        /**
     * @var string
     */
    protected $domain;
    /**
     * @var string
     */
    protected $accessKey;

    public function __construct()
    {
        $this->domain = config('canvas.domain');
        $this->accessKey = config('canvas.access_key');
    }

    function modulesConnection($course_id)
    {
        $url = str_replace('/v1', '', $this->domain) . '/graphql';
        $query = 'query GetCourseModules($courseId: ID!){
            course(id: $courseId) {
                name
                modulesConnection {
                    nodes {
                        _id
                        name
                        moduleItems {
                            content {
                                ... on Page {
                                    _id
                                    title
                                }
                                ... on Assignment {
                                    _id
                                    name
                                    description
                                    state
                                }
                                ... on Discussion {
                                    _id
                                    title
                                    message
                                }
                            }
                        }
                    }
                }
            }
        }';

        $variables = ['courseId' => $course_id];
        $headers = ['Authorization' => "Bearer {$this->accessKey}"];

        try {
            $response = SentryTrace::graphqlRequest($url, $query, $variables, $headers);
            return $response;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

}

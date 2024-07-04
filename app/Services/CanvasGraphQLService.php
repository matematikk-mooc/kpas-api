<?php

namespace App\Services;

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
        $queryData = 'query GetCourseModules($courseId: ID!){
            course(id: $courseId) {
                name
                modulesConnection {
                    nodes {
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

        try {
            $url = str_replace('/v1', '', $this->domain) . '/graphql';
            $headers = array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->accessKey,
            );
            $options = array(
                'http' => array(
                    'method' => 'POST',
                    'header' => implode("\r\n", $headers),
                    'content' => json_encode(array('query' => $queryData, 'variables' => array('courseId' => $course_id)))
                )
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            if ($result === false) {
                throw new Exception('Error fetching data');
            }
            $response = json_decode($result, true);
            return $response;
        } catch (Exception $error) {
            echo 'Error fetching course data: ' . $error->getMessage();
            return null;
        }
    }

}

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

        try {
            $url = str_replace('/v1', '', $this->domain) . '/graphql';
            $headers = array(
                'Authorization: Bearer ' . $this->accessKey,
                'Content-Type: application/json'
            );
            $postData = json_encode(array('query' => $queryData, 'variables' => array('courseId' => $course_id)));
        
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
            $result = curl_exec($ch);
            if ($result === false) {
                curl_close($ch);
                throw new Exception('Error fetching data');
            }
        
            curl_close($ch);
            $response = json_decode($result, true);
            return $response;
        } catch (Exception $error) {
            echo 'Error fetching course data: ' . $error->getMessage();
            return null;
        }
    }

}

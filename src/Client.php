<?php

    declare(strict_types=1);

    namespace Stui\StatuspageIo;

    use JetBrains\PhpStorm\ArrayShape;
    use Stui\StatuspageIo\Exceptions\AuthenticationException;
    use Stui\StatuspageIo\Exceptions\ResponseException;

    class Client
    {
        public const BASE_URI = 'https://api.statuspage.io/v1';

        private string $apiKey;
        private string $pageId;

        public function __construct()
        {
        }

        /**
         * @throws \Stui\StatuspageIo\Exceptions\ResponseException
         * @throws \Stui\StatuspageIo\Exceptions\AuthenticationException
         */
        #[ArrayShape(['httpCode' => "int", 'response' => "array"])] public function send(string $url, array $data): array
        {
            $apiRequest = curl_init($url);
            curl_setopt($apiRequest, CURLOPT_HTTPHEADER, array(
                               "Authorization: OAuth " . $this->getApiKey(),
                               "Expect: 100-continue",
                               "Content-Type: application/json"
                           )
            );
            curl_setopt($apiRequest, CURLOPT_POST, true);
            curl_setopt($apiRequest, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($apiRequest, CURLOPT_POSTFIELDS, json_encode($data));
            $response = curl_exec($apiRequest);
            $responseCode = curl_getinfo($apiRequest, CURLINFO_HTTP_CODE);

            if(str_replace('"metadata":[]', '"metadata":{}', json_encode(json_decode($response, true), JSON_UNESCAPED_SLASHES)) !== $response){
                throw new ResponseException('API returned invalid JSON response', 9901);
            }

            if($responseCode === 401){
                throw new AuthenticationException('Could not authenticate. Please check your API key.', 9902);
            }

            if($responseCode === 400){
                throw new ResponseException('The sent request could not be understood by the API.', 9903);
            }

            return ['httpCode' => $responseCode, 'response' => json_decode($response, true)];
        }

        /**
         * @return string
         */
        private function getApiKey(): string
        {
            return $this->apiKey;
        }

        /**
         * @param string $apiKey
         */
        public function setApiKey(string $apiKey): void
        {
            $this->apiKey = $apiKey;
        }

        /**
         * @return string
         */
        public function getPageId(): string
        {
            return $this->pageId;
        }

        /**
         * @param string $pageId
         */
        public function setPageId(string $pageId): void
        {
            $this->pageId = $pageId;
        }


    }

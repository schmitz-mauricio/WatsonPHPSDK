<?php
/**
 * Copyright 2017 IBM Corp. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace WatsonSDK\Services;

use WatsonSDK\Common\HttpClient;
use WatsonSDK\Common\HttpResponse;
use WatsonSDK\Common\HttpClientConfiguration;
use WatsonSDK\Common\HttpClientException;
use WatsonSDK\Common\WatsonService;
use WatsonSDK\Common\WatsonCredential;
use WatsonSDK\Common\InvalidParameterException;
use WatsonSDK\Services\Conversation\DialogNodes\DialogNode;
use WatsonSDK\Services\Conversation\Intents\Example;
use WatsonSDK\Services\Conversation\Intents\Intent;

/**
 * ConversationDialogNodes class
 */
class ConversationIntentsExamples extends Conversation {

    /**
     * Get a example from a Conversation service by using the $dialog_node and workspace_id
     *
     * @param $intent Intent
     * @param $example string
     * @param $workspace_id string
     * @param $version string
     * @return HttpResponse
     */
    public function getExample($intent, $example, $workspace_id, $version = self::VERSION) {

        $config = $this->initConfig();

        $config->setQuery( [ 'version' => $version ] );
        $config->setMethod(HttpClientConfiguration::METHOD_GET);
        $config->setType(HttpClientConfiguration::DATA_TYPE_JSON);
        $example = str_replace('+', '%20', $example);
        $url = self::BASE_URL."/workspaces/{$workspace_id}/intents/{$intent->getIntent()}/examples/{$example}";

        $config->setURL($url);

        $response = $this->sendRequest($config);

        return $response;
    }

    /**
     * Get List of examples form a Conversation service by using the workspace_id
     *
     * @param $intent Intent
     * @param $workspace_id string
     * @param $page_limit integer
     * @param $version string
     * @return HttpResponse
     */
    public function getAllExamples($intent, $workspace_id, $page_limit=100, $version = self::VERSION) {

        $config = $this->initConfig();

        $config->setQuery( [ 'version' => $version, 'page_limit' => $page_limit ] );
        $config->setMethod(HttpClientConfiguration::METHOD_GET);
        $config->setType(HttpClientConfiguration::DATA_TYPE_JSON);

        $url = self::BASE_URL."/workspaces/{$workspace_id}/intents/{$intent->getIntent()}/examples";

        $config->setURL($url);

        $response = $this->sendRequest($config);

        return $response;
    }

    /**
     * Create a example from a Example Model
     *
     * @param Example $model
     * @param $workspace_id
     * @param string $version
     */
    public function createExample(Example $model, $workspace_id, $version = self::VERSION)
    {
        $config = $this->initConfig();
        $config->addHeaders($model->getData('@header'));

        $config->setData($model->getData('@data', true, true));

        $config->setQuery( [ 'version' => $version ] );
        $config->setMethod(HttpClientConfiguration::METHOD_POST);
        $config->setType(HttpClientConfiguration::DATA_TYPE_JSON);

        $url = self::BASE_URL."/workspaces/{$workspace_id}/intents/{$model->getIntent()->getIntent()}/examples";
        $config->setURL($url);
        $response = $this->sendRequest($config);

        return $response;
    }

    /**
     * Edit a Example from a Example Model
     *
     * @param Example $model
     * @param $workspace_id
     * @param string $example
     * @param string $version
     */
    public function editExample(Example $model, $workspace_id, $example=null, $version = self::VERSION)
    {
        $config = $this->initConfig();
        $config->addHeaders($model->getData('@header'));

        $config->setData($model->getData('@data', true, true));

        $config->setQuery( [ 'version' => $version ] );
        $config->setMethod(HttpClientConfiguration::METHOD_POST);
        $config->setType(HttpClientConfiguration::DATA_TYPE_JSON);

        $example = (!is_null($example) ? $example : $model->getText());
        $example = str_replace('+', '%20', $example);
        $url = self::BASE_URL."/workspaces/{$workspace_id}/intents/{$model->getIntent()->getIntent()}/examples/{$example}";
        $config->setURL($url);
        $response = $this->sendRequest($config);

        return $response;
    }

    /**
     * Delete a example from a Example Model
     *
     * @param Example $model
     * @param $workspace_id
     * @param string $version
     */
    public function deleteExample(Example $model, $workspace_id, $version = self::VERSION)
    {
        $config = $this->initConfig();
        $config->addHeaders($model->getData('@header'));

        $config->setQuery( [ 'version' => $version ] );
        $config->setMethod(HttpClientConfiguration::METHOD_DELETE);
        $config->setType(HttpClientConfiguration::DATA_TYPE_JSON);
        $model->setText(str_replace('+', '%20', urlencode($model->getText())));
        $url = self::BASE_URL."/workspaces/{$workspace_id}/intents/{$model->getIntent()->getIntent()}/examples/{$model->getText()}";
        $config->setURL($url);
        $response = $this->sendRequest($config);

        return $response;
    }




}
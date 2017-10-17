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

/**
 * ConversationDialogNodes class
 */
class ConversationDialogNodes extends Conversation {

    /**
     * Get List of dialog nodes form a Conversation service by using the workspace_id
     *
     * @param $workspace_id string
     * @param $page_limit integer
     * @param $version string
     * @return HttpResponse
     */
    public function getAllDialogNodes($workspace_id, $page_limit=100, $version = self::VERSION) {

        $config = $this->initConfig();

        $config->setQuery( [ 'version' => $version, 'page_limit' => $page_limit ] );
        $config->setMethod(HttpClientConfiguration::METHOD_GET);
        $config->setType(HttpClientConfiguration::DATA_TYPE_JSON);

        $url = self::BASE_URL."/workspaces/{$workspace_id}/dialog_nodes";

        $config->setURL($url);

        $response = $this->sendRequest($config);

        return $response;
    }

    /**
     * Get a dialog nodes from a Conversation service by using the $dialog_node and workspace_id
     *
     * @param $dialog_node
     * @param $workspace_id string
     * @param $version string
     * @return HttpResponse
     */
    public function getDialogNode($dialog_node, $workspace_id, $version = self::VERSION) {

        $config = $this->initConfig();

        $config->setQuery( [ 'version' => $version ] );
        $config->setMethod(HttpClientConfiguration::METHOD_GET);
        $config->setType(HttpClientConfiguration::DATA_TYPE_JSON);

        $url = self::BASE_URL."/workspaces/{$workspace_id}/dialog_nodes/{$dialog_node}";

        $config->setURL($url);

        $response = $this->sendRequest($config);

        return $response;
    }

    /**
     * Create a dialogNode from a DialogNode Model
     *
     * @param DialogNode $model
     * @param $workspace_id
     * @param string $version
     */
    public function createDialogNode(DialogNode $model, $workspace_id, $version = self::VERSION)
    {
        $config = $this->initConfig();
        $config->addHeaders($model->getData('@header'));

        $config->setData($model->getData('@data', true, true));

        $config->setQuery( [ 'version' => $version ] );
        $config->setMethod(HttpClientConfiguration::METHOD_POST);
        $config->setType(HttpClientConfiguration::DATA_TYPE_JSON);

        $url = self::BASE_URL."/workspaces/{$workspace_id}/dialog_nodes";
        $config->setURL($url);
        $response = $this->sendRequest($config);

        return $response;
    }

    /**
     * Edit a dialogNode from a DialogNode Model
     *
     * @param DialogNode $model
     * @param $workspace_id
     * @param string $dialog_node
     * @param string $version
     */
    public function editDialogNode(DialogNode $model, $workspace_id, $dialog_node=null, $version = self::VERSION)
    {
        $config = $this->initConfig();
        $config->addHeaders($model->getData('@header'));

        $config->setData($model->getData('@data', true, true));

        $config->setQuery( [ 'version' => $version ] );
        $config->setMethod(HttpClientConfiguration::METHOD_POST);
        $config->setType(HttpClientConfiguration::DATA_TYPE_JSON);

        $dialog_node = (!is_null($dialog_node) ? $dialog_node : $model->getDialogNode());

        $url = self::BASE_URL."/workspaces/{$workspace_id}/dialog_nodes/{$dialog_node}";
        $config->setURL($url);
        $response = $this->sendRequest($config);

        return $response;
    }

    /**
     * Delete a dialogNode from a DialogNode Model
     *
     * @param DialogNode $model
     * @param $workspace_id
     * @param string $version
     */
    public function deleteDialogNode(DialogNode $model, $workspace_id, $version = self::VERSION)
    {
        $config = $this->initConfig();
        $config->addHeaders($model->getData('@header'));

        $config->setQuery( [ 'version' => $version ] );
        $config->setMethod(HttpClientConfiguration::METHOD_DELETE);
        $config->setType(HttpClientConfiguration::DATA_TYPE_JSON);

        $url = self::BASE_URL."/workspaces/{$workspace_id}/dialog_nodes/{$model->getDialogNode()}/";
        $config->setURL($url);
        $response = $this->sendRequest($config);

        return $response;
    }




}
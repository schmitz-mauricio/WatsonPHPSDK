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

namespace WatsonSDK\Services\Conversation\DialogNodes;

use WatsonSDK\Common\ServiceModel;

/**
 * NextStep model
 */
class NextStep extends ServiceModel {

    /**
     * @data(behavior)
     * 
     * The behavior of a next step.
     * How the next_step node will be processed. Currently, the only valid value is jump_to.
     * @var string
     */
    protected $behavior;

    /**
     * @data(selector)
     *
     * Which part of the dialog node to process next (condition, client, user_input, or body.
     *
     * @var string
     */
    protected $selector;

    /**
     * @data(dialog_node)
     *
     * The dialog_node of a dialog nodes (dialog_node).
     *
     * @var string
     */
    protected $dialog_node;

    /**
     * @return mixed
     */
    public function getBehavior()
    {
        return $this->behavior;
    }

    /**
     * @param mixed $behavior
     */
    public function setBehavior($behavior)
    {
        $this->behavior = $behavior;
    }

    /**
     * @return mixed
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * @param mixed $selector
     */
    public function setSelector($selector)
    {
        $this->selector = $selector;
    }

    /**
     * @return mixed
     */
    public function getDialogNode()
    {
        return $this->dialog_node;
    }

    /**
     * @param mixed $dialog_node
     */
    public function setDialogNode($dialog_node)
    {
        $this->dialog_node = $dialog_node;
    }



}
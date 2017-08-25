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
 * Output model
 */
class OutputText extends ServiceModel {

    /**
     * @data(values)
     * 
     * The values of a output text.
     * @var array
     */
    protected $values;

    /**
     * @data(selection_policy)
     *
     * The selection_policy of a output text.
     * @var string
     */
    protected $selection_policy;

    /**
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param mixed $values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }

    /**
     * @return mixed
     */
    public function getSelectionPolicy()
    {
        return $this->selection_policy;
    }

    /**
     * @param mixed $selection_policy
     */
    public function setSelectionPolicy($selection_policy)
    {
        $this->selection_policy = $selection_policy;
    }



}
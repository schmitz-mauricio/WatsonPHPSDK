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

namespace WatsonSDK\Services\Conversation\Intents;

use WatsonSDK\Common\ServiceModel;

/**
 * Output model
 */
class Intent extends ServiceModel {

    /**
     * @data(intent)
     * 
     * The intent of a intent.
     * @var string
     */
    protected $intent;

    /**
     * @name(created)
     *
     * The creation date of a intent.
     *
     * @var string
     */
    protected $created;

    /**
     * @name(updated)
     *
     * The update date of a intent.
     *
     * @var string
     */
    protected $updated;

    /**
     * @data(description)
     *
     * The description of a intent.
     *
     * @var string
     */
    protected $description;

    /**
     * @name(examples)
     *
     * The examples of a intent.
     *
     * @var array
     */
    protected $examples;

    /**
     * @return string
     */
    public function getIntent()
    {
        return $this->intent;
    }

    /**
     * @param string $intent
     */
    public function setIntent($intent)
    {
        $this->intent = $intent;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function getExamples()
    {
        return $this->examples;
    }

    /**
     * @param array $examples
     */
    public function setExamples(array $examples)
    {
        $this->examples = $examples;
    }



}
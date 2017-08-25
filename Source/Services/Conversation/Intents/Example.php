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
class Example extends ServiceModel {

    /**
     * @data(text)
     * 
     * The text of a intent example.
     * @var array
     */
    protected $text;

    /**
     * @name(created)
     *
     * The creation date of a intent example.
     *
     * @var string
     */
    protected $created;

    /**
     * @name(updated)
     *
     * The update date of a intent example.
     *
     * @var string
     */
    protected $updated;

    /**
     * @name(intent)
     *
     * The intent of a intent example.
     *
     * @var Intent
     */
    protected $intent;


    /**
     * @return array
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
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
     * @return Intent
     */
    public function getIntent()
    {
        return $this->intent;
    }

    /**
     * @param Intent $intent
     */
    public function setIntent($intent)
    {
        $this->intent = $intent;
    }


}
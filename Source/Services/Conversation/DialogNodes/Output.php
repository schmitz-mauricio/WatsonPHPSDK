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
class Output extends ServiceModel {

    /**
     * @data(text)
     * 
     * The text of a output.
     * @var mixed
     */
    protected $text;

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        if(is_array($text)){
            $oOutputText = new Intent();
            $oOutputText->setOptions($text);
            $this->text = $oOutputText;
        }else{
            $this->text = $text;
        }
    }



}
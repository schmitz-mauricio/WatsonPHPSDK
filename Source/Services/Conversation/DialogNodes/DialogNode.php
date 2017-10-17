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
use WatsonSDK\Services\Conversation\Intents\Intent;

/**
 * DialogNode model
 */
class DialogNode extends ServiceModel {

    /**
     * @data(title)
     * 
     * The title of a dialog node.
     * 
     * @var string
     */
    protected $title;

    /**
     * @data(output)
     *
     * The output of a dialog node.
     *
     * @var array
     */
    protected $output;

    /**
     * @data(parent)
     *
     * The parent of a dialog node (dialog_node).
     *
     * @var string
     */
    protected $parent;

    /**
     * @data(context)
     *
     * The context of a dialog node.
     *
     * @var array
     */
    protected $context;

    /**
     * @name(created)
     *
     * The creation date of a dialog node.
     *
     * @var string
     */
    protected $created;

    /**
     * @name(updated)
     *
     * The update date of a dialog node.
     *
     * @var string
     */
    protected $updated;

    /**
     * @data(metadata)
     *
     * The metadata of a dialog node.
     *
     * @var string
     */
    protected $metadata;

    /**
     * @data(next_step)
     *
     * The next_step of a dialog node.
     *
     * @var NextStep
     */
    protected $next_step;

    /**
     * @data(conditions)
     *
     * The conditions of a dialog node.
     *
     * @var string
     */
    protected $conditions;

    /**
     * @data(description)
     *
     * The description of a dialog node.
     *
     * @var string
     */
    protected $description;

    /**
     * @data(dialog_node)
     *
     * The name of a dialog node.
     *
     * @var string
     */
    protected $dialog_node;

    /**
     * @data(previous_sibling)
     *
     * The revious_sibling of a dialog node.
     *
     * @var string
     */
    protected $previous_sibling;

    /**
     * @var array
     */
    protected $childs;

    /**
     * @var Intent
     */
    protected $intent;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param mixed $output
     */
    public function setOutput($output)
    {
        if(is_array($output)){
            $oOutput = new Output();
            $oOutput->setOptions($output);
            $this->output = $oOutput;

        }else{
            $this->output = $output;
        }
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param mixed $context
     */
    public function setContext($context)
    {
        $this->context = $context;
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
     * @return mixed
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param mixed $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return mixed
     */
    public function getNextStep()
    {
        return $this->next_step;
    }

    /**
     * @param array $next_step
     */
    public function setNextStep($next_step)
    {
        if(is_array($next_step)){
            $oNextStep = new NextStep();
            $oNextStep->setOptions($next_step);
            $this->next_step = $oNextStep;

        }else{
            $this->next_step = $next_step;
        }
    }

    /**
     * @return NextStep
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param mixed $conditions
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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

    /**
     * @return mixed
     */
    public function getPreviousSibling()
    {
        return $this->previous_sibling;
    }

    /**
     * @param mixed $previous_sibling
     */
    public function setPreviousSibling($previous_sibling)
    {
        $this->previous_sibling = $previous_sibling;
    }

    /**
     * @return array
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * @param array $childs
     */
    public function setChilds($childs)
    {
        $this->childs = $childs;
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


    /**
     * Seta o usuário que alterou o nó
     * @param $user
     *
     */
    public function setUpdatedUser($user)
    {
        $metadata = $this->getMetadata();
        if(is_null($metadata)){
            $metadata = array();
        }

        if(!isset($metadata['update_log']))
            $metadata['update_log'] = array();

        $hoje = new \DateTime();
        $update = array(
            'user' => $user,
            'date' => $hoje->format('Y-m-d H:i:s')
        );

        array_push($metadata['update_log'], $update);

        $this->setMetadata($metadata);
    }
}
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

namespace WatsonSDK\Panel;

use WatsonSDK\Common\WatsonCredential;
use WatsonSDK\Services\Conversation\DialogNodes\DialogNode;
use WatsonSDK\Services\Conversation\DialogNodes\Output;
use WatsonSDK\Services\Conversation\DialogNodes\OutputText;
use WatsonSDK\Services\Conversation\Intents\Example;
use WatsonSDK\Services\Conversation\Intents\Intent;
use WatsonSDK\Services\ConversationDialogNodes;
use WatsonSDK\Services\ConversationIntents;
use WatsonSDK\Services\ConversationIntentsExamples;

/**
 * Panel class
 */
class Panel {

    public $workspace_id;
    public $username;
    public $password;

    public $dialogNodesService;
    public $intentsService;
    public $examplesService;

    public $aIntents;

    public $nodes = array();
    public $nodesPrinted = array();

    public $framework = 'zend';
    /**
     * Panel constructor.
     * @param $username
     * @param $password
     * @param $workspace_id
     */
    public function __construct($username, $password, $workspace_id)
    {
        $this->workspace_id = $workspace_id;
        $this->username = $username;
        $this->password = $password;

        $this->dialogNodesService = new ConversationDialogNodes(WatsonCredential::initWithCredentials($this->username, $this->password));
        $this->intentsService = new ConversationIntents(WatsonCredential::initWithCredentials($this->username, $this->password));
        $this->examplesService = new ConversationIntentsExamples(WatsonCredential::initWithCredentials($this->username, $this->password));
    }

    /**
     * Method to get a list of nodes for panel tree
     * @param int $page_limit
     * @param string $initial_previous_sibling
     * @return array
     */
    public function dialogNodesListAll($page_limit = 1000000000, $initial_previous_sibling = '_fixed_node')
    {
//        $cache = \Zend_Registry::get('Cache');
//        $cache->remove('WatsonDialogNodes');
        $cacheData = null;
        if($this->framework == 'zend'){
            $cache = \Zend_Registry::get('Cache');
            $cacheData = $cache->load('WatsonDialogNodes');

        }else if($this->framework == 'laravel'){
            $cacheData = \Illuminate\Support\Facades\Cache::store('file')->get('WatsonDialogNodes');
        }

        if(!is_null($cacheData) && $cacheData)
            return $cacheData;

        //Busca a lista
        $aDialogNodes = $this->dialogNodesService->getAllDialogNodes($this->workspace_id, $page_limit)->getContent(true);
        $aDialogNodesListAll = array();
        $aDialogNodesList = array();
        if(!is_null($aDialogNodes)){

            foreach ($aDialogNodes['dialog_nodes'] as $aDialogNode){
                if(is_array($aDialogNode) && !is_null($aDialogNode['previous_sibling']) && $aDialogNode['dialog_node'][0] != '_') {
                    $oDialogNode = new DialogNode();
                    $oDialogNode->setOptions($aDialogNode);
                    $oDialogNode->setIntent($this->findIntent($oDialogNode->getConditions(), false));
                    $aDialogNodesList[] = $oDialogNode;
                }

                $oDialogNode = new DialogNode();
                $oDialogNode->setOptions($aDialogNode);
                $oDialogNode->setIntent($this->findIntent($oDialogNode->getConditions(), false));
                $aDialogNodesListAll[] = $oDialogNode;
            }



        }
        $lista = array('all' => $aDialogNodesListAll, 'noparent' => $this->reorderNodes($aDialogNodesList));

        if($this->framework == 'zend'){
            $cache->save($lista, 'WatsonDialogNodes');

        }else if($this->framework == 'laravel'){
            \Illuminate\Support\Facades\Cache::store('file')->put('WatsonDialogNodes', $lista, (60*24)*1); //1 Dias
        }

        $this->setNodes($lista);

        return $lista;

    }

    public function treeView($url)
    {
        $nodes = $this->dialogNodesListAll();
        return '<div id="treeview">' . $this->doOutputTree($nodes['noparent'], $nodes['all'], $url) . '</div>';
    }

    public function doOutputTree($nodes, $list, $url, $html = '')
    {
        $html .= '<ul>';
        foreach($nodes as $dialogNode)
        {
            if(!in_array($dialogNode->getDialogNode(), $this->nodesPrinted)){
                array_push($this->nodesPrinted, $dialogNode->getDialogNode());
                $html .= '<li class="list-group-item" data-jstree=\'{ "href": "'.$url . $dialogNode->getDialogNode() .'" }\'>
                            
                            <div class="list-group list-group-sm list-group-gap">
                                
                                <span href class="list-group-item md-whiteframe-z0">
                                    <h4> '. ($dialogNode->getDescription() != '' ? $dialogNode->getDescription() : ($dialogNode->getTitle() != '' ? $dialogNode->getTitle() : $dialogNode->getDialogNode())) .'</h4>
                                </span>
                            </div>';

                $childs = $this->getChilds($list, $dialogNode,0);
                if(count($childs))
                {
                    $html = $this->doOutputTree($childs, $list, $url, $html);
                }

                $html .= '</li>';
            }
        }
        $html .= '</ul>';

        return $html;
    }


    /**
     * Method create a node from params
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $erro = false;
        $aux_name = '';
        if(isset($data['parent']))
            $aux_name = $data['parent'].'_';

        //Create a intent
        $oIntent = new Intent();
        $oIntent->setIntent($aux_name . $this->createName($data['intent_description']));
        $oIntent->setDescription($data['intent_description']);
        $insertIntent = $this->intentsService->createIntent($oIntent, $this->workspace_id)->getContent(true);

        if(!is_null($insertIntent)) {
            foreach ($data['examples'] as $example) {
                if ($example != '') {
                    $oExample = new Example();
                    $oExample->setText($example);
                    $oExample->setIntent($oIntent);

                    $insertExample = $this->examplesService->createExample($oExample, $this->workspace_id)->getContent(true);

                }

            }


            //Criar um nó com uma condição que é a intenção
            $oDialogNode = new DialogNode();
            $oOutput = new Output();
            $oOutputText = new OutputText();

            $answers = array();
            foreach ($data['answer'] as $answer)
            {
                if($answer != '')
                    array_push($answers, $answer);
            }
            if($data['question'] != ''){
                array_push($answers, $data['question']);
            }
            $oOutputText->setValues($answers);
            $oOutputText->setSelectionPolicy($data['selection_policy']);

            $oOutput->setText($oOutputText);

            if(isset($data['parent']))
                $oDialogNode->setParent($data['parent']);

            $oDialogNode->setPreviousSibling('_fixed_node');

            if(isset($data['previous_sibling']))
                $oDialogNode->setPreviousSibling($data['previous_sibling']);

            $oDialogNode->setTitle($oIntent->getIntent());
            $oDialogNode->setOutput($oOutput);
            $oDialogNode->setConditions('#' . $oIntent->getIntent());
            $oDialogNode->setDescription($oIntent->getDescription());
            $oDialogNode->setDialogNode($oIntent->getIntent());
            $oDialogNode->setUpdatedUser($data['user']);
            $insertDialogNode = $this->dialogNodesService->createDialogNode($oDialogNode, $this->workspace_id)->getContent(true);

            if(!is_null($insertDialogNode)){
                //Se vier uma pergunta cria um subnó
                if($data['question'] != '')
                {
                    $oIntentChild = new Intent();
                    $oIntentChild->setIntent($this->createName($data['intent_description']) . '_' . $this->createName($data['question_title']));
                    $oIntentChild->setDescription($data['question_title']);
                    $insertIntentChild = $this->intentsService->createIntent($oIntentChild, $this->workspace_id)->getContent(true);
                    if(!is_null($insertIntentChild))
                    {
                        $oOutputChild = new Output();
                        $oOutputTextChild = new OutputText();
                        $oOutputChild->setText($oOutputTextChild);

                        $oDialogNodeChild = new DialogNode();
                        $oDialogNodeChild->setTitle($this->createName($data['question_title']));
                        $oDialogNodeChild->setDescription($data['question_title']);
                        $oDialogNodeChild->setConditions('#' . $oIntentChild->getIntent());
                        $oDialogNodeChild->setOutput($oOutputChild);
                        $oDialogNodeChild->setDialogNode($oIntentChild->getIntent());
                        $oDialogNodeChild->setParent($oDialogNode->getDialogNode());
                        $oDialogNodeChild->setUpdatedUser($data['user']);
                        $insertDialogNodeChild = $this->dialogNodesService->createDialogNode($oDialogNodeChild, $this->workspace_id)->getContent(true);

                        if(!is_null($insertDialogNodeChild))
                        {
                            $erro = false;
                        }else{
                            $erro = true;
                        }


                    }else{
                        $erro = true;
                    }

                }

                $erro = false;
            }else{
                $erro = true;
            }
        }else{
            $erro = true;
        }

        if($erro){
            $this->intentsService->deleteIntent($oIntent, $this->workspace_id);
            if(isset($oDialogNode))
                $this->dialogNodesService->deleteDialogNode($oDialogNode, $this->workspace_id);
            if(isset($oIntentChild))
                $this->intentsService->deleteIntent($oIntentChild, $this->workspace_id);
            if(isset($oDialogNodeChild))
                $this->dialogNodesService->deleteDialogNode($oDialogNodeChild, $this->workspace_id);
        }

        if(!$erro){
            if($this->framework == 'zend'){
                $cache = \Zend_Registry::get('Cache');
                $cache->remove('WatsonDialogNodes');

            }elseif($this->framework){
                \Illuminate\Support\Facades\Cache::forget('WatsonDialogNodes');
            }

        }
        if(isset($data['parent']) && $data['parent'] != ''){
            return array('result' => !$erro, 'intent' => $data['parent']);
        }else{
            return array('result' => !$erro, 'intent' => $oIntent->getIntent());
        }
    }

    /**
     * MEthod to require a node for edit
     * @param $intent
     * @return array|bool
     */
    public function edit($intent)
    {
        $aIntent = $this->findIntent($intent, true);
        $oIntent = $aIntent['intent'];
        $examplesList = $aIntent['examples'];

        $aDialogNode = $this->dialogNodesService->getDialogNode($intent, $this->workspace_id)->getContent(true);
        if(is_null($aDialogNode)){
            return false;
        }
        $oDialogNode = new DialogNode();
        $oDialogNode->setOptions($aDialogNode);

        if($this->framework == 'zend'){
            $cache = \Zend_Registry::get('Cache');
            $cacheData = $cache->load('WatsonDialogNodes');
        }

        $aDialogNodesList = array();
        if($cacheData){
            $aDialogNodesList = $cacheData['all'];

        }else{
            $aDialogNodes = $this->dialogNodesService->getAllDialogNodes($this->workspace_id, 1000000000)->getContent(true);
            if(!is_null($aDialogNodes)){
                foreach ($aDialogNodes['dialog_nodes'] as $aDialogNode){
                    if(is_array($aDialogNode)){
                        $tempDialogNode = new DialogNode();
                        $tempDialogNode->setOptions($aDialogNode);
                        $tempDialogNode->setIntent($this->findIntent($tempDialogNode->getConditions(), false));
                        $aDialogNodesList[] = $tempDialogNode;
                    }
                }
            }
        }

        $childs = $this->getChilds($aDialogNodesList, $oDialogNode, 1);

        $childs = $this->reorderNodes($childs, '');
        $oDialogNode->setChilds($childs);

        return array('oIntent' => $oIntent, 'examplesList' => $examplesList, 'oDialogNode' => $oDialogNode);
    }

    /**
     * Method to update a DialogNode
     * @param array $data
     * @return bool
     */
    public function update($data = array())
    {
        $originalData = $this->edit($data['intent']);

        $oIntent = $originalData['oIntent'];
        $examplesList = $originalData['examplesList'];
        $oDialogNode = $originalData['oDialogNode'];
        //Edita a intenção
        if(!isset($data['parent']))
            $oIntent->setIntent($this->createName($data['intent_description']));
        else{
            $aux_name = $data['parent'].'_';
            $oIntent->setIntent($aux_name . $this->createName($data['intent_description']));
        }
        $oIntent->setDescription($data['intent_description']);

        $edit = $this->intentsService->editIntent($oIntent, $this->workspace_id,  $data['intent'])->getContent(true);

        if($edit){
            foreach ($examplesList as $example){
                $this->examplesService->deleteExample($example, $this->workspace_id);
            }

            foreach ($data['examples'] as $example) {
                if ($example != '') {
                    $oExample = new Example();
                    $oExample->setText($example);
                    $oExample->setIntent($oIntent);

                    $insertExample = $this->examplesService->createExample($oExample, $this->workspace_id)->getContent(true);

                }

            }

            $oOutput = $oDialogNode->getOutput();
            if(is_object($oOutput))
                $oOutputText = $oOutput->getText();
            else{
                $oOutput = new Output();
                $oOutputText = new OutputText();
            }

            $answers = array();
            foreach ($data['answer'] as $answer)
            {
                if($answer != '')
                    array_push($answers, $answer);
            }
            if($data['question'] != '')
                $answers = array($data['question']);

            if(!is_object($oOutputText))
                $oOutputText = new OutputText();

            $oOutputText->setValues($answers);
            $oOutputText->setSelectionPolicy($data['selection_policy']);
            $oOutput->setText($oOutputText);
            $oDialogNode->setTitle($oIntent->getIntent());
            $oDialogNode->setOutput($oOutput);
            $oDialogNode->setConditions('#' . $oIntent->getIntent());
            $oDialogNode->setDialogNode($oIntent->getIntent());
            $oDialogNode->setUpdatedUser($data['user']);
            $oDialogNode->setDescription($data['intent_description']);

            $editDialogNode = $this->dialogNodesService->editDialogNode($oDialogNode, $this->workspace_id, $data['intent'])->getContent(true);
            if($editDialogNode){

                if($data['question'] != '')
                {
                    $oIntentChild = new Intent();
                    $oIntentChild->setIntent($this->createName($data['intent_description']) . '_' . $this->createName($data['question_title']));
                    $oIntentChild->setDescription($data['question_title']);

                    $insertIntentChild = $this->intentsService->createIntent($oIntentChild, $this->workspace_id)->getContent(true);
                    if(!is_null($insertIntentChild))
                    {
                        $oOutputChild = new Output();
                        $oOutputTextChild = new OutputText();
                        $oOutputChild->setText($oOutputTextChild);

                        $oDialogNodeChild = new DialogNode();
                        $oDialogNodeChild->setTitle($this->createName($data['question_title']));
                        $oDialogNodeChild->setDescription($data['question_title']);
                        $oDialogNodeChild->setConditions('#' . $oIntentChild->getIntent());
                        $oDialogNodeChild->setOutput($oOutputChild);
                        $oDialogNodeChild->setDialogNode($oIntentChild->getIntent());
                        $oDialogNodeChild->setParent($oDialogNode->getDialogNode());
                        $oDialogNodeChild->setUpdatedUser($data['user']);
                        $insertDialogNodeChild = $this->dialogNodesService->createDialogNode($oDialogNodeChild, $this->workspace_id)->getContent(true);


                        if(!is_null($insertDialogNodeChild))
                        {
                            $erro = false;
                        }else
                            $erro = true;


                    }else{
                        $erro = true;
                    }

                    if($erro)
                        $this->delete($oIntentChild->getIntent());

                }
                if($this->framework == 'zend'){
                    if(!$erro){
                        $cache = \Zend_Registry::get('Cache');
                        $cache->remove('WatsonDialogNodes');
                    }
                }
                return $oDialogNode->getDialogNode();
            }

        }
        return false;

    }

    /**
     * Method create a node from params
     * @param $data
     * @return array
     */
    public function createChild($data)
    {
        $erro = false;
        $aux_name = $data['parent'].'_';
        //Create a intent
        $oIntent = new Intent();
        $oIntent->setIntent($aux_name . time());
        $oIntent->setDescription('');

        $insertIntent = $this->intentsService->createIntent($oIntent, $this->workspace_id)->getContent(true);
        if(!is_null($insertIntent)) {
            //Criar um nó com uma condição que é a intenção
            $oDialogNode = new DialogNode();

            $answers = array('');

            $oDialogNode->setParent($data['parent']);

            if(isset($data['previous_sibling']))
                $oDialogNode->setPreviousSibling($data['previous_sibling']);

            $oDialogNode->setTitle('novo_no');
            $oDialogNode->setConditions('#' . $oIntent->getIntent());
            $oDialogNode->setDescription('Novo nó');
            $oDialogNode->setDialogNode($oIntent->getIntent());
            $oDialogNode->setUpdatedUser($data['user']);
            $insertDialogNode = $this->dialogNodesService->createDialogNode($oDialogNode, $this->workspace_id)->getContent(true);

            if(!is_null($insertDialogNode)){
                $erro = false;
            }else{
                $erro = true;
            }
        }else{
            $erro = true;
        }

        if($erro){
            $this->intentsService->deleteIntent($oIntent, $this->workspace_id);
            if(isset($oDialogNode))
                $this->dialogNodesService->deleteDialogNode($oDialogNode, $this->workspace_id);
        }

        if($this->framework == 'zend'){
            if(!$erro){
                $cache = \Zend_Registry::get('Cache');
                $cache->remove('WatsonDialogNodes');
            }
        }
        return array('result' => !$erro, 'intent' => $data['parent']);
    }

    public function delete($intent)
    {
        $aDialogNode = $this->dialogNodesService->getDialogNode($intent, $this->workspace_id)->getContent(true);
        if(is_null($aDialogNode)){
            return false;
        }
        $oDialogNode = new DialogNode();
        $oDialogNode->setOptions($aDialogNode);


        if($this->framework == 'zend'){
            $cache = \Zend_Registry::get('Cache');
            $cacheData = $cache->load('WatsonDialogNodes');
        }

        $aDialogNodesList = array();
        if($cacheData){
            $aDialogNodesList = $cacheData['all'];

        }else {
            $aDialogNodes = $this->dialogNodesService->getAllDialogNodes($this->workspace_id, 1000000000)->getContent(true);
            if (!is_null($aDialogNodes)) {
                foreach ($aDialogNodes['dialog_nodes'] as $aDialogNode) {
                    if (is_array($aDialogNode)) {
                        $tempDialogNode = new DialogNode();
                        $tempDialogNode->setOptions($aDialogNode);
                        $tempDialogNode->setIntent($this->findIntent($tempDialogNode->getConditions(), false));
                        $aDialogNodesList[] = $tempDialogNode;
                    }
                }
            }
        }

        $this->deleteChilds($aDialogNodesList, $oDialogNode);
        $this->deleteNode($oDialogNode);

    }

    public function deleteChilds($list, $parent)
    {
        $childs = array();

        foreach ($list as $current){
            if($current->getParent() == $parent->getDialogNode()){
                $current->setIntent($this->findIntent($current->getIntent()->getIntent(), true));
                //If $levels
                if(count($current->getChilds())){
                    foreach ($current->getChilds() as $child){
                        $this->deleteChilds($list, $child);
                    }
                }

                $this->deleteNode($current);
            }
        }

    }

    public function deleteNode($oDialogNode)
    {

        $oIntent = $this->findIntent($oDialogNode->getDialogNode());
        $this->dialogNodesService->deleteDialogNode($oDialogNode, $this->workspace_id);
        if($oIntent)
            $this->intentsService->deleteIntent($oIntent, $this->workspace_id);

        if($this->framework == 'zend'){
            $cache = \Zend_Registry::get('Cache');
            $cache->remove('WatsonDialogNodes');
        }
    }

    public function clearCache()
    {
        if($this->framework == 'zend'){
            $cache = \Zend_Registry::get('Cache');
            $cache->remove('WatsonDialogNodes');

        }else if($this->framework =='laravel'){
            \Illuminate\Support\Facades\Cache::forget('WatsonDialogNodes');
        }
    }

    /**
     * Method to add find childs of DialogNode
     * @param $list
     * @param $parent
     * @param int $levels
     * @return array
     */
    public function getChilds($list, $parent, $levels = 2)
    {
        $childs = array();

        $count = 0;
        foreach ($list as $current){
            if($current->getParent() == $parent->getDialogNode()){
                if(!is_array($current->getIntent()) && !is_null($current->getConditions())){
                    $this->aIntents = null;
                    $current->setIntent($this->findIntent(str_replace('#', '', $current->getConditions()), true));

                }
                //If $levels
//                if($count <= $levels)
                $current->setChilds($this->getChilds($list, $current, $levels));
                array_push($childs, $current);
                $count++;
            }
        }

        return $childs;
    }

    /**
     * Method to reorder DialogNodeList
     * @param $aDialogNodesList
     * @param string $previous_sibling
     * @param bool $withIntent
     * @param bool $withExamples
     * @return array
     */
    public function reorderNodes($aDialogNodesList, $previous_sibling = '_fixed_node', $withIntent = false, $withExamples = false)
    {
        $qtd = count($aDialogNodesList);

        $list = array();

        for($i=0; $i<=$qtd; $i++){
            foreach ($aDialogNodesList as $oDialogNode){
                if($oDialogNode->getPreviousSibling() == $previous_sibling){

                    if($withIntent){
                        array_push($list, array('dialogNode' => $oDialogNode, 'intent' => $this->findIntent($oDialogNode->getConditions(), $withExamples)));

                    }else{
                        array_push($list, $oDialogNode);

                    }
                    $previous_sibling = $oDialogNode->getDialogNode();
                }
            }
        }

        return $list;
    }

    public function findIntent($needle, $withExamples = false){

        foreach ($this->getArrayIntents()['intents'] as $aIntent){
            if($aIntent['intent'] == str_replace('#', '', $needle)){
                $oIntent = new Intent();
                $oIntent->setOptions($aIntent);

                if($withExamples){

                    //Examples
                    $examplesResult = $this->examplesService->getAllExamples($oIntent, $this->workspace_id,  100000000)->getContent(true);
                    $aExamples = array();
                    if(!is_null($examplesResult)){
                        foreach ($examplesResult['examples'] as $aExample){
                            if(is_array($aExample)){
                                $oExample = new Example();
                                $oExample->setOptions($aExample);
                                $oExample->setIntent($oIntent);
                                $aExamples[] = $oExample;
                            }
                        }
                    }
                    $oIntent->setExamples($aExamples);
                    return array('intent' => $oIntent, 'examples' => $aExamples);
                }else{
                    return $oIntent;
                }

            }
        }
    }

    /**
     * @return array
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @param array $nodes
     */
    public function setNodes($nodes)
    {
        $this->nodes = $nodes;
    }

    /**
     * @return array
     */
    public function getNodesPrinted()
    {
        return $this->nodesPrinted;
    }

    /**
     * @param array $nodesPrinted
     */
    public function setNodesPrinted($nodesPrinted)
    {
        $this->nodesPrinted = $nodesPrinted;
    }



    /**
     * Method return a array of intents
     * @return array
     */
    public function getArrayIntents()
    {
        if(is_null($this->aIntents))
        {
            $aIntents = $this->intentsService->getAllIntents($this->workspace_id, 1000000000)->getContent(true);
            $this->aIntents = $aIntents;
        }
        return $this->aIntents;
    }

    public function getElementZend($element, $data = array())
    {

        $view = new \Zend_View();
        $view->setBasePath(__DIR__ . '/views/zend/');
        $view->params = $data;

        $output = $view->render($element . '.phtml');
        return $output;

    }

    public function createName($string = "") {
        $name = strtolower(str_replace(' ', '_', preg_replace('/[`^~\'"]/', null, iconv('UTF-8', 'ASCII//TRANSLIT', $string))));
        $name = str_replace(['?', '!', '-',':', '.', ','], '_', $name);
        return $name;
    }

    public function clearAllNodes($url)
    {
        $nodes = $this->dialogNodesListAll();
        foreach ($nodes['noparent'] as $node) {
            $this->deleteNode($node);
        }
    }
}
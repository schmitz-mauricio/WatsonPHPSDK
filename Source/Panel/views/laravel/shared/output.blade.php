<div class="form-group {{ isset($outputAnswerDivHidden) && $outputAnswerDivHidden ? 'hidden' : ''  }} " id="outputAnswerDiv{{ isset($oDialogNode) ? $oDialogNode->getDialogNode() : '' }}">
    <?php
    if(isset($oDialogNode)){
        $oDialogNode = $oDialogNode;
        $selection_policy = '';
        if(is_object($oDialogNode->getOutput())){

            if(is_object($oDialogNode->getOutput()->getText())){
                $text = $oDialogNode->getOutput()->getText()->getValues();
                $selection_policy = $oDialogNode->getOutput()->getText()->getSelectionPolicy();
            }else{
                $text = array($oDialogNode->getOutput()->getText());
            }
        }else{
            $text = array($oDialogNode->getOutput());
        }
    }else{
        $selection_policy = 'sequential';

    }

    if(!isset($text) || is_null($text))
        $text = array('');
    ?>
    <div class="form-group "><label for="selection_policy" class="not-empty required"><i class="fa fa-question-circle" data-toggle="popover" data-placement="right" data-content="Se você adicionar várias resposta pode definir se quer que responda randomicamente ou sequencial" data-timeout="3000"></i> Modo de resposta</label>
        <select name="selection_policy" id="selection_policy" class="form-control " placeholder="" style="" required="required" title="" autofocus="autofucus">
            <option value="sequential" {{ $selection_policy == 'sequential' ? 'selected' : '' }}>Sequêncial</option>
            <option value="random" {{ $selection_policy == 'random' ? 'selected' : '' }}>Randômico</option>
        </select>
    </div>

    <label for="answer" class="optional"><i class="fa fa-question-circle" data-toggle="popover" data-placement="right" data-content="Informe o texto que deseja enviar ao usuário" data-timeout="3000"></i> Resposta</label>
    @foreach($text as $value)
    <div class="form-group toClone answerToCopy" id="answerToCopy{{ isset($oDialogNode) ? $oDialogNode->getDialogNode() : '' }}">
        <textarea name="answer[]" id="answer" class="form-control classInput summernote textAreaSemRedimensionamento" placeholder="" style="" rows="10" cols="80">{{ $value }}</textarea>
        <a href="#" class="trash-example summernoteTrash" data-toggle="tooltip" data-placement="top" data-content="Remover resposta"><span class="glyphicon glyphicon-trash"></span></a>
    </div>
    @endforeach
    <div id="answerCopyDiv{{ isset($oDialogNode) ? $oDialogNode->getDialogNode() : ''  }}"></div>

    <div class="form-group">
        <button type="button" class="btn btn-info copybutton" data-copy-target="#answerToCopy{{ isset($oDialogNode) ? $oDialogNode->getDialogNode() : '' }}" data-copy-to="#answerCopyDiv{{ isset($oDialogNode) ? $oDialogNode->getDialogNode() : '' }}" data-copy-ignore=".note-editor" data-copy-callback="Watson.summernote()"><i class="fa fa-plus"></i> Adicionar</button>
    </div>
</div>

<div class="form-group {{ isset($questionDivHidden) && $questionDivHidden ? 'hidden' : '' }}" id="questionDiv{{ isset($oDialogNode) ? $oDialogNode->getDialogNode() : '' }}">
    @if( isset($oDialogNode) && !is_null($oDialogNode->getOutput()) && count($oDialogNode->getChilds()))
    <div class="form-group">
        @foreach ($text as $value) {
        <div class="form-group toClone">
            <label for="question" class="not-empty required"><i class="fa fa-question-circle" data-toggle="popover" data-placement="right" data-content="Descreva a pergunta a ser feita ao usuário" data-timeout="3000"></i> Pergunta</label>
            <textarea name="question" id="pergunta" class="form-control summernote textAreaSemRedimensionamento" placeholder="" style="" required="required" title="">{{ $value }}</textarea>
        </div>
        @endforeach
    </div>
    @else
    <div class="form-group ">
        <label for="question_title" class="not-empty required"><i class="fa fa-question-circle" data-toggle="popover" data-placement="right" data-content="Informe um título breve do porque da pergunta" data-timeout="3000"></i> Título pergunta</label>
        <input name="question_title" id="question_title" value="" class="form-control" placeholder="" style="" required="required" title="" type="text">
    </div>
    <div class="form-group ">
        <label for="question" class="not-empty required"><i class="fa fa-question-circle" data-toggle="popover" data-placement="right" data-content="Descreva a pergunta a ser feita ao usuário" data-timeout="3000"></i> Pergunta</label>
        <textarea name="question" id="question" class="form-control summernote textAreaSemRedimensionamento" placeholder="" style="" required="required" title=""></textarea>
    </div>
    <small class="text-info"><i class="fa fa-exclamation"></i> Clique em salvar para adicionar as condiçoes de acordo com a resposta a essa pergunta</small>
    @endif
</div>
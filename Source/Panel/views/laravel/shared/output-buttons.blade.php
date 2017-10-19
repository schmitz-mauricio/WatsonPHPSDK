@if(isset($oDialogNode) && count($oDialogNode->getChilds()))

@else
<div class="form-group">
    <div class="btn-group">
        <button type="button" class="btn btn-info showHide enableDisableButton" data-enable-target="#submit{{ isset($oDialogNode) ? $oDialogNode->getDialogNode() : (isset($buttomName)  ? $buttomName : '') }}" data-show="#outputAnswerDiv{{ isset($oDialogNode) ? $oDialogNode->getDialogNode() : '' }}" data-hide="#questionDiv{{ isset($oDialogNode) ? $oDialogNode->getDialogNode() : '' }}" data-callback="WatsonPanel.summernote" data-toggle="tooltip" data-placement="top" data-original-title="Adicionar uma resposta para essa condição">Adicionar resposta</button>
        <button type="button" class="btn btn-success showHide enableDisableButton" data-enable-target="#submit{{ isset($oDialogNode) ? $oDialogNode->getDialogNode() : (isset($buttomName) ?  $buttomName : '') }}" data-show="#questionDiv{{ isset($oDialogNode) ? $oDialogNode->getDialogNode() : '' }}" data-hide="#outputAnswerDiv{{ isset($oDialogNode) ? $oDialogNode->getDialogNode() : '' }}" data-toggle="tooltip" data-placement="top" data-original-title="Adicionar uma pergunta ao usuário">Adicionar pergunta ao usuário</button>
    </div>
</div>
@endif
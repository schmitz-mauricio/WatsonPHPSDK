<div class="form-group ">
    <label for="intent" class="not-empty required"><i class="fa fa-question-circle" data-toggle="popover" data-placement="right" data-content="Informe um título breve do que será abordado nesse diálogo" data-timeout="3000"></i> Título da intenção</label>
    <input name="intent" id="intent" value="{{ isset($oIntent) ? $oIntent->getIntent() : '' }}" class="form-control" placeholder="" style="" required="required" title="Campo obrigatório" type="hidden">
    <input name="intent_description" id="intent_description" value="{{ isset($oIntent) ? $oIntent->getDescription() : '' }}" class="form-control" placeholder="" style="" required="required" title="Campo obrigatório" type="text">
</div>

<div class="form-group">
    <label for="examples" class="not-empty required"><i class="fa fa-question-circle" data-toggle="popover" data-placement="right" data-content="Informe possíveis perguntas que o usuário pode fazer" data-timeout="3000"></i> Quando o usuário diz: </label>
</div>

@if(isset($oIntent))

    @foreach($aExamples as $example)
        <div class="form-group examplesToCopy" id="examplesToCopy{{ isset($oIntent) ? $oIntent->getIntent() : '' }}">
            <input name="examples[]" id="examples" value="{{ $example->getText() }}"
                   class="form-control callClick" data-callclick-target="#copyExample{{ isset($oIntent) ? $oIntent->getIntent() : '' }}" placeholder="" style=""
                   required="required" title="" type="text">
            <a href="#" class="trash-example"><span class="glyphicon glyphicon-trash"></span></a>
        </div>
    @endforeach
@endif

<div class="form-group examplesToCopy toClone" id="examplesToCopy{{ isset($oIntent) ? $oIntent->getIntent() : '' }}">
    <input name="examples[]" id="examples" value="" class="form-control callClick" data-callclick-target="#copyExample{{ isset($oIntent) ? $oIntent->getIntent() : '' }}" placeholder="" style="" required="required" title="Campo obrigatório" type="text">
    <a href="#" class="trash-example"><span class="glyphicon glyphicon-trash"></span></a>
</div>
<div id="examplesCopyDiv{{ isset($oIntent) ? $oIntent->getIntent() : '' }}"></div>

<div class="form-group">
    <button type="button" id="copyExample{{ isset($oIntent) ? $oIntent->getIntent() : '' }}" class="btn btn-success copybutton" data-copy-target="#examplesToCopy{{ isset($oIntent) ? $oIntent->getIntent() : '' }}" data-copy-to="#examplesCopyDiv{{ isset($oIntent) ? $oIntent->getIntent() : '' }}"><i class="fa fa-plus"></i> Adicionar</button>
</div>
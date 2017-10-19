<div class="box-inner padding">
    <div class="col-md-12">
        <div class="form-group">
            <a href="{{ route( $prefix . 'index') }}" type="button" class="btn btn-info"><i class="fa fa-arrow-left"></i> Voltar para lista</a>
        </div>

        <form id="watsonpanel" name="watsonpanel" enctype="application/x-www-form-urlencoded" class="validate" action="{{ route( $prefix . 'store') }}" method="post" novalidate="novalidate">
            <div class="panel panel-body">
                @include('panelLaravel::laravel.shared.intent')

            </div>
            <div class="panel panel-body">
                @include('panelLaravel::laravel.shared.output-buttons')

                @include('panelLaravel::laravel.shared.output',['outputAnswerDivHidden' => true, 'questionDivHidden' => true])
            </div>
            <div class="panel panel-body">
                <button name="submit" id="submit" type="submit" class="btn btn-success" style="" md-ink-ripple="" disabled data-toggle="tooltip" data-placement="top" data-original-title="Adicione uma resposta ou pergunta primeiro">Salvar</button>
            </div>
        </form>
    </div>
</div>
<div class="box-inner padding">
    <div class="form-group">
        <a href="{{ route( $prefix . 'index') }}" type="button" class="btn btn-info"><i class="fa fa-arrow-left"></i> Voltar para lista</a>
    </div>
    <?php
    if($oDialogNode->getParent() != '') {
    ?>
    <div class="col-md-1 text-center">
        <a href="{{ route( $prefix . 'edit',['id' => $oDialogNode->getParent()]) }}">
            <div class="visible-md visible-lg">
                <i class="fa fa-arrow-left fa-3x" aria-hidden="true"></i>
            </div>

            <div class="visible-sm visible-xs">
                <i class="fa fa-arrow-up fa-3x" aria-hidden="true"></i>
            </div>
        </a>
    </div>
    <?php
    }
    ?>
    <div class="col-md-{{ ($oDialogNode->getParent() != '' ? '11' : '12') }}">
        <form id="watsonpanel" name="watsonpanel" enctype="application/x-www-form-urlencoded" class="validate" action="{{ route( $prefix . 'update', ['id' => $oDialogNode->getDialogNode()]) }}" method="post" novalidate="novalidate">
            <div class="panel panel-body">
                @include('panelLaravel::laravel.shared.intent', ['oIntent' => $oIntent, 'aExamples' => $examplesList])
            </div>
            <div class="panel panel-body">
                <a href="{{ route( $prefix . 'create', ['action' => 'createchild', 'parent' => $oDialogNode->getDialogNode()])  }}" class="btn btn-success waves-effect"> Adicionar pergunta</a>
                <br><br>
                @include('panelLaravel::laravel.shared.output',['questionDivHidden' => true, 'oDialogNode' => $oDialogNode])
            </div>
            <div class="panel panel-body">
                <button name="submit" id="submit" type="submit" class="btn btn-primary  waves-effect" style="" md-ink-ripple="">Salvar</button>
                <a href="#" data-href="{{ route( $prefix . 'destroy', ['id' => (!is_null($oIntent) ? $oIntent->getIntent() : $oDialogNode->getDialogNode())]) }}" class="btn btn-danger"
                   data-toggle="confirmation" data-placement="left">
                    <i class="fa fa-times fa-lg"></i>
                    <span class="hidden-xs" style="display inline-block; width: 55px;"> Excluir</span>
                </a>
            </div>
            <div class="panel panel-body">
                <?php
                $metadata = $oDialogNode->getMetadata();
                if(isset($metadata['update_log'])){
                    if(!empty($metadata['update_log'])){
                        $data = DateTime::createFromFormat('Y-m-d H:i:s', $metadata['update_log'][0]['date']);
                        echo 'Criação: ' . $metadata['update_log'][0]['user'] . ' em ' . $data->format('d/m/Y H:i:s') . '<br>';
                    }

                    if(count($metadata['update_log']) > 1){
                        $data = DateTime::createFromFormat('Y-m-d H:i:s', end($metadata['update_log'])['date']);
                        echo 'Última alteração: ' . end($metadata['update_log'])['user'] . ' em ' . $data->format('d/m/Y H:i:s');
                    }
                }
                ?>
            </div>
        </form>
    </div>
</div>
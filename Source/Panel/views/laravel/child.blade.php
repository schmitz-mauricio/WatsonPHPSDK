<div class="box-inner padding">
    <div class="form-group">
        <a href="{{ route( $prefix . 'index') }}" type="button" class="btn btn-info"><i class="fa fa-arrow-left"></i> Voltar para lista</a>
    </div>
    @if($oDialogNode->getParent() != '')
    <div class="col-md-1 text-center">
        <a href="{{ route( $prefix . 'edit', ['id' =>$oDialogNode->getParent()]) }}">
            <div class="visible-md visible-lg">
                <i class="fa fa-arrow-left fa-3x" aria-hidden="true"></i>
            </div>

            <div class="visible-sm visible-xs">
                <i class="fa fa-arrow-up fa-3x" aria-hidden="true"></i>
            </div>
        </a>
    </div>
    @endif

    <form id="watsonpanel" name="watsonpanel" enctype="application/x-www-form-urlencoded" class="validate" action="{{ route( $prefix . 'update', ['id' =>$oDialogNode->getDialogNode()]) }}" method="post" novalidate="novalidate">
        <input type="hidden" name="_method" value="PUT"/>
        {{ csrf_field() }}
        <div class="col-md-5 {{ $oDialogNode->getParent() == '' ? 'col-md-offset-1' : '' }}">
            <div class="panel panel-body">
                @include('panelLaravel::laravel.shared.intent', ['oIntent' => $oIntent, 'aExamples' => $examplesList])

                @include('panelLaravel::laravel.shared.output',['outputAnswerDivHidden' => true, 'oDialogNode' => $oDialogNode])

                <button name="submit" id="submit" type="submit" class="btn btn-primary  waves-effect" style="" md-ink-ripple="">Salvar</button>
                <a  class="btn btn-danger"  onclick="Scripts.confirm('{{ route( $prefix . 'destroy', ['id' => $oDialogNode->getDialogNode()]) }}', ['{{ __('que deseja excluir esse diálogo?') }}', '{{ __('Excluído') }}', '{{ __('Diálogo excluído com sucesso!') }}'], 'DELETE', 'location.href=\'{{ route( $prefix . 'index') }}\';')"><i class="fa fa-times"></i> {{ __('Excluir') }} </a>
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
        </div>
    </form>

    <div class="col-md-6">
        @foreach ($oDialogNode->getChilds() as $child)
        <div class="row">
            <div class="col-md-11 dd">
                <a href="#" class="showHide" id="btNode{{ $child->getDialogNode() }}" data-show="#{{ $child->getDialogNode() }}" data-hide="#btNode{{ $child->getDialogNode() }}" data-toggle="tooltip" data-placement="top" data-original-title="Adicionar uma resposta/pergunta para essa condição">
                    <ul class="dd-list">
                        <li class="dd-item">
                            <div class="dd-handle white-bg">
                                <span class="label label-success"></span>
                                <h3 class="text-md">{{ $child->getDescription() }}</h3>
                                <small class="font-thin">
                                    <?php
                                    $metadata = $child->getMetadata();
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
                                </small>
                            </div>
                        </li>
                    </ul>
                </a>
                <div class="panel panel-body hidden" id="{{ $child->getDialogNode() }}">
                    <form id="watsonpanel{{ $child->getDialogNode()}}" name="watsonpanel{{ $child->getDialogNode()}}" enctype="application/x-www-form-urlencoded" class="validate" action="{{ route( $prefix . 'update', ['id' => $child->getDialogNode(), 'parent' =>$oDialogNode->getDialogNode()]) }}" method="post" novalidate="novalidate">
                        <input type="hidden" name="_method" value="PUT"/>
                        {{ csrf_field() }}
                        @include('panelLaravel::laravel.shared.intent', ['oIntent' => $child->getIntent()['intent'], 'aExamples' => $child->getIntent()['examples']])

                        @if(is_null($child->getOutput()))
                            @include('panelLaravel::laravel.shared.output-buttons', ['oDialogNode' => $child])
                        @endif

                        @if(!count($child->getChilds()))
                        <a href="{{ route( $prefix . 'create', ['action' => 'createchild', 'parent' => $child->getDialogNode()])  }}" class="btn btn-success waves-effect"> Adicionar pergunta</a>
                        @endif
                        <br><br>

                        @include('panelLaravel::laravel.shared.output',['oDialogNode' => $child, 'questionDivHidden' => (!count($child->getChilds())), 'outputAnswerDivHidden' => (count($child->getChilds()))])

                        <button name="submit" id="submit{{ $child->getDialogNode() }}" type="submit" class="btn btn-primary  waves-effect" style="" md-ink-ripple="">Salvar</button>
                        <a href="#" class="showHide btn btn-default" data-hide="#{{ $child->getDialogNode() }}" data-show="#btNode{{ $child->getDialogNode() }}"  data-toggle="tooltip" data-placement="right" data-original-title="Informações não salvas serão perdidas!">Fechar</a>
                        <a  class="btn btn-danger"  onclick="Scripts.confirm('{{ route( $prefix . 'destroy', ['id' => $child->getDialogNode()]) }}', ['{{ __('que deseja excluir esse diálogo?') }}', '{{ __('Excluído') }}', '{{ __('Diálogo excluído com sucesso!') }}'], 'DELETE', 'location.href=\'{{ route( $prefix . 'edit', ['id' => $oDialogNode->getDialogNode()]) }}\';')"><i class="fa fa-times"></i> {{ __('Excluir') }} </a>
                    </form>
                </div>
            </div>
            @if(count($child->getChilds()))
            <div class="col-md-1 text-center">
                <a href="{{ route( $prefix . 'edit', ['id' => $child->getIntent()['intent']->getIntent()]) }}">
                    <div class="visible-md visible-lg">
                        <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                    </div>

                    <div class="visible-sm visible-xs">
                        <i class="fa fa-arrow-down fa-3x" aria-hidden="true"></i>
                    </div>
                </a>
            </div>
            @endif
        </div>

        @endforeach
        <div class="row">
            <div class="col-md-11">
                <form id="watsonpanelNovaCondicao" name="watsonpanelNovaCondicao" enctype="application/x-www-form-urlencoded" class="validate" action="{{ route( $prefix . 'store', ['parent' =>$oDialogNode->getDialogNode()]) }}" method="post" novalidate="novalidate">
                    {{ csrf_field() }}
                    <input type="hidden" name="previous_sibling" value="{{ $child->getDialogNode() }}">
                    <div class="panel panel-body hidden" id="novaCondicao">
                        <div class="panel panel-body">
                            @include('panelLaravel::laravel.shared.intent',['aExamples' => []])

                        </div>
                        <div class="panel panel-body">
                            @include('panelLaravel::laravel.shared.output-buttons', ['buttomName' => 'NovaCondicao', 'novo' => true])

                            @include('panelLaravel::laravel.shared.output',['outputAnswerDivHidden' => true, 'questionDivHidden' => true, 'novo' =>  true])
                        </div>
                        <button name="cancelar" id="cancelar" type="button" class="btn btn-default waves-effect showHide" data-show="#btNovaCondicao" data-hide="#novaCondicao" >Cancelar</button>
                        <button name="submit" id="submitNovaCondicao" type="submit" class="btn btn-primary  waves-effect" style="" md-ink-ripple="" disabled data-toggle="tooltip" data-placement="top" data-original-title="Adicione uma resposta ou pergunta primeiro">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="">
            <a href="#" class="showHide btn btn-info" id="btNovaCondicao" data-hide="#btNovaCondicao" data-show="#novaCondicao" data-toggle="tooltip" data-placement="top" data-original-title="Adicionar uma nova condição!"><i class="fa fa-plus"></i> Adicionar condição</a>
        </div>
    </div>
</div>
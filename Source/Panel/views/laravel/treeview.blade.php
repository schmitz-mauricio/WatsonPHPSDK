<div class="col-lg-12">
    <p class="m-b-lg">
        <a href="{{ route('admin.dialog.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ __('Adicionar diálogo') }} </a>
        <a href="{{ route('admin.dialog.index', ['action' => 'refresh']) }}" class="btn btn-info"><i class="fa fa-refresh"></i> {{ __('Atualizar') }}</a>
    </p>
    <div class="dd" id="treeview">

        <?php
            $nodes = $panel->dialogNodesListAll();
            echo doOutputTree($panel, $nodes['noparent'], $nodes['all'], '');

            function doOutputTree($panel, $nodes, $list, $html = '')
            {
                $html .= '<ul class="dd-list">';
                foreach($nodes as $dialogNode)
                {
                    $nodesPrinted = $panel->getNodesPrinted();
                    if(!in_array($dialogNode->getDialogNode(), $nodesPrinted)){
                        array_push($nodesPrinted, $dialogNode->getDialogNode());
                        $panel->setNodesPrinted($nodesPrinted);
                        $html .= '<li class="dd-item" data-jstree=\'{ "href": "'. route('admin.dialog.edit', ['id' => $dialogNode->getDialogNode()]) .'" }\'>
                                      <div class="dd-handle">
                                          <span class="label label-warning"></span> '. ($dialogNode->getDescription() != '' ? $dialogNode->getDescription() : ($dialogNode->getTitle() != '' ? $dialogNode->getTitle() : $dialogNode->getDialogNode())) .'
                                      </div>';

                        $childs = $panel->getChilds($list, $dialogNode,0);
                        if(count($childs))
                        {
                            $html = doOutputTree($panel, $childs, $list, $html);
                        }

                        $html .= '</li>';
                    }
                }
                $html .= '</ul>';

                return $html;
            }
        ?>
    </div>
</div>
@section('styles')
    <style>
        .jstree-default .jstree-node {
            line-height: 50px !important;
        }

        .jstree-default .jstree-clicked, .jstree-default  .jstree-hovered {
            background: none;
            border-radius: 0;
            box-shadow: none;
        }

        .list-group {
            min-width: 300px !important;
        }

        span.list-group-item:hover {
            color: inherit;
            background-color: rgba(110, 115, 120, 0.075);
        }

        @media (max-width: 768px) {
            .jstree-default .jstree-node {
                line-height: 80px !important;
            }
        }
    </style>
@stop
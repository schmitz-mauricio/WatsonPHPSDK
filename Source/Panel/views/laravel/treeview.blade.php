<div class="col-lg-12">
    <p class="m-b-lg">
        <a href="{{ route( $prefix . 'create') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ __('Adicionar diálogo') }} </a>
        <a href="{{ route( $prefix . 'index', ['action' => 'refresh']) }}" class="btn btn-info"><i class="fa fa-refresh"></i> {{ __('Atualizar') }}</a>
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
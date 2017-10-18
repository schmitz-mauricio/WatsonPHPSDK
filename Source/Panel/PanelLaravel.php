<?php

namespace WatsonSDK\Panel;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;

class PanelLaravel
{
    /**
     * Reports request object.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $viewFactory;

    /**
     * Datatables constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\View\Factory $viewFactory
     */
    public function __construct(Request $request, Factory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
        $this->request = $request;
        view()->addNamespace('panelLaravel', __DIR__ . '/views');
    }

    /**
     * Process dataTables needed render output.
     *
     * @param string $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function render($view, $data = [], $mergeData = [])
    {
        $params = $this->getRequest()->all();
        if(isset($params['action'])){
            $action = $params['action'];
            return call_user_func_array([$this, $action], ['view' => $view, 'panel' => $data['panel']]);
        }
        return $this->viewFactory->make($view, $data, $mergeData);
    }

    /**
     * Monta a treeview
     * @param String $view
     * @param \WatsonSDK\Panel\Panel $panel
     */
    public function treeview($view, $panel)
    {
        return $this->viewFactory->make($view, ['panel' => $panel]);
    }

    /**
     * Atualiza o cache
     * @param String $view
     * @param \WatsonSDK\Panel\Panel $panel
     */
    public function refresh($view, $panel)
    {
        $panel->clearCache();
        return $this->viewFactory->make($view, ['panel' => $panel]);
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Display printable view of datatables.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function printPreview($data, $view)
    {
        return $this->viewFactory->make($view, compact('data'));
    }
}

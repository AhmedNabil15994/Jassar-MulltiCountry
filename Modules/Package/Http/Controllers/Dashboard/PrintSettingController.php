<?php

namespace Modules\Package\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Traits\DataTable;
use Modules\Package\Repositories\Dashboard\PrintSettingRepository as Repo;
use Modules\Package\Http\Requests\Dashboard\PrintSettingRequest as ModelRequest;
use Modules\Package\Transformers\Dashboard\PrintSettingResource as ModelResource;

class PrintSettingController extends Controller
{
    protected $repo;

    function __construct(Repo $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return view('package::dashboard.print-settings.index');
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->repo->QueryTable($request));
        $datatable['data'] = ModelResource::collection($datatable['data']);
        return Response()->json($datatable);
    }

    public function create()
    {
        return view('package::dashboard.print-settings.create');
    }

    public function store(ModelRequest $request)
    {
        try {
            $create = $this->repo->create($request);

            if ($create) {
                return Response()->json([true, __('apps::dashboard.messages.created')]);
            }

            return Response()->json([false, __('apps::dashboard.messages.failed')]);
        } catch (\Exception $e) {
           
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function show($id)
    {
        return view('package::dashboard.print-settings.show');
    }

    public function edit($id)
    {
        $model = $this->repo->findById($id);
        return view('package::dashboard.print-settings.edit', compact('model'));
    }

  

    public function update(ModelRequest $request, $id)
    {
        try {
            $update = $this->repo->update($request, $id);

            if ($update) {
                return Response()->json([true, __('apps::dashboard.messages.updated')]);
            }

            return Response()->json([false, __('apps::dashboard.messages.failed')]);
        } catch (\Exception $e) {
           
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function destroy($id)
    {
        try {
            $delete = $this->repo->delete($id);

            if ($delete) {
                return Response()->json([true, __('apps::dashboard.messages.deleted')]);
            }

            return Response()->json([false, __('apps::dashboard.messages.failed')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deletes(Request $request)
    {
        try {
            $deleteSelected = $this->repo->deleteSelected($request);

            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.messages.deleted')]);
            }

            return Response()->json([false, __('apps::dashboard.messages.failed')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }
}

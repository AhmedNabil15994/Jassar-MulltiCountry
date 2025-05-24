<?php

namespace Modules\Catalog\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Catalog\Http\Requests\Dashboard\ImportProductRequest;
use Modules\Core\Traits\DataTable;
use Modules\Catalog\Http\Requests\Dashboard\ProductRequest;
use Modules\Catalog\Http\Requests\Dashboard\updatePhotoRequest;
use Modules\Catalog\Transformers\Dashboard\ProductResource;
use Modules\Catalog\Repositories\Dashboard\ProductRepository as Product;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Apps\Http\Requests\Dashboard\GetExcelHeaderRowRequest;
use Modules\Apps\Imports\GetFirstRowImport;
use Modules\Catalog\Imports\ProductImport;
use Modules\Catalog\Transformers\Dashboard\ExportProductResource;
use Modules\Core\Traits\Dashboard\DatatableExportTrait;

class ProductController extends Controller
{
    use DatatableExportTrait;

    protected $product;
    protected $defaultVendor;

    function __construct(Product $product)
    {
        $this->product = $product;
        $this->defaultVendor = app('vendorObject') ?? null;

        $this->setRepository(Product::class);
        $this->setExportResource(new ExportProductResource([]));
    }

    public function index()
    {
        return view('catalog::dashboard.products.index');
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->product->QueryTable($request));
        $datatable['data'] = ProductResource::collection($datatable['data']);
        return Response()->json($datatable);
    }

    public function reviewProducts()
    {
        if (!is_null($this->defaultVendor))
            return abort(404);

        return view('catalog::dashboard.products.review-products.index');
    }

    public function reviewProductsDatatable(Request $request)
    {
        if (!is_null($this->defaultVendor))
            return abort(404);

        $datatable = DataTable::drawTable($request, $this->product->reviewProductsQueryTable($request));
        $datatable['data'] = ProductResource::collection($datatable['data']);
        return Response()->json($datatable);
    }

    public function create()
    {
        return view('catalog::dashboard.products.create');
    }

    public function store(ProductRequest $request)
    {
        try {
            $create = $this->product->create($request);

            if ($create) {
                return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function show($countryPrefix,$id)
    {
        return abort(404);

        /*$product = $this->product->getProductDetailsById($id);
        if (!$product)
            return abort(404);

        return view('catalog::dashboard.products.show', compact('product'));*/
    }

    public function edit($countryPrefix,$id)
    {
        $product = $this->product->findById($id);
        if (!$product)
            return abort(404);

        $product->load(["variantValues", "variants.productValues.optionValue.option", "categories"]);
        $currentVaration = $product->variantValues->sortBy("option_value_id")->groupBy("product_variant_id")->pluck("*.option_value_id")->toArray();

        return view('catalog::dashboard.products.edit', compact('product', "currentVaration"));
    }

    public function clone($countryPrefix,$id)
    {
        $product = $this->product->findById($id);
        if (!$product)
            return abort(404);

        $product->load(["variantValues", "variants.productValues.optionValue.option", "categories"]);
        $currentVaration = $product->variantValues->sortBy("option_value_id")->groupBy("product_variant_id")->pluck("*.option_value_id")->toArray();

        return view('catalog::dashboard.products.clone', compact('product', 'currentVaration'));
    }

    public function update(ProductRequest $request, $countryPrefix,$id)
    {
        // try {
            $update = $this->product->update($request, $id);

            if ($update) {
                return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        // } catch (\Exception $e) {
        //     return Response()->json([false, $e->errorInfo[2]]);
        // }
    }

    public function updatePhoto(updatePhotoRequest $request)
    {
        try {
            $imagePath = $this->product->updatePhoto($request);

            if ($imagePath) {
                return Response()->json([true, __('apps::dashboard.general.message_create_success'), 'imagePath' => $imagePath]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            throw $e;
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function approveProduct(Request $request, $countryPrefix,$id)
    {
        try {
            $update = $this->product->approveProduct($request, $id);

            if ($update) {
                return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function destroy($countryPrefix,$id)
    {
        try {
            $delete = $this->product->delete($id);

            if ($delete) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deletes(Request $request)
    {
        try {
            if (empty($request['ids']))
                return Response()->json([false, __('apps::dashboard.general.select_at_least_one_item')]);

            $deleteSelected = $this->product->deleteSelected($request);
            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deleteProductImage(Request $request)
    {
        try {
            $delete = $this->product->deleteProductImg($request);

            if ($delete)
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            else
                return Response()->json([false, __('apps::dashboard.general.message_error')]);

        } catch (\Exception $e) {
            return Response()->json([false]);
        }
    }

    public function getExcelHeaderRow(GetExcelHeaderRowRequest $request)
    {
        $rows = Excel::toArray(new GetFirstRowImport, $request->file('excel_file'));
        return response()->json([true, 'ignore_success' => true, 'header_row' => $rows[0][0]]);
    }

    public function switcher($countryPrefix,$id, $action)
    {
        try {
            $switch = $this->product->switcher($id, $action);
            if ($switch) {
                return Response()->json();
            }
            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function Import(ImportProductRequest $request)
    {
        try {
            DB::beginTransaction();
            Excel::import(new ProductImport($request), $request->file('excel_file'));
            DB::commit();
            return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function getProductsAjax(Request $request){
        return Response()->json([
            'status' => true,
            'products' => ProductResource::collection($this->product->getByCountry($request->country_id)),
        ]);
    }
}

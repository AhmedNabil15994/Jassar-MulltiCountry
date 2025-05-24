<?php

namespace Modules\Slider\Repositories\Dashboard;

use Modules\Slider\Entities\Slider;
use Modules\Catalog\Repositories\Dashboard\ProductRepository as Product;
use Modules\Catalog\Repositories\Dashboard\CategoryRepository as Category;
use Modules\Vendor\Repositories\Dashboard\VendorRepository as Vendor;
use Illuminate\Support\Facades\DB;
use Modules\Core\Traits\CoreTrait;
use Illuminate\Support\Facades\File;

class SliderRepository
{
    use CoreTrait;

    protected $slider;
    protected $product;
    protected $category;
    protected $vendor;

    function __construct(Slider $slider, Product $product, Category $category, Vendor $vendor)
    {
        $this->slider = $slider;
        $this->product = $product;
        $this->category = $category;
        $this->vendor = $vendor;
    }

    public function getAll()
    {
        $Slider = $this->slider->get();
        return $Slider;
    }

    public function findById($id)
    {
        return $this->slider->withDeleted()->find($id);
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {
            $data = [
                'start_at' => $request->start_at,
                'end_at' => $request->end_at,
                // 'image' => path_without_domain($request->image),
                'status' => $request->status ? 1 : 0,
                "title" => $request->title,
                "short_description" => $request->short_description,

            ];

            if ($request->slider_type == 'external') {
                $data['link'] = $request->link;
            } elseif ($request->slider_type == 'product') {
                $product = $this->product->findById($request->product_id);
                $data['sliderable_id'] = $product ? $request->product_id : null;
                $data['sliderable_type'] = $product ? get_class($product) : null;
            } elseif ($request->slider_type == 'category') {
                $category = $this->category->findById($request->category_id);
                $data['sliderable_id'] = $category ? $request->category_id : null;
                $data['sliderable_type'] = $category ? get_class($category) : null;
            } elseif ($request->slider_type == 'vendor') {
                $vendor = $this->vendor->findById($request->vendor_id);
                $data['sliderable_id'] = $vendor ? $request->vendor_id : null;
                $data['sliderable_type'] = $vendor ? get_class($vendor) : null;
            } else {
                $data['sliderable_id'] = null;
                $data['sliderable_type'] = null;
            }

            $slider = $this->slider->create($data);

            if ($request->image)
                $slider->addMedia($request->file('image'))->toMediaCollection('images');
                
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();

        $slider = $this->findById($id);
        $restore = $request->restore ? $this->restoreSoftDelete($slider) : null;

        try {
            $data = [
                'start_at' => $request->start_at,
                'end_at' => $request->end_at,
                'status' => $request->status ? 1 : 0,
                "title" => $request->title,
                "short_description" => $request->short_description,
            ];

            if ($request->slider_type == 'external') {
                $data['link'] = $request->link;
                $data['sliderable_id'] = null;
                $data['sliderable_type'] = null;
            } elseif ($request->slider_type == 'product') {
                $product = $this->product->findById($request->product_id);
                $data['sliderable_id'] = $product ? $request->product_id : null;
                $data['sliderable_type'] = $product ? get_class($product) : null;
            } elseif ($request->slider_type == 'category') {
                $category = $this->category->findById($request->category_id);
                $data['sliderable_id'] = $category ? $request->category_id : null;
                $data['sliderable_type'] = $category ? get_class($category) : null;
            } elseif ($request->slider_type == 'vendor') {
                $vendor = $this->vendor->findById($request->vendor_id);
                $data['sliderable_id'] = $vendor ? $request->vendor_id : null;
                $data['sliderable_type'] = $vendor ? get_class($vendor) : null;
            } else {
                $data['sliderable_id'] = null;
                $data['sliderable_type'] = null;
            }

            $slider->update($data);

            if ($request->image) {

                $slider->clearMediaCollection('images');
                $slider->addMedia($request->file('image'))->toMediaCollection('images');
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restoreSoftDelete($model)
    {
        $model->restore();
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);
            if ($model) {

                if ($model->trashed()) :

                    $model->clearMediaCollection('images');
                    $model->forceDelete();
                else :
                    $model->delete();
                endif;
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteSelected($request)
    {
        DB::beginTransaction();

        try {

            foreach ($request['ids'] as $id) {
                $model = $this->delete($id);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }



    public function QueryTable($request)
    {
        $query = $this->slider;

        $query = $this->filterDataTable($query, $request);

        return $query;
    }

    public function filterDataTable($query, $request)
    {
        // SEARCHING INPUT DATATABLE
        if ($request->input('search.value') != null) {

            $query = $query->where(function ($query) use ($request) {
                $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            });
        }

        // FILTER
        if (isset($request['req']['from']) && $request['req']['from'] != '')
            $query->whereDate('created_at', '>=', $request['req']['from']);

        if (isset($request['req']['to']) && $request['req']['to'] != '')
            $query->whereDate('created_at', '<=', $request['req']['to']);

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'only')
            $query->onlyDeleted();

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'with')
            $query->withDeleted();

        if (isset($request['req']['status']) && $request['req']['status'] == '1')
            $query->active();

        if (isset($request['req']['status']) && $request['req']['status'] == '0')
            $query->unactive();

        return $query;
    }
}

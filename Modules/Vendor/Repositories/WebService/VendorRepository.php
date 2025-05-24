<?php

namespace Modules\Vendor\Repositories\WebService;

use Modules\Vendor\Entities\Vendor;
use Modules\Vendor\Entities\Section;
use Modules\Vendor\Entities\VendorDeliveryCharge;
use Modules\Vendor\Entities\VendorCategory;

class VendorRepository
{
    protected $section;
    protected $vendor;
    protected $deliveryCharge;
    protected $category;

    function __construct(Vendor $vendor, Section $section, VendorDeliveryCharge $deliveryCharge, VendorCategory $category)
    {
        $this->section = $section;
        $this->vendor = $vendor;
        $this->deliveryCharge = $deliveryCharge;
        $this->category = $category;
    }

    public function getAllSections()
    {
        $sections = $this->section->with([
            'vendors' => function ($query) {
                $query->active()->with([
                    'deliveryCharge' => function ($query) {
                        $query->where('state_id', '');
                    }
                ]);

                $query->when(setting('other.enable_subscriptions') == 1, function ($q) {
                    return $q->whereHas('subbscription', function ($query) {
                        $query->active()->unexpired()->started();
                    });
                });

                $query->inRandomOrder();
            },
        ]);

        $sections = $sections->whereHas('vendors', function ($query) {
            $query->active();
            $query->when(setting('other.enable_subscriptions') == 1, function ($q) {
                return $q->whereHas('subbscription', function ($query) {
                    $query->active()->unexpired()->started();
                });
            });
        })->active()->inRandomOrder()->take(10)->get();
        return $sections;
    }

    public function getCategories($request)
    {
        $query = $this->category->active()->mainCategories();

        if ($request->show_in_home == 1)
            $query = $query->where('show_in_home', 1);

        if ($request->model_flag == 'tree')
            $query = $query->with('childrenRecursive');

        $query = $query->whereHas('vendors', function ($query) use ($request) {
            $query->active();

            $stateId = $request->state_id;
            $vendorId = $request->vendor_id;

            $query = $query->when(!is_null($vendorId), function ($query) use ($vendorId) {
                return $query->where('vendors.id', $vendorId);
            });

            $query = $query->when(setting('other.enable_subscriptions') == 1, function ($query) {
                return $query->whereHas('subbscription', function ($query) {
                    $query->active()->unexpired()->started();
                });
            });

            $query = $query->when(setting('other.select_shipping_provider') == 'vendor_delivery' && !is_null($stateId), function ($query) use ($stateId) {
                return $query->whereHas('deliveryCharge', function ($query) use ($stateId) {
                    $query->where('state_id', $stateId);
                });
            });

            $query = $query->whereHas('products', function ($query) use ($request) {
                $query->active();
            });
        });

        $query = $query->orderBy('sort', 'asc');

        if ($request->response_type == 'paginated')
            $query = $query->paginate($request->count ?? 24);
        else {
            if (!empty($request->count))
                $query = $query->take($request->count);
            $query = $query->get();
        }

        return $query;
    }

    public function getAllVendors($request)
    {
        $vendorCategories = array_values($request->vendor_categories ?? []);

        $query = $this->vendor->active();

        if ($request->with_vendor_categories == 'yes') {
            $query = $query->with(['categories' => function ($query) {
                $query->where('vendor_categories.status', 1);
            }]);
            $query = $query->whereHas('categories', function ($query) {
                $query->where('vendor_categories.status', 1);
            });
        }

        if (count($vendorCategories) > 0) {
            $query = $query->whereHas('categories', function ($query) use ($vendorCategories) {
                $query->where('vendor_categories.status', 1);
                $query->whereIn('vendor_categories_pivot.vendor_category_id', $vendorCategories);
            });
        }

        $query = $query->when(setting('other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        $query = $query->whereHas('products', function ($query) use ($request) {
            $query->active();
        });

        if ($request['section_id']) {
            $query = $query->whereHas('sections', function ($query) use ($request) {
                $query->where('section_id', $request['section_id']);
            });
        }

        if (!is_null($request['state_id']) && setting('other.select_shipping_provider') == 'vendor_delivery') {
            $query = $query->with([
                'deliveryCharge' => function ($query) use ($request) {
                    $query->where('state_id', $request->state_id);
                }
            ]);
            $query = $query->whereHas('deliveryCharge', function ($query) use ($request) {
                $query->where('state_id', $request->state_id);
            });
        }

        if ($request['search']) {
            $query = $this->vendorSearch($query, $request);
        }

        $query = $query->sorted();

        if ($request->response_type == 'paginated')
            $query = $query->paginate($request->count ?? 24);
        else {
            if (!empty($request->count))
                $query = $query->take($request->count);
            $query = $query->get();
        }

        return $query;
    }

    public function getOneVendor($request)
    {
        $vendor = $this->vendor->active();
        $vendor = $vendor->when(setting('other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });
        return $vendor->find($request->id);
    }

    public function findById($id, $with = [])
    {
        $vendor = $this->vendor->query();

        if (!empty($with)) {
            $vendor = $vendor->with($with);
        }

        $vendor = $vendor->when(setting('other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        return $vendor->find($id);
    }

    public function findVendorByIdAndStateId($id, $stateId)
    {
        $vendor = $this->vendor
            ->with(['companies' => function ($q) use ($stateId) {
                $q->active();
                $q->whereHas('deliveryCharge', function ($query) use ($stateId) {
                    $query->where('state_id', $stateId);
                });
                $q->has('availabilities');
            }]);

        $vendor = $vendor->when(setting('other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        $vendor = $vendor->whereHas('states', function ($query) use ($stateId) {
            $query->where('state_id', $stateId);
        });

        return $vendor->find($id);
    }

    public function returnProductRelations($model, $request = null)
    {
        return $model->with([
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            },
            'options',
            'images',
            'vendor',
            'subCategories',
            'addOns',
            'variants' => function ($q) {
                $q->with(['offer' => function ($q) {
                    $q->active()->unexpired()->started();
                }]);
            },
        ]);
    }

    public function getDeliveryPrice($stateId, $vendorId)
    {
        return $this->deliveryCharge::active()
            ->where('state_id', $stateId)
            ->where('vendor_id', $vendorId)
            ->value('delivery');
    }

    public function getDeliveryPriceDetails($stateId, $vendorId)
    {
        return $this->deliveryCharge::active()
            ->where('state_id', $stateId)
            ->where('vendor_id', $vendorId)
            ->first();
    }

    private function vendorSearch($model, $request)
    {
        $term = strtolower($request['search']);
        return $model->where(function ($query) use ($term) {
            $query->whereRaw('lower(title) like (?)', ["%{$term}%"]);
            $query->orWhereRaw('lower(slug) like (?)', ["%{$term}%"]);
        });
    }
}

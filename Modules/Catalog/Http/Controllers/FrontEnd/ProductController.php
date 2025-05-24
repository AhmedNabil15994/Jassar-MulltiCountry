<?php

namespace Modules\Catalog\Http\Controllers\FrontEnd;

use Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository as Product;
use Modules\Catalog\Traits\CatalogTrait;
use Modules\Catalog\Transformers\Frontend\ProductDetailsResource;
use Modules\Catalog\Transformers\Frontend\ProductResource;
use Modules\Catalog\Transformers\Frontend\VariantProductDetailsResource;

class ProductController extends Controller
{
    use CatalogTrait;

    protected $product;

    function __construct(Product $product)
    {
        $this->product = $product;
    }


    public function lifeSearch($countryPrefix,$keyword = null)
    {
        return ProductResource::collection($this->product->getProductsByKeyword($keyword));
    }

    public function index(Request $request,$countryPrefix, $slug, $category = null)
    {
        $product = $this->product->findBySlug($slug);
        if (!$product)
            abort(404);

        if ($product && !checkRouteLocale($product, $slug)) {
            return redirect()->route('frontend.products.index', [
                $product->slug
            ]);
        }
        $category = $product->categories;

        $variantPrd = null;
        $selectedOptions = [];
        $selectedOptionsValue = [];

        if (count($request->query()) > 0) {
            $selectedOptions = getOptionsAndValuesIds($request)['selectedOptions'];
            $selectedOptionsValue = getOptionsAndValuesIds($request)['selectedOptionsValue'];
            if ($request->has('var') && !empty($request->var) && !in_array("", $selectedOptions) && !in_array("", $selectedOptionsValue)) {
                $variantPrd = $this->product->findVariantProductById($request->var);
                $variantPrd->image = $variantPrd->image ? url($variantPrd->image) : null;
            }
        }

        $related_products = $this->product->getRelatedProducts($product, $product->categories->pluck('id')->toArray(), ["tags", "variants"]);
        $product = (new ProductDetailsResource($product))->jsonSerialize();

        return view('catalog::dukaan.products.show', compact(
            'product',
            'category',
            'related_products',
            'variantPrd',
            'selectedOptions',
            'selectedOptionsValue'
        ));
    }

    public function getPrdVariationInfo(Request $request)
    {
        $variantObject = [];
        $product = $this->product->findById($request->product_id);

        if (!$product)
            return response()->json(["errors" => __('catalog::frontend.products.product_not_found')], 422);

        if (count($request->selectedOptions)) {
            $selectedOptionsValue = array_values($request->selectedOptions);

            $variantProducts = $this->product->getVariantProductsByPrdId($request->product_id);
            foreach ($variantProducts as $k => $val) {
                $values = $val->productValues()->pluck('option_value_id')->toArray();
                $result = array_diff($values, $selectedOptionsValue);

                if (count($result) == 0) {
                    $variantObject = $val;
                    $variantObject->image_url = $val->image_url;
                }
            }
        }

        if (!empty($variantObject)) {

            if($variantObject->status == 0)
                return response()->json(["errors" => __('catalog::frontend.products.validation.variation_not_found')], 400);

            $generateVariantProductData = generateVariantProductData($product, $variantObject->id, $selectedOptionsValue);
            $variantObject = (new VariantProductDetailsResource($variantObject))->jsonSerialize();
            $variantObject['title'] = $generateVariantProductData['name'];
            $variantObject['slug'] = $generateVariantProductData['slug'];
            $data = [
                'variantProduct' => $variantObject,
                'selectedOptions' => $request->selectedOptions,
                'selectedOptionsValue' => $selectedOptionsValue,
            ];
            return response()->json(["message" => 'Success', "data" => $data], 200);
        } else {
            return response()->json(["errors" => __('catalog::frontend.products.validation.variation_not_found')], 422);
        }
    }
}

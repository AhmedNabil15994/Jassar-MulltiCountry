<?php

namespace Modules\Catalog\Http\Controllers\FrontEnd;

use Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Entities\ProductAddon;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Catalog\Http\Requests\FrontEnd\CartRequest;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository as Product;
use Modules\Catalog\Transformers\Frontend\CartResource;
use Modules\Package\Repositories\Frontend\OfferRepository;
use Modules\Package\Repositories\Frontend\PackageRepository;

class ShoppingCartController extends Controller
{
    use ShoppingCartTrait;

    protected $product;

    function __construct(Product $product,PackageRepository  $package,OfferRepository  $offer)
    {
        $this->product = $product;
        $this->package = $package;
        $this->offer = $offer;
    }

    public function index()
    {
        return view('catalog::dukaan.shopping-cart.index');
    }

    public function totalCart()
    {
        return getCartSubTotal();
    }

    public function headerCart()
    {
        return view('apps::frontend.layouts._cart');
    }

    public function createOrUpdate(CartRequest $request,$countryPrefix, $productSlug, $variantPrdId = null)
    {
        $data = [];
        $itemID = '';
        $second = locale() == 'ar' ? 'en' : 'ar';

        if($request->product_type == 'product'){
            $product = $this->product->findBySlug($productSlug);
            if (!$product)
                return response()->json(["errors" => __('cart::api.cart.product.not_found') . $productSlug], 422);

            $product->product_type = 'product';
            $product->secondary_title = $product->getTranslations('title')[$second];
            $data['productDetailsRoute'] = route('frontend.products.index', [$product->slug]);
            $data['productTitle'] = $product->title;
            $productCartId = $product->id;
            $vendorId = $product->vendor_id ?? null;
            $itemID = $productCartId;

        }else if($request->product_type == 'package'){
            $product = $this->package->findPackageById($productSlug);
            if (!$product)
                return response()->json(["errors" => __('cart::api.cart.product.not_found') . $productSlug], 422);

            $product->product_type = 'package';
            $product->secondary_title = $product->getTranslations('title')[$second];
            $data['productDetailsRoute'] = route('frontend.packages.index', [$product->id]);
            $data['productTitle'] = $product->title;
            $productCartId = $product->id;
            $vendorId = $product->vendor_id ?? null;
            $itemID = 'var-package-'.$productCartId;
        }else if($request->product_type == 'offer'){
            $product = $this->offer->findOfferById($productSlug);
            if (!$product)
                return response()->json(["errors" => __('cart::api.cart.offer.not_found') . $productSlug], 422);

            $product->product_type = 'offer';
            $product->secondary_title = $product->getTranslations('title')[$second];
            $data['productDetailsRoute'] = route('frontend.offers.index', [$product->id]);
            $data['productTitle'] = $product->title;
            $productCartId = $product->id;
            $vendorId = $product->vendor_id ?? null;
            $itemID = 'var-offer-'.$productCartId;
            $product->price = $request->price;
            $product->selectedProducts = $request->selectedProducts;
        }

        $checkProduct = is_null(getCartItemById($itemID));
        if (isset($request->request_type) && $request->request_type == 'general_cart') {
            $request->merge(['qty' => getCartItemById($product->id) ? getCartQuantityById($product->id) + 1 : 1]);
        }

        if (setting('other.select_shipping_provider') == 'vendor_delivery') {
            if (getCartContent(null, true)->count() > 0 && !is_null($vendorId) && $vendorId != (getCartContent(null, true)->first()->attributes['vendor_id'] ?? ''))
                return response()->json(["errors" => __('catalog::frontend.products.alerts.empty_cart_firstly'), 'itemQty' => intval($request->qty) - 1], 422);
        }

        $errors = $this->addOrUpdateCart($product, $request,$request->product_type);

        if ($errors)
            return response()->json(["errors" => $errors], 422);

        $data = getCart(null,true);
        $data['new_item_id'] = $itemID;
        if ($checkProduct) {
            return response()->json(["message" => __('catalog::frontend.cart.add_successfully'), "data" => $data], 200);
        } else {
            return response()->json(["message" => __('catalog::frontend.cart.updated_successfully'), "data" => $data], 200);
        }
    }

    public function delete(Request $request,$countryPrefix, $id)
    {
        if ($request->product_type == 'product')
            $deleted = $this->deleteProductFromCart($id);
        else
            $deleted = $this->deleteProductFromCart('var-package-' . $id);

        if ($deleted)
            return redirect()->back()->with(['alert' => 'success', 'status' => __('catalog::frontend.cart.delete_item')]);

        return redirect()->back()->with(['alert' => 'danger', 'status' => __('catalog::frontend.cart.error_in_cart')]);
    }

    public function deleteByAjax(Request $request)
    {
        if ($request->product_type == 'product')
            $deleted = $this->deleteProductFromCart($request->id);
        else if ($request->product_type == 'package')
            $deleted = $this->deleteProductFromCart('var-package-' . $request->id);
        else if ($request->product_type == 'offer')
            $deleted = $this->deleteProductFromCart('var-offer-' . $request->id);

        if ($deleted) {
            $result = getCart(null,true);
            return response()->json(["message" => __('catalog::frontend.cart.delete_item'), "result" => $result], 200);
        }

        return response()->json(["errors" => __('catalog::frontend.cart.error_in_cart')], 422);
    }

    public function clear(Request $request)
    {
        $cleared = $this->clearCart();

        if ($cleared)
            return redirect()->back()->with(['alert' => 'success', 'status' => __('catalog::frontend.cart.clear_cart')]);

        return redirect()->back()->with(['alert' => 'danger', 'status' => __('catalog::frontend.cart.error_in_cart')]);
    }

    public function addonsValidation($request, $productId)
    {
        $request->addonsOptions = isset($request->addonsOptions) ? json_decode($request->addonsOptions) : [];
        if (isset($request->addonsOptions) && !empty($request->addonsOptions) && $request->product_type == 'product') {
            foreach ($request->addonsOptions as $k => $value) {

                $addOns = ProductAddon::where('product_id', $productId)->where('addon_category_id', $value->id)->first();
                if (!$addOns) {
                    return __('cart::api.validations.addons.addons_not_found') . ' - ' . __('cart::api.validations.addons.addons_number') . ': ' . $value->id;
                }

                $optionsIds = $addOns->addonOptions ? $addOns->addonOptions->pluck('addon_option_id')->toArray() : [];
                if ($addOns->type == 'single' && count($value->options) > 0 && !in_array($value->options[0], $optionsIds)) {
                    return __('cart::api.validations.addons.option_not_found') . ' - ' . __('cart::api.validations.addons.addons_number') . ': ' . $value->options[0];
                }

                if ($addOns->type == 'multi') {
                    if ($addOns->max_options_count != null && count($value->options) > intval($addOns->max_options_count)) {
                        return __('cart::api.validations.addons.selected_options_greater_than_options_count') . ': ' . $addOns->addonCategory->getTranslation('title', locale());
                    }

                    if ($addOns->min_options_count != null && count($value->options) < intval($addOns->min_options_count)) {
                        return __('cart::api.validations.addons.selected_options_less_than_options_count') . ': ' . $addOns->addonCategory->getTranslation('title', locale());
                    }

                    if (count($value->options) > 0) {
                        foreach ($value->options as $i => $item) {
                            if (!in_array($item, $optionsIds)) {
                                return __('cart::api.validations.addons.option_not_found') . ' - ' . __('cart::api.validations.addons.addons_number') . ': ' . $item;
                            }
                        }
                    }
                }
            }
        }

        return true;
    }
}

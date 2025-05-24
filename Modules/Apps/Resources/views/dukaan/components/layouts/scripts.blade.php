

<script>
    function VueData(data){
        return data;
    }

    function VueMethods(methods){

        return methods;
    }

    function vueCreated(){}
    function vueMounted(){}
</script>

@yield('vuejs')
<script>
    const { createApp } = Vue;
    @if((setting('other.selling_on_site') ?? 1) == 1)
    let cartData = @json(getCart());
    @endif
    let favourateProducts = @json(auth()->check() ? auth()->user()->favourites->pluck('id')->toArray() : []);
    let data = VueData({
        //general
        pageLoader:false,
        current_currency:'{{currentCurrencyCode()}}',
        ///////////////////

        //lifesearch
        search_key:'',
        search_loader:false,
        search_not_found:false,
        search_items:[],
        ///////////////////

        //Favourites
        favourateProducts:favourateProducts,
        ///////////////////
        @if((setting('other.selling_on_site') ?? 1) == 1)
        //cart data
        cart: cartData,
        succesfullyAddedItem: null,
        package_id: null,
        offer_id: null,
        productBtns: [],
        packageBtns: [],
        offerBtns: [],
        ////////////
        @endif
    });

    let methods = VueMethods({
        // general


        removeById(list, id){

            const index = list.indexOf(id);

            if (index > -1)
                index.splice(index, 1);
        },


        changeCurrency(event){
            this.pageLoader = true;
            axios.post("{{route('frontend.update.currency')}}",{code:event.target.value}).then(response => {
                window.location.reload();
            }).catch(error => {
                window.location.reload();
            });
        },

        changeCountry(event){
            this.pageLoader = true;
            axios.post("{{route('frontend.update.country')}}",{country_id:event.target.value}).then(response => {
                window.location.reload();
            }).catch(error => {
                window.location.reload();
            });
        },

        //lifeSearch getProducts
        getProducts(){
            if(this.search_key != ''){

                this.search_loader = true;
                let action = "{{ route('frontend.products.lifesearch', 'keyword') }}";
                action = action.replace('keyword', this.search_key);

                axios.get(action).then(response => {

                    this.search_items = response.data.data;
                    this.search_loader = false;
                    if(!this.search_items.length)
                        this.search_not_found = true;
                });
            }else{

                this.search_not_found = false;
            }
        },
        ///////////////////////

        //product Favourites

        addToFavourites(id){

            let action = "{{ route('frontend.profile.favourites.store', ['id']) }}";
            id= parseInt(id);
            action = action.replace('id', id);
            const index = this.favourateProducts.indexOf(id);

            if (index > -1)
                this.favourateProducts.splice(index, 1);
            else
                this.favourateProducts.push(id);

            axios.post(action);
        },
        ///////////////////////
        @if((setting('other.selling_on_site') ?? 1) == 1)
        //cart data

        closeModal(id){
            $('#'+id).modal('hide');
        },

        cartCount(){
            return this.cart.items.length;
        },

        cartIsImpty(){
            return this.cart.items.length ? false : true;
        },

        findItemById(id,type='product'){
            return  this.cart.items.find(item => item.id == id);
        },

        cartSubTotal(){
            return this.cart.cart_subTotal;
        },

        cartTotal(){
            return this.cart.cart_total;
        },

        cartItems(){
            return this.cart.items;
        },

        toggleDisableCartButton(id,type='product'){

            if(type=='product'){
                if(!this.productBtns.filter(product => product.id == id).length)
                    this.productBtns.push({id:id,showLoader:false});

                this.productBtns.map((product) => {

                    if(product.id == id)
                        product.showLoader = !product.showLoader;
                })
            }else if(type=='package'){
                if(!this.packageBtns.filter(package => package.id == id).length)
                    this.packageBtns.push({id:id,showLoader:false});

                this.packageBtns.map((package) => {

                    if(package.id == id)
                        package.showLoader = !package.showLoader;
                })
            }else if(type=='offer'){
                if(!this.offerBtns.filter(offer => offer.id == id).length)
                    this.offerBtns.push({id:id,showLoader:false});

                this.offerBtns.map((offer) => {

                    if(offer.id == id)
                        offer.showLoader = !offer.showLoader;
                })
            }

        },
        setSelectedProducts(offer_id,productId,price,qty){
            let item = this.findItemById('var-offer-'+offer_id,'offer');
            if(item){
                item.selectedProducts = productId
                item.price = (price * qty) + ' ' + '{{CURRENCY}}'
                let url = "{{ route('frontend.shopping-cart.create-or-update', [':id']) }}".replace(':id',offer_id);
                this.addItemToCart(url,offer_id,'offer',item.price,item.selectedProducts)
            }

        },
        addItemToCart(url, productId,type='product',price=0,selectedProducts=''){
            this.toggleDisableCartButton(productId,type);
            let requestData = {
                request_type: 'general_cart',
                product_type: type,
            };
            if(price){
                requestData.price = price;
                requestData.selectedProducts = selectedProducts;
            }
            axios.post(url,requestData).then(response => {
                this.cart = response.data.data;
                this.succesfullyAddedItem = this.findItemById(this.cart.new_item_id,type);
                this.toggleDisableCartButton(productId,type);
                if(type == 'offer'){
                    $('#addpro_modal').modal('toggle');
                }else{
                    $('#addcart_modal').modal('show');
                }
            }).catch( (error) => {
                toastr['error'](error.response.data.errors);
                this.toggleDisableCartButton(productId,type);
            });

        },

        removeItemFromCart(productID, productType = 'product'){

            this.cart.items.map((cartItem) => {
                if(cartItem.attributes.product.id == productID){

                    cartItem.attributes.product.cart_loader = true;
                }
            });

            axios.get("{{route('frontend.shopping-cart.deleteByAjax')}}",{
                params: {
                    id: productID,
                    product_type: productType,
                }
            }).then(response => {this.cart = response.data.result;})
                .finally(() => {
                    if("{{Request::segment(4)}}"  == 'payment'){
                        window.location.reload();
                    }
                    if(this.cartIsImpty())
                        $('#body-overlay').removeClass('active');
                });
        },
        ////////////
        @endif
    });


    let app = createApp({
        data() {
            return data;
        },
        mounted() {
            vueMounted();
        },
        created() {
            vueCreated();
        },
        methods: methods,
    }).mount('#theApp');

    function alertMessage(type,message){

        toastr[type](message);
    }

    function getCheckoutsteps(
        cartStatus = false,
        AddressStatus = false,
        checkoutStatus = false,
        invoiceStatus = false,
        cartAvtive = false,
        AddressAvtive = false,
        checkoutAvtive = false,
        invoiceAvtive = false,
    ){
        return [
            {
                key:'cart',
                status:cartStatus,
                id:'step1',
                title:'@lang('Shopping cart')',
                active_now:cartAvtive
            },
            {
                key:'address',
                status:AddressStatus,
                id:'step2',
                title:'@lang('Address')',
                active_now:AddressAvtive
            },
            {
                key:'checkout',
                status:checkoutStatus,
                id:'step3',
                title:'@lang('checkout')',
                active_now:checkoutAvtive
            },
            {
                key:'invoice',
                status:invoiceStatus,
                id:'step4',
                title:'@lang('invoice')',
                active_now:invoiceAvtive
            },
        ];
    }

    function redirect(data) {
        if (data['url']) {
            var url = data['url'];

            if (url) {
                if (data['blank'] && data['blank'] == true) {

                    window.open(url, '_blank');
                } else {
                    window.location.replace(url);
                }
            }
        }
    }

    function redirectToUrl(url,blank = false) {
        if (blank) {

            window.open(url, '_blank');
        } else {

            window.location.replace(url);
        }
    }

</script>
@stack('last')

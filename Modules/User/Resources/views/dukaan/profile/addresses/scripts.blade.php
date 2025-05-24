
<script>
    function addressData(data){
        data.addresses = @json($addresses);
        data.countries = @json($countries);
        data.cities = @json($cities);
        data.locale = '{{locale()}}';
        data.current_country = '{{currentCountry()}}';

        data.modal_address = {
            id: '',
            action: 'create',
            country_id: '',
            state_id: '',
            city_name: '',
            phone_code:  data.countries.find(country => country.id == parseInt(data.current_country)).phone_code,
            mobile: '',
            username: '',
            block: '',
            street: '',
            building: '',
            address: '',
            default: false,
            delete_loader: false,
        };
        data.success_submit = false;
        data.get_cities_loader = false;
        data.modalBtnLoader = false;
        data.errors = [];
        return data;
    }

    function addressMounted(){

        $(document.body).on("change","#country_id",function(){
            app.getCities(this.value);
        });
        
    }

    function addressMethods(methods){


        methods.findAddressById = function(id){

            return this.addresses.find(address => address.id == id);
        }

        methods.refreshAddress = function(address = {}){

            for (var key in this.modal_address) {
                if (address.hasOwnProperty(key)) {
                    this.modal_address[key] = address[key];
                }else{
                    this.modal_address[key] = '';
                }
            }

            this.modal_address.default = this.modal_address.default == 1 ? true : false;
        }

        methods.openCreateAddressModal = function(){
            this.errors = [];
            this.success_submit = false;
            this.refreshAddress();
            this.modal_address.action = 'create';
            this.getCities(this.current_country);
            $('#exampleModalLong').modal('show');
        }

        methods.openEdit = function(id){
            
            let address = this.findAddressById(id);
            this.getCities(address.country_id, address.state_id);
            this.errors = [];
            this.success_submit = false;
            this.modal_address = address;
            this.modal_address.action = 'update';
            $('#exampleModalLong').modal('show');
        }

        methods.getCities = function(countryId,state_id = null){
            this.get_cities_loader = true;
            axios.get("{{route('frontend.area.get_child_area_by_parent')}}",{params: {country_id: countryId}}).then(response => {
                
                this.cities = response.data[0];
                this.get_cities_loader = false;

            }).finally(function() {
                
                $('#country_id').val(countryId);
                if(state_id)
                    $('#state_id').val(state_id);

                $('.mySelect2').select2({

                    dropdownParent: $('#exampleModalLong').first()
                });

                $('#state_id').select2();
            });
        }

        methods.saveAddress = function(){
            
            this.modal_address.country_id = $('#country_id').val();
            this.modal_address.state_id = $('#state_id').val();
            this.modal_address.city_name = $( "#state_id option:selected" ).text();
            this.modalBtnLoader = true;
            this.errors = [];

            if(this.modal_address.action == 'create'){
                var url = "{{route('frontend.profile.address.store')}}";
            }else{
                var url = "{{route('frontend.profile.address.update',':id')}}";
                url = url.replace(':id', this.modal_address.id);
            }

            axios.post(url,this.modal_address).then(response => {
                
                this.modalBtnLoader = false;
                this.success_submit = false;
                this.errors = [];
                this.refreshAddress();
                this.addresses = response.data.addresses;
                alertMessage('success', response.data.message);

                $('#exampleModalLong').modal('hide');
            }).catch(error => {

                this.errors = error.response.data.errors;
                this.modalBtnLoader = false;
                this.success_submit = true;
                alertMessage('error', error.response.data.message);
            });
        }

        methods.deleteAddress = function(id){
            
            let address = this.findAddressById(id);
            address.delete_loader = true;
            var url = "{{route('frontend.profile.address.delete',':id')}}";
            url = url.replace(':id', id);

            axios.get(url).then(response => {
                
                this.addresses = response.data.addresses;
                alertMessage('success', response.data.message);
            }).catch(error => {

                this.errors = error.response.data.errors;
                alertMessage('error', error.response.data.message);
            });
        }

        methods.makeDefault = function(id){
            
            let address = this.findAddressById(id);
            this.addresses.map(item => {
                item.default = false;
            });

            address.default = true;
            var url = "{{route('frontend.profile.address.set-default',':id')}}";
            url = url.replace(':id', id);
            axios.get(url);
        }

        return methods;
    }
</script>

<div class="modal fade" id="exampleModalLong" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"> @lang('front.New Address') </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti-close"></i></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <input type="text" :class="'form-control ' +  (errors.hasOwnProperty('username') ? 'is-invalid' : '') + (success_submit && !errors.hasOwnProperty('username') == true ? ' is-valid' : '')"
                             name="text" placeholder="@lang('front.Address Name')"  v-model="modal_address.username">
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('username')">@{{errors.username[0]}}</div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class='phone-cont'>
                                <select class="selectpicker select-country" data-live-search="true" v-model="modal_address.phone_code">
                                    
                                        <option v-for="country in countries" :value="country.phone_code">
                                            @{{country.emoji}} <span class="country-name">@{{country.title[locale]}}</span> <span class="code">+@{{country.phone_code}}</span>
                                        </option>
                                    
                                </select>
                                <input placeholder="@lang('front.Phone No')." type="text"  v-model="modal_address.mobile"
                                :class="'form-control ' +  (errors.hasOwnProperty('phone_code') || errors.hasOwnProperty('mobile') 
                                ? 'is-invalid' : (success_submit && !errors.hasOwnProperty('phone_code') == true && !errors.hasOwnProperty('mobile') == true ? ' is-valid' : ''))" />
                            </div>
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('phone_code')">@{{errors.phone_code[0]}}</div>
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('mobile')">@{{errors.mobile[0]}}</div>
                        </div>

                        <div class="form-group col-md-6">
                            <select id="country_id" @change="getCities()"  class="select2 mySelect2 form-control">
                                <option v-for="country in countries" :value="country.id">@{{country.title[locale]}}</option>
                            </select>
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('country_id')">@{{errors.country_id[0]}}</div>
                        </div>

                        <div class="form-group col-md-6" v-if="!get_cities_loader">
                            <select id="state_id"  class="select2 mySelect2 form-control">
                                <optgroup v-for="city in cities" :label="city.title" :data-select2-id="city.id">
                                    <option v-for="state in city.states" :value="state.id" :data-select2-id="state.id">@{{state.title}}</option>
                                </optgroup>
                            </select>
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('state_id')">@{{errors.state_id[0]}}</div>
                        </div>

                        <div class="form-group col-md-6" v-else>
                            <div class="row">
                                <x-dukaan-sppiner-loader/>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <input type="text" 
                            :class="'form-control ' +  (errors.hasOwnProperty('street') ? 'is-invalid' : '') + (success_submit && !errors.hasOwnProperty('street') == true ? ' is-valid' : '')"
                             name="text" placeholder="@lang('front.Streat')" v-model="modal_address.street">
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('street')">@{{errors.street[0]}}</div>
                        </div>

                        <div class="form-group col-md-6">
                            <input type="text" 
                            :class="'form-control ' +  (errors.hasOwnProperty('block') ? 'is-invalid' : '') + (success_submit && !errors.hasOwnProperty('block') == true ? ' is-valid' : '')"
                             name="text" placeholder="@lang('front.Block No')." v-model="modal_address.block">
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('block')">@{{errors.block[0]}}</div>
                        </div>

                        <div class="form-group">
                            <input type="text" 
                            :class="'form-control ' +  (errors.hasOwnProperty('building') ? 'is-invalid' : '') + (success_submit && !errors.hasOwnProperty('building') == true ? ' is-valid' : '')"
                             name="text" placeholder="@lang('front.Building No, Floor, Flat No')." v-model="modal_address.building">
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('building')">@{{errors.building[0]}}</div>
                        </div>

                        <div class="form-group">
                            <textarea class="form-control" placeholder="@lang('front.Note')" v-model="modal_address.address"></textarea>
                            <div class="invalid-feedback invalid-feedback-active" v-if="errors.hasOwnProperty('address')">@{{errors.address[0]}}</div>
                        </div>

                        <div class="form-group">
                            <input class="form-check-input" type="checkbox" id="flexCheckDefault50" v-model="modal_address.default">
                            <label class="form-check-label check-note" for="flexCheckDefault50">
                                @lang('front.Mark as default')
                            </label>
                        </div>

                        <div class="d-flex justify-content-end">

                            <button @click="saveAddress()" class="btn theme-btn" type="button" :disabled="modalBtnLoader">
                                <span v-if="modalBtnLoader"><x-dukaan-btn-loader/></span>
                                <span v-else class="btn-text">@lang('front.Save Changes')</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>   
</div> 
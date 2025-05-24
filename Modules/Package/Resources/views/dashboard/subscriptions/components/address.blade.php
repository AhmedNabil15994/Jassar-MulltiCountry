<!-- Button trigger modal -->
<button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#address_{{$address->id}}">
  <i class="fa fa-home"></i>
</button>

<!-- Modal -->
<div class="modal fade" id="address_{{$address->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <table class="table table-striped">
          <tbody>
            <tr>
              <th scope="col">{{__('Country')}}</th>
              <td>{{optional(optional(optional($address->state)->city)->country)->title}}</td>
            </tr>
            <tr>
              <th scope="col">{{__('City')}}</th>
              <td>{{optional(optional($address->state)->city)->title}}</td>
            </tr>
            <tr>
              <th scope="col">{{__('State')}}</th>
              <td>{{optional($address->state)->title}}</td>
            </tr>
            <tr>
              <th scope="col">{{__('Streat')}}</th>
              <td>{{optional($address)->street}}</td>
            </tr>
            <tr>
              <th scope="col">{{__('Block No.')}}</th>
              <td>{{optional($address)->block}}</td>
            </tr>
            <tr>
              <th scope="col">{{__('Building No., Floor, Flat No.')}}</th>
              <td>{{optional($address)->building}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
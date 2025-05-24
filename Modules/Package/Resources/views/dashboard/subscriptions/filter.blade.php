

<div class="form-actions mt-4">
  <div class="col-md-6">
    {!! field()->date('can_order_in', __('package::dashboard.subscriptions.datatable.can_order_in'),now()) !!}
  </div>
  <div class="clearfix"></div>
  <div class="col-md-3">
    {!! field('filter_switch')->checkBox('is_default', __('package::dashboard.subscriptions.datatable.is_default'),null, ['class' => 'switch-btn
    filter-datatable']) !!}
  </div>
</div>


@push('scripts')
<script>
  $('.switch-btn').bootstrapSwitch({
        onSwitchChange: function (e, state) {
            let form = $("#formFilter");
            let data = getFormData(form);
            $("#dataTable").DataTable().destroy();
            tableGenerate(data);
        }
  });
  $('.filter-datatable').on('change',function(){
      let form = $("#formFilter");
      let data = getFormData(form);
      $("#dataTable").DataTable().destroy();
      tableGenerate(data);
  })
</script>
@endpush

{!! field()->select('subscription_id',__('package::dashboard.suspensions.form.user'),
$extraData['subscriptions']->pluck('user.name', 'id')) !!}
<div class="subscription-information">

</div>
{!! field()->date('start_at',__('package::dashboard.suspensions.form.start_at')) !!}
{!! field()->date('end_at',__('package::dashboard.suspensions.form.end_at')) !!}
{!! field()->textarea('notes',__('package::dashboard.suspensions.form.notes')) !!}




@push('scripts')
<script>
  $('#subscription_id').change(function(){
        $.ajax({
          type: "get",
          url: "{{ route('dashboard.subscriptions.getSubscriptionById',':id') }}".replace(":id",$(this).val()),
          success: function (response) {
              $('.subscription-information').html(`
                  <h5>{{ __('package::dashboard.suspensions.form.start_at') }} : ${response['start_at']}</h5>
                  <h5>{{ __('package::dashboard.suspensions.form.end_at') }} : ${response['end_at']}</h5>
              `)
          }
        });
      })
</script>
@endpush

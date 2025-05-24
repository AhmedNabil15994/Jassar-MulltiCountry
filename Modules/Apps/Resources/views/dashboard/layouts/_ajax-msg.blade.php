<div id="result" style="display: none"></div>

<div class="progress-info" style="display: none">

	@if(isset($alert))

		<div class="alert {{$alert['class']}}" role="alert">
			{{$alert['dec']}}
		</div>
	@endif

	<div class="progress">
		<span class="progress-bar progress-bar-warning"></span>
	</div>

	<div class="status" id="progress-status"></div>

</div>

<!--
Base Component Card Layout
Student Dashboard - Reusable Card Component Structure
-->
@php
	$cardClass = isset($cardClass) ? $cardClass : '';
	$cardHeader = isset($cardHeader) ? $cardHeader : '';
	$cardBody = isset($cardBody) ? $cardBody : '';
	$cardFooter = isset($cardFooter) ? $cardFooter : null;
	$componentScripts = isset($componentScripts) ? $componentScripts : null;
@endphp
<div class="card card-flush {{ $cardClass }}">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			{!! $cardHeader !!}
		</h3>
	</div>
	<div class="card-body pt-0">
		{!! $cardBody !!}
	</div>
	@if($cardFooter)
	<div class="card-footer">
		{!! $cardFooter !!}
	</div>
	@endif
</div>

@if($componentScripts)
@push('scripts')
{!! $componentScripts !!}
@endpush
@endif


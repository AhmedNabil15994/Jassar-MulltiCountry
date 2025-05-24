{!! field()->file($name ?? 'image', isset($label) ? $label : __('advertising::dashboard.advertising.form.image'), !is_null($image) ? $image : '') !!}

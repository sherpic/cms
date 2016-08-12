{{--TODO: we can have a google map in here for lat-long picking.. at the moment it's just a text box that expects something like "-34.926814, 138.605923" --}}
<fieldset class="form-group field_{{ $field_name }} {{ $field_name }} {{ $field_class }} text-field">
	<label for="field_{{ $field_name }}">{{ $field_label ? $field_label : $field_name }}</label>
	<input name="{{ $prefixed_field_name }}" id="field_{{ $field_name }}" type="text"
		   class="form-control field_{{ $field_name }} {{ $field_name }}" value="{{ $field_value }}"/>
	@if(@$field_info)
		<small class="text-muted">{{ $field_info }}</small>
	@endif
</fieldset>

@props([
    'id',
    'name',
    'type' => 'text',
    'label' => '',
    'value' => '',
    'placeholder' => '',
    'error' => null,
    'required' => false,
])

<div class="form-group">
    @if($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endif

    <input 
        type="{{ $type }}" 
        id="{{ $id }}" 
        name="{{ $name }}" 
        placeholder="{{ $placeholder }}" 
        value="{{ old($name, $value) }}" 
        class="form-control {{ $error ? 'is-invalid' : '' }}" 
        {{ $required ? 'required' : '' }}
    >

    @if($error)
        <div class="invalid-feedback">
            {{ $error }}
        </div>
    @endif
</div>

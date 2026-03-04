<div class="flex items-center gap-x-2">
    @if($value)
        <i class="{{ $value }} text-primary-500"></i>
    @endif
    <span>{{ $getLabel() }}</span>
</div>
@if($errors->any())
<div class="p-5 bg-red-100 text-red-500">
    @foreach($errors->all() as $e)
    <div>
        {{ $e }}
    </div>
    @endforeach
</div>
@endif
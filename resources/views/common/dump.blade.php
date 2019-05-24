<h4 class="mt-2">{{ $title }}</h4>
<div class="mt-2 mb-2">
    <pre class="border p-2 text-white bg-dark overflow-auto" style="max-height:20rem;">{!! json_encode($data, JSON_PRETTY_PRINT) !!}</pre>
</div>

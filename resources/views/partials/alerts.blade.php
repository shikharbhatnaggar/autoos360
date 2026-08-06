@if(session('success'))
    <div class="mb-4 rounded-lg bg-green-100 border border-green-200 text-green-800 p-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 rounded-lg bg-red-100 border border-red-200 text-red-800 p-4">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 rounded-lg bg-red-100 border border-red-200 text-red-800 p-4">
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
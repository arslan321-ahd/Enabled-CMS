<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<form method="POST" action="{{ route('forms.submit', $form->id) }}" enctype="multipart/form-data">
    @csrf

    <h3>{{ $form->title }}</h3>

    @foreach ($form->fields as $field)
        <div class="mb-3">
            <label>{{ $field->label }}</label>

            @if ($field->type == 'select')
                <select name="{{ $field->name }}" class="form-control">
                    @foreach (json_decode($field->options ?? '[]') as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
            @elseif($field->type == 'textarea')
                <textarea name="{{ $field->name }}" class="form-control"></textarea>
            @else
                <input type="{{ $field->type }}" name="{{ $field->name }}" class="form-control">
            @endif
        </div>
    @endforeach

    <button class="btn btn-primary">Submit</button>
</form>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
</script>

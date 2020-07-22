<!DOCTYPE html>
<html>
<head>
    <title>Уменьшитель URL</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />

    <style>
        p {
            margin-top: 0;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
   
<div class="container">
   
    <div class="card mt-5">
      <div class="card-header">
        <form method="POST" action="{{ route('generate.shorten.link.post') }}">

            @csrf

            <div class="input-group mb-3">
              <label for="link" class="col-sm-1 col-form-label">http://</label>
              <input type="text" id="link" name="link" class="form-control" placeholder="URL" aria-label="Recipient's username" aria-describedby="basic-addon2">
            </div>

            <div class="input-group mb-3">
              <label for="date" class="col-sm-1 col-form-label">До:</label>
              <input type="datetime-local" class="form-control" id="date" name="date">
            </div>

            <div class="input-group mb-3">
              <button class="btn btn-success form-control" type="submit">Уменьшить</button>
            </div>
            
        </form>
      </div>
      <div class="card-body">
   
            @if (Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif

            @if (count($errors) > 0)
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">
                        <p>{{ $error }}</p>
                    </div>
                @endforeach
            @endif
   
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Короткая ссылка</th>
                        <th>Ссылка</th>
                        <th>Осталось времени</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shortLinks as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td><a href="{{ route('shorten.link', $row->code) }}" target="_blank">{{ route('shorten.link', $row->code) }}</a></td>
                            <td>{{ $row->link }}</td>
                            @if(empty($row->expiry_at))
                                <td>&infin;</td>
                            @else
                                <td>{{ $row->expiry_at }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
      </div>
    </div>
   
</div>
    
</body>
</html>
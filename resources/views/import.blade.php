@extends('layout')

@section('content')
    <div class="jumbotron jumbotron-fluid text-center">
        <div class="container">
            <h5 class="display-4">Homeowner Import</h5>
            <form class="form-horizontal" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <br />
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group row">
                    <div class="col-6 text-right {{ $errors->has('file') ? 'text-danger' : null }}">
                        <label for="file" class="form-label">Select a CSV to upload <sup>*</sup></label>
                    </div>
                    <div class="col-6 text-left">
                        <input type="file" name="file" id="file">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </form>
        </div>
    </div>

    @if(isset($results) && count($results) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">First</th>
                    <th scope="col">Initials</th>
                    <th scope="col">Last</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $key => $result)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $result['title'] ?? '' }}</td>
                        <td>{{ $result['first_name'] ?? '' }}</td>
                        <td>{{ $result['initial'] ?? '' }}</td>
                        <td>{{ $result['last_name'] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@stop
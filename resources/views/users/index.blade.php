@extends('layouts.new')
@section('content')
    <div class="container mt-5">
        @if (\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success') }}</p>
        </div>
    @endif
            <div class="card-header">
                <h3 class="text-secondary text-center"><strong> User Data with Role</strong></h3>
                <div class="float-right mb-2">
                    <a class="btn btn-primary" href="{{ route('users.create') }}">New Users</a>
                </div>
            </div>
            <div class="card-body">

                <table class="table table-bordered table-striped mt-5 yajra_datatable">
                    <thead>
                        <tr>
                        <th>Id</th>
                        <th>UserName</th>
                        <th >Email</th>
                        <th>Roles</th>
                        <th width="280px">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
    </div>
@endsection

@section('scripts')

<script type="text/javascript">
    $(function () {
      var table = $('.yajra_datatable').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('users.index') }}",
          columns: [
              {data: 'id', name: 'id'},
              {data: 'name', name: 'name'},
              {data: 'email', name: 'email'},
              {data: 'user_roles', name: 'user_roles'},
              {data: 'action', 
              name: 'action', 
              orderable: false, 
              searchable: false
          },
          ]
      });
    });
  </script>
    
@endsection
@extends('layouts.new')
@section('content')
    <div class="container mt-5">
        @if (\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success') }}</p>
        </div>
    @endif
            <div class="card-header">
                <h3 class="text-secondary text-center"><strong> Company Employee</strong></h3>
                <div class="float-right mb-2">
                    <a class="btn btn-primary" href="{{ route('employee.create') }}">New Employee Add</a>
                </div>
            </div>
            <div class="card-body">

                <table class="table table-bordered table-striped mt-5 yajra_datatable">
                    <thead>
                        <tr>
                        <th>Id</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th >Email</th>
                        <th>Phone</th>
                        <th>Company</th>
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
          ajax: "{{ route('employee.index') }}",
          columns: [
              {data: 'id', name: 'id'},
              {data: 'first_name', name: 'first_name'},
              {data: 'last_name', name: 'last_name'},
              {data: 'email', name: 'email'},
              {data: 'phone', name: 'phone'},
              {data: 'company_name', name: 'company_name'},
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
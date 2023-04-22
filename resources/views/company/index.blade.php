@extends('layouts.new')
@section('content')

    <div class="container">

        @if (\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success') }}</p>
        </div>
    @endif
            <div class="card-header">
                <h3 class="text-secondary text-center"><strong> company</strong></h3>
                <div class="float-right mb-2">
                    <a class="btn btn-primary" href="{{ route('company.create') }}">New company</a>
                </div>
            </div>
            <div class="card-body">

                <table class="table table-bordered table-striped mt-5 yajra-datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Company Name</th>
                            <th>Email</th>
                            <th >Image</th>
                            <th>Website</th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
    </div>
@endsection

@section('scripts')

    <script type="text/javascript">
        $(function() {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('company.getcompany') }}",

                columns: [
                    {data: 'id', name: 'id'},
              {data: 'company_name', name: 'company_name'},
              {data: 'email', name: 'email'},
              {data: 'image', name: 'image',
              render: function( data, type, full, meta ) {
                        return "<img src=\"uploads/logo/" + data + "\" height=\"100\"/>";
            }
        },
              {data: 'website', name: 'website'},
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
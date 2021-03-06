@include('layouts.header')
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                     <h4 class="card-title">Role Management</h4>
                  </div>
                  <div class="col-md-6">
                    @can('role-create')
                      <a href="{{ route('roles.create') }}" class="btn btn-outline-success btn-icon-text" style="float:right;">
                      Create New Role <i class="ti-control-forward"></i>
                      </a>
                    @endcan
                </div>
                </div>
                <!-- <div class="pull-right">
                        @can('role-create')
                        <a class="btn btn-success" href="{{ route('roles.create') }}"> Create New Role</a>
                        @endcan
                    </div> -->
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                      <thead>
                        <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach ($roles as $key => $role)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                            <?php
                                $rid = Crypt::encryptString($role->id); // encode the User id
                            ?>
                                <a class="btn btn-info" href="{{ route('roles.show', $rid) }}">Show</a>
                                @can('role-edit')
                                <a class="btn btn-primary" href="{{ route('roles.edit', $rid) }}">Edit</a>
                                @endcan
                                @can('role-delete')
                                {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $rid],'style'=>'display:inline']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!}
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
       {!! $roles->render() !!}

@include('layouts.footer')

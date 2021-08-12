@include('layouts.header')
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">User List</h4>
                  @if(Session::has('message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                  @endif
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                      <thead>
                        <tr>
                          <th>Sr. No.</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Role</th>
                          <th>Date Created</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i=0 ?>
                        @forelse ($userD as $user)
                        <?php $i++ ?>
                        <tr>
                          <td>{{$i}}</td>
                          <td>{{ $user->fname }} {{$user->lname }}</td>
                          <td>{{ $user->email}}</td>
                          <td>
                          {{ $user->role}}
                          </td>
                          <td>{{ date('d-m-Y', strtotime($user->created_at)) }}</td>
                          <td>
                            <a href='edituser/{{$user->id}}' class="badge badge-warning"><i class="ti-pencil" data-toggle="tooltip" title="Edit User"></i></a> 
                            <a href='delete/{{ $user->id }}' class="badge badge-danger" onclick="return confirm('Are you sure?')"><i class="ti-trash" data-toggle="tooltip" title="Delete User"></i></a>
                          </td>
                          </td>
                        </tr>
                        @empty
                        <tr>
                          <th>No Records Found!</th>
                        </tr>
                        @endforelse
                      </tbody>
                    </table>
                    {{-- Pagination --}}
                    <div class="template-demo">
                    <div class="btn-group d-flex ">
                        {!! $userD->links() !!}
                    </div>
                    </div>
                   </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
@include('layouts.footer')
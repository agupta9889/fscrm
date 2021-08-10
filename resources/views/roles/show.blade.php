@include('layouts.header')
      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-3 grid-margin stretch-card"></div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-warning btn-icon-text" >
                        <i class="ti-control-backward"></i>
                            Back</a>
                    </div> 
                    <h4 class="card-title">Show Role</h4>
                    <div class="form-group">
                    <label for="exampleInputUsername1"><strong>Name:</strong></label>
                    <br/>
                    {{ $role->name }}
                    </div>
                    <div class="form-group">
                    <label for="exampleInputUsername1"><strong>Permission:</strong></label>
                        <br/>
                        @if(!empty($rolePermissions))
                            @foreach($rolePermissions as $v)
                                <label class="label label-success">{{ $v->name }},</label>
                            @endforeach
                        @endif
                    </div>
                </div>
              </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card"></div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
@include('layouts.footer')

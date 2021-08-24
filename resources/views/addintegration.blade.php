@include('layouts.header')
      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-3 grid-margin stretch-card"></div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Add Integration</h4>
                  @if(Session::has('message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                  @endif
                  <form class="forms-sample" method="post" action="{{ URL::to('insertintegaration') }}">
                    @csrf()
                    <div class="form-group">
                      <label for="exampleInputUsername1">Name</label>
                      <input type="text" name="name" class="form-control" id="exampleInputUsername1" placeholder="Full Name" required>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Username/Email ID</label>
                      <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputUsername1">API Key</label>
                      <input type="text" name="api_key" class="form-control" id="exampleInputUsername1" placeholder="API Key" required>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Offer/Rotator Id</label>
                      <input type="text" name="rotator_id" class="form-control" id="exampleInputPassword1" placeholder="Offer/Rotator Id" required>
                    </div>
                    <input type="submit" class="btn btn-primary mr-2" value="Submit">
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card"></div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
@include('layouts.footer')

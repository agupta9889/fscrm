@include('layouts.header')
      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-3 grid-margin stretch-card"></div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Add Rotator</h4>
                  @if(Session::has('message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                  @endif
                  <form class="forms-sample" method="post" action="{{ 'addrotator' }}">
                    @csrf()
                    <div class="form-group">
                      <label for="exampleInputUsername1">Rotator Name</label>
                      <input type="text" name="rotatorname" class="form-control" id="exampleInputUsername1" placeholder="Rotator Name">
                      <input type="hidden" name="mode" value="sequential" class="form-control" id="exampleInputUsername1">
                      <input type="hidden" name="test_number" value="1231231234"  class="form-control" id="exampleInputUsername1">
                    </div>
                    
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
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
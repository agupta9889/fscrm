@include('layouts.header')
      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-3 grid-margin stretch-card"></div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit User</h4>
                  @if(Session::has('message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                  @endif
                  <form class="forms-sample" action="/edituser/{{$user[0]->id; }}" method="post">
                  @csrf()
                    <div class="form-group">
                      <label for="exampleInputUsername1">First Name</label>
                      <input type="text" name="first_name" class="form-control" id="exampleInputUsername1" placeholder="First Name" value="{{$user[0]->fname; }}">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputUsername1">Last Name</label>
                      <input type="text" name="last_name" class="form-control" id="exampleInputUsername1" placeholder="Last Name" value="{{$user[0]->lname }}">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Email address</label>
                      <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email" value="{{$user[0]->email }}">
                      <input type="hidden" name="updateID" value="{{ $user[0]->id }}">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Password</label>
                      <input type="password" name="password" class="form-control" id="exampleInputPassword1" value="">
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Role</label>
                        <div class="col-sm-5">
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="radio" <?php if($user[0]->role ==1) {?> checked <?php } ?> class="form-check-input" onclick="onButtonClickEd()" name="role" value="1" >
                            Coaching Manager
                            <i class="input-helper"></i></label>
                        </div>
                        </div>
                        <div class="col-sm-5">
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="radio" <?php if($user[0]->role ==2) {?> checked <?php } ?> class="form-check-input" onclick="onButtonClickEd1()" name="role"  value="2" >
                            Company Manager
                            <i class="input-helper"></i></label>
                        </div>
                        </div>
                    </div>
                    <div class="form-group <?php if($user[0]->role == 2){?> hide <?php } ?>" id="textInputEd">
                      <label for="exampleInputUsername1">Assigned Numbers (comma separated):</label>
                      <input type="text" name="assign_number" class="form-control" id="exampleInputUsername1" value="{{$user[0]->assigned_number }}">
                    <p class="card-description">Enter "all" for all numbers. This will only work with rotator numbers, for API numbers edit the integration.</p>
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
<script>
  function onButtonClickEd(){
    document.getElementById('textInputEd').style.display ='block';
  }
  function onButtonClickEd1(){
    document.getElementById('textInputEd').style.display = 'none';
  }
</script>
<style>
  .hide{
  display:none;
}
</style>

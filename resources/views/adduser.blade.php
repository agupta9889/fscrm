@include('layouts.header')
      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-3 grid-margin stretch-card"></div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Add User</h4>
                  @if(Session::has('message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                  @endif
                  <form class="forms-sample" method="post" action="{{'adduser'}}">
                    @csrf()
                    <div class="form-group">
                      <label for="exampleInputUsername1">First Name</label>
                      <input type="text" name="first_name" class="form-control" id="exampleInputUsername1" placeholder="First Name" required>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputUsername1">Last Name</label>
                      <input type="text" name="last_name" class="form-control" id="exampleInputUsername1" placeholder="Last Name" required>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Email address</label>
                      <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Password</label>
                      <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputUsername1">Role</label>
                        {!! Form::select('role', $role,[], array('class' => 'form-control crmrole')) !!}
                    </div>
                    <div class="form-group hide" id="textInput">
                      <label for="exampleInputUsername1">Assigned Numbers (comma separated):</label>
                      <input type="text" name="phone" class="form-control" id="exampleInputUsername1">
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

  $("select.crmrole").change(function(){
    
  var selectedRole = $(this).children("option:selected").val();
  //alert("You have selected the role - " + selectedRole);
  if(selectedRole === 'Coaching Manager' ){
    document.getElementById('textInput').style.display ='block';
  }
  else{
    document.getElementById('textInput').style.display = 'none';
  }
  
});

</script>
<style>
  .hide{
  display:none;
}
</style>
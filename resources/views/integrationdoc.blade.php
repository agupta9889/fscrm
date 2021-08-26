@include('layouts.header')
      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-1 grid-margin stretch-card"></div>
            <div class="col-md-10 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Integrations</h4>
                  <div class="table-responsive">
                    <table class="table table-hover ">
                      <tbody>
                      @if(Session::has('message'))
                      <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                      @endif
                      @foreach($integrationUser as $user)
                        <tr>
                          <td data-toggle="collapse" data-target="#table-{{$user->id}}" class="accordion-toggle"> 
                            <span class="badge badge-info">
                              <i class="ti-eye"></i>
                            </span></td>
                          <td>{{$user->name}}</td>
                          <td>{{$user->created_at}}</td>
                        </tr>
                        <tr>
                          <td colspan="12" class="hiddenRow">
                            <div class="accordian-body collapse" id="table-{{$user->id}}"> 
                              <table class="table table-striped" id="phoneTable">
                                <tbody>
                                  <tr>
                                    <div class="row col-sm-12">
                                      <form class="forms-sample" method="post" action="{{ URL::to('editintegrationdoc') }}/{{$user->id}}">
                                            @csrf()
                                        <div class="col-sm-6">
                                        <br/>
                                        <h4 class="card-title">Settings</h4>
                                          <div class="form-group">
                                            <label for="exampleInputUsername1">Name</label>
                                            <input type="text" name="name" value="{{$user->name}}" class="form-control" placeholder="Full Name"  required>
                                            <input type="hidden" name="updatedID" value="{{$user->id}}">
                                          </div>
                                          <div class="form-group">
                                            <label for="exampleInputEmail1">Username/Email ID</label>
                                            <input type="email" name="email" value="{{$user->email}}" class="form-control" placeholder="Email" required>
                                          </div>
                                          <div class="form-group">
                                            <label for="exampleInputUsername1">API Key</label>
                                            <input type="text" name="api_key" value="{{$user->api_key}}" class="form-control" placeholder="API Key" required>
                                          </div>
                                          <div class="form-group">
                                            <label for="exampleInputPassword1">Offer/Rotator Id</label>
                                            <input type="text" name="rotator_id" value="{{$user->rotator_id}}" class="form-control" placeholder="Offer/Rotator Id" required>
                                          </div>
                                          <a href="deleteintegration/{{$user->id}}" type="submit" class="form-group btn btn-danger" onclick="return confirm('Are you sure?')">Delete
                                          </a> 
                                        </div>
                                        <div class="col-sm-6">
                                          <br/>
                                          <h4 class="card-title">Managers</h4>
                                            <div class="form-group">
                                              <label for="exampleInputUsername1">Users with role "account manager"</label>
                                              <br>
                                              <?php  $userAssignID = explode(',',$user->user_assign_id); 
                                                 //print_r($userAssignID);
                                              ?>
                                              @foreach($coachingmanager as $row)
                                              <?php $assignID = explode(',',$row->id); 
                                                  // print_r($assignID);
                                              ?>
                                              <label>
                                                <input class="form-group" name="user_assign_id[]" type="checkbox" value="{{ $row->id }}" <?php if( $row->id == $user->user_assign_id ){ ?> checked <?php } ?> > {{$row->fname}} {{$row->lname}} ({{$row->email}})
                                              </label><br>
                                              @endforeach
                                            </div>
                                            <div class="form-group">
                                              <input type="submit" class="form-group btn btn-primary ml-3" value="Update">
                                            </div>
                                        </div>
                                      </form>
                                    </div>
                                  </tr>
                                </tbody>
                              </table>
                            </div> 
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <br/>
                  <h3 class="card-title">Using swifthis outside IMA?</h3>
                  <p>Show this to your developer. He'll know what to do. Once you get a response back, please save the sales_number and show it to your lead on your members area.</p>
                  <blockquote>
                    <pre>
                      <code>API Configuration Steps are here...</code>
                    </pre>
                  </blockquote>
                </div>
              </div>
            </div>
            <div class="col-md-1 grid-margin stretch-card"></div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
@include('layouts.footer')

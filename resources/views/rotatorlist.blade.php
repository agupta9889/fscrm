@include('layouts.header')
 <!------------Popover css--------------->
  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"> -->

      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Rotator List</h4>
                  @if(Session::has('message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                  @endif
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Rotator Name</th>
                          <th>ID</th>
                          <th>Mode</th>
                          <th>Phones</th>
                          <th>Report Leads</th>
                          <th>Status</th>
                          <th>Test Number</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      @forelse($rotatorD as $rotator)
                        <tr>
                          <td data-toggle="collapse" data-target="#table-<?php echo $rotator->id;?>" class="accordion-toggle">
                            <span onClick="rotatorPhId({{ $rotator->id }})">
                              <i class="ti-eye" data-toggle="tooltip" title="Show Data"></i>
                          </span>
                          </td>
                          <td>{{ $rotator->rotatorname }}</td>
                          <td>{{ $rotator->id }}</td>
                          <td>{{ $rotator->mode }}</td>
                          <td>7   1 / 6</td>
                          <td>3   3 / 0</td>
                          <td>
                          <?php if($rotator->status ==0){?>
                              Active
                              <?php } else { ?>
                                Paused
                                <?php  } ?>
                                  
                              </td>   
                          <td>{{ $rotator->test_number }}</td>
                          <td>
                            <a href="#" onClick="rotatorId({{ $rotator->id }})">
                              <i class="ti-mobile" data-toggle="modal" data-target="#phoneModal"></i>
                            </a>
                            <a href="#" class="rotatorsetting" attrid="{{ $rotator->id  }}" attrname="{{ $rotator->rotatorname }}" attstatus="{{ $rotator->status }}" attrtestnumber="{{ $rotator->test_number }}">
                              <i class="ti-pencil-alt" data-toggle="modal" data-target="#RotatorSettingseModal"></i>
                            </a> 
                            <a href='deleterotator/{{ $rotator->id }}' onclick="return confirm('Are you sure?')">
                              <i class="ti-trash" data-toggle="tooltip" title="Delete Rotator"></i>
                            </a>
                          </td>
                        </tr>

                        <tr>
                          <td colspan="12" class="hiddenRow">
                            <div class="accordian-body collapse" id="table-<?php echo $rotator->id;?>"> 
                            <table class="table table-striped" id="phoneTable">
                              <thead>
                                <tr class="info">
                                  <th></th>
                                  <th>#</th>
                                  <th>Phone Number</th>
                                  <th>Today's Leads</th>		
                                  <th>Max Daily</th>
                                  <th>This Week</th>	
                                  <th>Max Weekly</th>	
                                  <th>Report Total</th>	
                                  <th>Max Limit</th>	
                                  <th>Leads Left</th>	
                                  <th>Active</th>	
                                  <th>Label</th>
                                  <th>Exports</th>		
                                  <th>Action</th>	
                                </tr>
                              </thead>
                              <tbody>
                              @foreach($rotator->getroltetalList as $rowdata)
                                <tr >
                                  <td data-toggle="collapse"  class="accordion-toggle" data-target="#table2-{{ $rowdata->id }}">
                                    <a href="#">
                                      <i class="ti-eye"></i>
                                    </a>
                                  </td>
                                  <td>1</td>
                                  <td>{{ $rowdata->phone_number }}</td>
                                  <td>0</td>
                                  <td>{{ $rowdata->max_daily_leads }}</td>
                                  <td>0</td>
                                  <td>{{ $rowdata->max_weekly_leads}}</td>
                                  <td>0</td>
                                  <td>{{ $rowdata->max_limit_leads}}</td>
                                  <td>900</td>
                                  <td><i class="ti-check"></i></td>
                                  <!-- <td><i class="ti-close"></i></td> -->
                                  <td>{{ $rowdata->floor_label }}</td>
                                  <td><a href="{{ URL::to('unexportedlead') }}">0</a></td>
                                  <td>
                                    <a href="{{ URL::to('report') }}">
                                    <i class="ti-bar-chart"></i>
                                    </a> 
                                    <a href="#">
                                      <i class="ti-trash"></i>
                                    </a>
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="12" class="hiddenRow">
                                    <div class=" row accordian-body collapse" id="table2-{{ $rowdata->id }}"> 
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-6">
                                      <form class="forms-sample">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="exampleModalLabel">Phone Settings <br/><span style="font-size:11px;">Date Created: {{ $rowdata->created_at }}</span></h5>
                                          <button type="reset" class="btn btn-danger" aria-label="Reset">Reset Number</button>
                                        </div>
                                        <div class="modal-body">
                                          <div class="row">
                                            <div class="col-sm-6">
                                              <div class="form-group">
                                              <label for="exampleInputUsername1">Label</label>
                                              <input type="text" name="floor_label" value="{{ $rowdata->floor_label }}" class="form-control" id="exampleInputUsername1" placeholder="NYC Floor">
                                              </div>
                                            </div>
                                            <div class="col-sm-6">
                                              <div class="form-group">
                                                <label for="exampleInputUsername1">Status</label>
                                                <select class="form-control" id="exampleFormControlSelect2">
                                                  <option>Active</option>
                                                  <option>Paused</option>
                                                </select>  
                                              </div>
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <label for="exampleInputUsername1">Phone Number</label>
                                            <input type="text" name="phone_number" value="{{ $rowdata->phone_number }}" class="form-control" id="exampleInputUsername1" placeholder="Phone Number">
                                          </div>
                                          <div class="row">
                                            <div class="col-sm-4">
                                              <div class="form-group">
                                              <label for="exampleInputUsername1">Max Daily Leads</label>
                                              <input type="number" name="max_daily_leads" value="{{ $rowdata->max_daily_leads }}" class="form-control" placeholder="">
                                              </div>
                                            </div>
                                            <div class="col-sm-4">
                                              <div class="form-group">
                                                <label for="exampleInputUsername1">Max Weekly Leads</label>
                                                <input type="number" name="max_weekly_leads" value="{{ $rowdata->max_weekly_leads }}" class="form-control" placeholder="">
                                              </div>
                                            </div>
                                            <div class="col-sm-4">
                                              <div class="form-group">
                                                <label for="exampleInputUsername1">Max Limit Leads</label>
                                                <input type="number" name="max_limit_leads" value="{{ $rowdata->max_limit_leads }}" class="form-control" placeholder="">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-sm-6">
                                              <div class="form-check form-check-flat form-check-primary">
                                                <label class="form-check-label">
                                                  <input type="checkbox" name="test_number" value="{{ $rowdata->test_number }}" class="form-check-input">Test Number
                                                <i class="input-helper"></i></label>
                                              </div>
                                            </div>
                                            <div class="col-sm-6">
                                              <div class="form-group">
                                                <label for="exampleInputUsername1">Notifications Email</label>
                                                <input type="notification_email" name="eamil" value="{{ $rowdata->notification_email }}" class="form-control" placeholder="Notifications Email">
                                              </div>
                                            </div>
                                          </div>
                                        </div>  
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-primary">Save</button>
                                        </div>
                                      </form>
                                    </dive>
                                    </div> 
                                    <div class="col-sm-4"></div>
                                  </td>
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                            </div> 
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
                        {{ $rotatorD->links() }}
                    </div>
                    </div>
                    
                   
                    <!-- <div class="template-demo">
                      <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-primary">Previous</button>
                        <button type="button" class="btn btn-primary">1</button>
                        <button type="button" class="btn btn-primary">2</button>
                        <button type="button" class="btn btn-primary">3</button>
                        <button type="button" class="btn btn-primary">Next</button>
                      </div>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
<!--------------Phone Model---------->
<div class="modal fade" id="phoneModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="forms-sample" method="post" action="{{ 'rotatorlist' }}">
        @csrf()
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Phone Number</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="rotator_id" id="phone">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Phone Type</label>
            <div class="col-sm-5">
            <div class="form-check">
                <label class="form-check-label">
                <input type="radio" name="phone_type" value="1" class="form-check-input" onclick="onButtonClick()" checked>
                Phone Number
                <i class="input-helper"></i></label>
            </div>
            </div>
            <div class="col-sm-4">
            <div class="form-check">
                <label class="form-check-label">
                <input type="radio" name="phone_type"  value="2" class="form-check-input" onclick="onButtonClick1()">
                Integration
                <i class="input-helper"></i></label>
            </div>
            </div>
          </div>
          <div class="form-group" id="phone">
            <label for="exampleInputUsername1">Phone Numbers</label>
            <input type="text" name="phone_number" class="form-control" id="exampleInputUsername1" placeholder="1-(555)-555-5555">
          </div>
          <div class="form-group hide" id="integration">
            <label for="exampleInputUsername1">Select Integration</label>
            <Select class="form-control" name="integration">
              <option value="">Select</option>
              <option value="api">API</option>
            </select>
          </div>
          <div class="form-group row">
            <div class="col-sm-6">
              <div class="form-group">
              <label for="exampleInputUsername1">Label</label>
              <input type="text" name="floor_label" class="form-control" id="exampleInputUsername1" placeholder="NYC Floor">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
              <label for="exampleInputUsername1">Status</label>
              <select class="form-control" name="status" id="exampleFormControlSelect2">
                <option value="0">Active</option>
                <option value="1">Paused</option>
              </select>  
            </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-4">
              <div class="form-group">
              <label for="exampleInputUsername1" style="font-size:12px;">Max Daily Leads 
                <span class="badge badge-pill badge-primary">
                  <i class="ti-info" data-toggle="popover" data-placement="top" title="Max Daily Leads:"  data-content="Total amount of leads allocated to this number for the day (MST). After quota is reached no more leads are added. 0 means unlimited."></i>
                </span>
              </label>
              <input type="number" name="max_daily_leads" class="form-control">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="exampleInputUsername1" style="font-size:12px;">Max Weekly Leads
                <span class="badge badge-pill badge-primary">
                  <i class="ti-info" data-toggle="popover" data-placement="top" title="Max Weekly Leads:"  data-content="Total amount of leads allocated to this number for the week (Monday-Sunday). After quota is reached no more leads are added. 0 means unlimited."></i>
                </span>
                </label>
                <input type="number" name="max_weekly_leads" class="form-control">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="exampleInputUsername1" style="font-size:12px;">Max Limit Leads
                <span class="badge badge-pill badge-primary">
                  <i class="ti-info" data-toggle="popover" data-placement="top" title="Max limit Leads:"  data-content="otal amount of leads allocated to this number. After quota is reached no more leads are added. 0 means unlimited."></i>
                </span>
                </label>
                <input type="number" name="max_limit_leads" class="form-control">
              </div>
            </div>
          </div>
          <div class="form-check form-check-flat form-check-primary">
            <label class="form-check-label">
              <input type="checkbox" name="test_number" value="1231231234" class="form-check-input">Test Number
            <i class="input-helper"></i></label>
          </div>
        </div>  
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-primary" value="Submit">
        </div>
      </form> 
    </div>
  </div>
</div>
<!--------------Rotator Setting-------------------->
<div class="modal fade" id="RotatorSettingseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="forms-sample" method="post" action="rotatorlist">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Rotator Settings</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="text" class="rotatorid">
          <div class="form-group">
            <label for="exampleInputUsername1">Rotator Name</label>
            <input type="text" name="rotatorname" class="form-control rotatorname" id="exampleInputUsername1" placeholder="Rotator Name">
          </div>
          <div class="form-group">
              <label for="exampleInputUsername1">Rotator Status</label>
              <select class="form-control rotatorstatus" name="status" id="exampleFormControlSelect2">
                <option>Select</option>
                <option>Active</option>
                <option>Paused</option>
              </select>  
          </div>
          <div class="form-group">
            <label for="exampleInputUsername1">Lead Test Match</label>
            <input type="text" name="lead_test_match" class="form-control testmatch" id="exampleInputUsername1" placeholder="">
          </div>
        </div>  
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-primary" value="Submit">
        </div>
      </form> 
    </div>
  </div>
</div>
<!-----------End--------------->
        
@include('layouts.footer')
<!-----------Popover JS--------------->
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>   -->

<script>
function onButtonClick(){
  document.getElementById('phone').style.display ='block';
  document.getElementById('integration').style.display ='none';
}
function onButtonClick1(){
  document.getElementById('integration').style.display = 'block';
  document.getElementById('phone').style.display ='none';
}

// $(document).ready(function(){
  
//     $('[data-toggle="popover"]').popover();   
//     placement : 'top'
// });

function rotatorId(id)
  {
    $("#phone").val(id);
    
   
  }

  $(document).ready(function(){
    $(".rotatorsetting").click(function() {
      $(".rotatorid").val($(this).attr('attrid'));
      $(".rotatorname").val($(this).attr('attrname'));
      $(".rotatorstatus").val($(this).attr('attstatus'));
      $(".testmatch").val($(this).attr('attrtestnumber'));
    })
  });



</script>

<style>
  .hide{
  display:none;
}
.hiddenRow {
    padding: 0 !important;
}
</style>
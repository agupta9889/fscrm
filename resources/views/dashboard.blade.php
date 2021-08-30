@include('layouts.header')
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                @php $user = auth()->user(); @endphp
                  <h3 class="font-weight-bold">Welcome {{ $user->fname }}</h3>
                  <h6 class="font-weight-normal mb-0">All systems are running smoothly!</h6>
                </div>
                <div class="col-12 col-xl-4">
                 <div class="justify-content-end d-flex">
                  <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                    <div id="reportrange" class="btn btn-sm btn-light bg-white dropdown-toggle">
                     <i class="mdi mdi-calendar"></i>
                     <span></span>
                    </div>
                  </div>
                 </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card tale-bg">
                <div class="card-people mt-auto">
                  <img src="{{asset('assets/images/dashboard/people.svg')}}" alt="people">
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin transparent">
              <div class="row">
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-tale">
                    <div class="card-body">
                      <p class="mb-4">Accepted</p>
                      <p class="fs-30 mb-2">{{ $totalReportActCount }}</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="mb-4">Rejected</p>
                      <p class="fs-30 mb-2">0</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                  <div class="card card-light-blue">
                    <div class="card-body">
                      <p class="mb-4">Active Numbers</p>
                      <p class="fs-30 mb-2">{{ $activecount }}</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body">
                      <p class="mb-4">Inactive Numbers</p>
                      <p class="fs-30 mb-2">{{ $inactivecount }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                     <h4 class="card-title">Rotator List</h4>
                  </div>
                  <div class="col-md-6">
                    <span data-toggle="modal" data-target="#rotatorModal" class="btn btn-outline-success btn-icon-text" style="float:right;"> 
                    Add Rotator <i class="ti-control-forward"></i>
                    </span>
                </div>
                </div>
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
                            <span onClick="rotatorPhId({{ $rotator->id }})" class="badge badge-info">
                              <i class="ti-eye" data-toggle="tooltip" title="Show Data"></i>
                          </span>
                          </td>
                          <td>{{ $rotator->rotatorname }}</td>
                          <td>{{ $rotator->id }}</td>
                          <td>{{ $rotator->mode }}</td>
                          <td> {{ $rotator->getrotatorList->count() }} <label class="text-success">{{ $rotator->getrotatorList->where('status', '0')->count(); }}</label> / <label class="text-danger">{{ $rotator->getrotatorList->where('status', '1')->count(); }}</label></td>
                          <?php
                            $tmp = \App\Models\Salephone::first();
                          ?>
                          @if(!$tmp)
                          <td>0 <label class="text-success">0</label> / <label class="text-danger">0</label></td>
                          @else
                          <td>{{ $tmp->reportleadcount($rotator->id) }} <label class="text-success">{{ $tmp->reportleadcount($rotator->id) }}</label> / <label class="text-danger">0</label></td>
                          @endif
                          <td>
                            <?php if($rotator->status ==0){?>
                            <label class="badge badge-success">Active</label>
                            <?php } else { ?>
                            <label class="badge badge-danger">Paused</label>
                            <?php  } ?>
                          </td>   
                          <td>{{ $rotator->test_number }}</td>
                          <td>
                            <span onClick="rotatorId({{ $rotator->id }})" class="badge badge-success">
                              <i class="ti-mobile" data-toggle="modal" data-target="#phoneModal"></i>
                            </span>
                            <span class="rotatorsetting badge badge-warning" attrid="{{ $rotator->id  }}" attrname="{{ $rotator->rotatorname }}" attstatus="{{ $rotator->status }}" attrtestnumber="{{ $rotator->test_number }}">
                              <i class="ti-pencil-alt" data-toggle="modal" data-target="#RotatorSettingseModal"></i>
                            </span> 
                            <a href='deleterotator/{{ $rotator->id }}' class="badge badge-danger" onclick="return confirm('Are you sure?')">
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
                              <?php $i = 0 ?>
                              @foreach($rotator->getrotatorList as $rowdata)
                              <?php $i++; ?>
                                <tr>
                                  <td data-toggle="collapse"  class="accordion-toggle" data-target="#table2-{{ $rowdata->id }}">
                                    <span class="badge badge-info">
                                      <i class="ti-eye"></i>
                                    </span>
                                  </td>
                                  <td>{{ $i}}</td>
                                  @if($rowdata->phone_type === '0')
                                  <td class={{  $rowdata->current_selected === '0' ? 'active' : '' }} > {{ $rowdata->phone_number}} 
                                  @else
                                  <td class={{ $rowdata->current_selected === '0' ? 'active' : '' }} > {{ $rowdata->getIntegrationName->name }} 
                                  @endif
                                  <br/>
                                    <?php if( $rowdata->test_number === 1231231234) { ?>
                                      <span class="badge badge-warning">Test</span>
                                    <?php }?>
                                  </td>
                                  <?php
                                    $tmp = \App\Models\Salephone::first();
                                  ?>
                                  @if(!($tmp))
                                  <td>0</td>
                                  @else
                                  <td>{{ $tmp->salephonelist($rowdata->phone_number) }}</td>
                                  @endif
                                  <td>{{ $rowdata->max_daily_leads }}</td>
                                  @if(!$tmp)
                                  <td>0</td>
                                  @else
                                  <td>{{ $tmp->salephonelist($rowdata->phone_number)}}</td>
                                  @endif
                                  <td>{{ $rowdata->max_weekly_leads }}</td>
                                  @if(!$tmp)
                                  <td>0</td>
                                  @else
                                  <td>{{ $tmp->salephonelist($rowdata->phone_number) }}</td>
                                  @endif
                                  <td>{{ $rowdata->max_limit_leads }}</td>
                                  <?php
                                    $left = \App\Models\Salephone::first();
                                  ?>
                                  @if(!$left)
                                  <td>0</td>
                                  @else
                                  <td>{{ $rowdata->max_limit_leads - $left->salephonelistleftlead($rowdata->phone_number) }}</td>
                                  @endif
                                  <td>
                                  <?php if($rowdata->status == 0) { ?>
                                    <label class="badge badge-success"><i class="ti-check"></i></label>
                                  <?php } else { ?>
                                    <label class="badge badge-danger"><i class="ti-close"></i></label>
                                  <?php } ?>
                                  </td>
                                  <td>{{ $rowdata->floor_label }}</td>
                                  <td><a href="{{ URL::to('unexportedlead') }}/{{ $rowdata->id }}">{{ $rowdata->export_count }}</a></td>
                                  <td>
                                    <a href="{{ URL::to('report') }}/{{ $rowdata->id }}" class="badge badge-warning">
                                    <i class="ti-bar-chart"></i>
                                    </a> 
                                    <a href="deletephone/{{ $rowdata->id }}" class="badge badge-danger" onclick="return confirm('Are you sure?')">
                                      <i class="ti-trash"></i>
                                    </a>
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="12" class="hiddenRow">
                                    <div class=" row accordian-body collapse" id="table2-{{ $rowdata->id }}"> 
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-6">
                                      <form class="forms-sample" method="post" action="{{ 'rotatorlist' }}/{{ $rowdata->id }}">
                                      @csrf()
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
                                              <input type="hidden" name="id" value="{{ $rowdata->id }}">
                                              <input type="hidden" name="rotator_id" value="{{ $rowdata->rotator_id }}">
                                            </div>
                                            <div class="col-sm-6">
                                              <div class="form-group">
                                                <label for="exampleInputUsername1">Status</label>
                                                <select class="form-control" name="status" id="exampleFormControlSelect2">
                                                  <option value="0" <?php if($rowdata->status == 0){ ?> selected <?php } ?> >Active</option>
                                                  <option value="1" <?php if($rowdata->status == 1){ ?> selected <?php } ?> >Paused</option>
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
                                                  <input type="checkbox" name="test_number" <?php if( $rowdata->test_number == '1231231234'){ ?>checked<?php } ?> value="1231231234" class="form-check-input">Test Number
                                                <i class="input-helper"></i></label>
                                              </div>
                                            </div>
                                            <div class="col-sm-6">
                                              <div class="form-group">
                                                <label for="exampleInputUsername1">Notifications Email</label>
                                                <input type="email" name="notification_email" value="{{ $rowdata->notification_email }}" class="form-control" placeholder="Notifications Email">
                                              </div>
                                            </div>
                                          </div>
                                        </div>  
                                        <div class="modal-footer">
                                          <input type="submit" class="btn btn-primary" value="Update">
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
                  </div>
                </div>
              </div>
            </div>
          </div>       
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->

<!--------------Rotator Model---------->
<div class="modal fade" id="rotatorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="forms-sample" method="post" action="{{ 'addrotator' }}">
        @csrf()
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Rotator</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="exampleInputUsername1">Rotator Name</label>
            <input type="text" name="rotatorname" class="form-control" id="exampleInputUsername1" placeholder="Rotator Name">
            <input type="hidden" name="mode" value="sequential" class="form-control" id="exampleInputUsername1">
            <input type="hidden" name="test_number" value="1231231234"  class="form-control" id="exampleInputUsername1">
          </div>
        </div>  
        <div class="modal-footer">
          <input type="submit" class="btn btn-primary" value="Submit">
        </div>
      </form> 
    </div>
  </div>
</div>

        <!--------------Phone Model---------->
<div class="modal fade" id="phoneModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="forms-sample" method="post" action="{{ 'addphonesetting' }}">
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
                <input type="radio" name="phone_type" value="0" class="form-check-input" onclick="onButtonClick()" checked>
                Phone Number
                <i class="input-helper"></i></label>
            </div>
            </div>
            <div class="col-sm-4">
            <div class="form-check">
                <label class="form-check-label">
                <input type="radio" name="phone_type"  value="1" class="form-check-input" onclick="onButtonClick1()">
                Integration
                <i class="input-helper"></i></label>
            </div>
            </div>
          </div>
          <div class="form-group" id="phones">
            <label for="exampleInputUsername1">Phone Numbers</label>
            <input type="text" name="phone_number" class="form-control" id="exampleInputUsername1" placeholder="1-(555)-555-5555">
          </div>
          <div class="form-group hide" id="integrations">
            <label for="exampleInputUsername1">Select Integration</label>
            <Select class="form-control" name="integration_id">
              <option value="">Select</option>
              @foreach($integration as $row)
              <option value="{{$row->id}}">{{$row->name}}</option>
              @endforeach
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
      <form class="forms-sample" method="post" action="rotatoredit">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Rotator Settings</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          @csrf()
          <input type="hidden" name="id" class="rotatorid">
          <div class="form-group">
            <label for="exampleInputUsername1">Rotator Name</label>
            <input type="text" name="rotatorname" class="form-control rotatorname" id="exampleInputUsername1" placeholder="Rotator Name">
          </div>
          <div class="form-group">
              <label for="exampleInputUsername1">Rotator Status</label>
              <select name="status" class="form-control rotatorstatus" id="exampleFormControlSelect2">
                <option value="0">Active</option>
                <option value="1">Paused</option>
              </select>  
          </div>
          <div class="form-group">
            <label for="exampleInputUsername1">Lead Test Match</label>
            <input type="text" name="test_number" class="form-control testmatch" id="exampleInputUsername1">
          </div>
        </div>  
        <div class="modal-footer">
          <input type="submit" class="btn btn-primary" value="Update">
        </div>
      </form> 
    </div>
  </div>
</div>
<!-----------End--------------->
@include('layouts.footer')

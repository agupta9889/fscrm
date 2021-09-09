@include('layouts.header')
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
    
                  <h4 class="card-title">Assigned Numbers {{$phone}}</h4>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                          <tr>
                          
                              <th>Phone Number</th>
                              <th>Offer</th>
                              <th>Unexported</th>
                              <th>Active</th>
                              <th>Exports</th>
                          </tr>
                      </thead>
                      <tbody>

                    <?php if(!empty($assignedID) && !empty($assignee_users)) {?>
                      
                      @foreach($assignedID as $rowdata)
                          <tr>
                              <td> {{ $rowdata->phone_number }}</td>
                          
                              <td> {{ $rowdata->rotator_id }}</td>
                              <?php $Unexportedcount1 = \App\Models\Salephone::getUnexportedCountData($rowdata->id); ?>
                              <td>
                              <?php if(!empty($Unexportedcount1)){ ?>
                              <a href="{{ URL::to('unexportedlead') }}/{{ $rowdata->id }}">{{ $Unexportedcount1 }}</a></td>
                              <?php } else {?>
                                <a href="javascript:void(0);" onclick="notfound();">{{ $Unexportedcount1 }}</a>
                              <?php } ?>
                              <td>
                              <?php if($rowdata->status ==0){?>
                                <label class="badge badge-success">Active</label>
                              <?php } else { ?>
                               <label class="badge badge-danger">Paused</label>
                                <?php  } ?>
                              </td>  
                              <td><a href="{{ URL::to('exportlead') }}/{{ $rowdata->id }}">{{ $rowdata->export_count }}</a></td>
                          </tr>
                        @endforeach

                        
                        @foreach($assignee_users as $rowdata1)
                          <tr>
                              <td> {{ $rowdata1->username }}</td>
                          
                              <td> {{ $rowdata1->rotator_id }}</td>
                              <?php $Unexportedcount2 = \App\Models\Salephone::getUnexportedCountData($rowdata1->id); ?>
                              <td>
                                <?php if(!empty($Unexportedcount2)) { ?>
                                <a href="{{ URL::to('unexportedlead') }}/{{ $rowdata1->id }}">{{ $Unexportedcount2 }}</a></td>
                                <?php } else { ?>
                                  <a href="javascript:void(0);" onclick="notfound();">{{ $Unexportedcount2 }}</a>
                              <?php } ?>
                              <td>
                              <?php if($rowdata1->status ==0){?>
                                <label class="badge badge-success">Active</label>
                              <?php } else { ?>
                               <label class="badge badge-danger">Paused</label>
                                <?php  } ?>
                              </td>  
                              <td><a href="{{ URL::to('exportlead') }}/{{ $rowdata1->id }}">{{ $rowdata1->export_count }}</a></td>
                          </tr>
                          @endforeach
                         <?php } else { ?>
                        <tr>
                           <th>No Records Found!</th>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                    {{-- Pagination --}}
                    <div class="template-demo">
                    <div class="btn-group d-flex ">
                        {!! $assignedID->links() !!}
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

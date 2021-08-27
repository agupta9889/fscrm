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
                              <th>#</th>
                              <th>Phone Number</th>
                              <th>Offer</th>
                              <th>Unexported</th>
                              <th>Active</th>
                              <th>Exports</th>
                          </tr>
                      </thead>
                      <tbody>
                        <?php $i = 0; ?>
                      @forelse($assignedID as $rowdata)
                        <?php $i++ ?>
                          <tr>
                              <td>{{$i}}</td>
                              <td>{{ $rowdata->sales_number }}</td>
                              <?php
                                  $model = \App\Models\Rotator::find('1');
                                  $rotator = $model->getrotatorName($rowdata->rotator_id);
                              ?>
                              <td>{{ $rotator->rotatorname }}</td>
                              <td><a href="{{ URL::to('unexportedlead') }}/{{ $rowdata->phone_setting_id }}">0</a></td>
                              <?php
                                  $model = \App\Models\Phonesetting::find('1');
                                  $phonesetting = $model->getphoneSettingStatus($rowdata->phone_setting_id);
                              ?>
                              <td>
                              <?php if($phonesetting->status ==0){?>
                                <label class="badge badge-success">Active</label>
                              <?php } else { ?>
                               <label class="badge badge-danger">Paused</label>
                                <?php  } ?>
                              </td>  
                              <td><a href="{{ URL::to('exportlead') }}/{{ $rowdata->phone_setting_id }}">0</a></td>
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

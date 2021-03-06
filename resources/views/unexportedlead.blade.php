@include('layouts.header')
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                        <?php
                            $pid = Crypt::encryptString($unexpID->phone_setting_id); // encode the Phone Setting id
                        ?>
                      <h4 class="card-title">Unexported Leads <a href="{{ URL::to('exportlead')}}/{{ $pid}}">(<?php echo $exportCount->export_count;?> Exports)</a></h4>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <input type="hidden" id="export_count" value="{{ request()->id }}">
                    <input type="hidden" id="rotatorID" value="{{ $rotatorIDs->rotator_id }}">
                    <table class="table table-hover" id="exampleUnexp">
                      <thead>
                        <tr class="text-center">
                          <th class="nosort" data-orderable="false">Name</th>
                          <th data-orderable="false">Email</th>
                          <th data-orderable="false">Phone</th>
                          <th data-orderable="false">ZIP</th>
                          <th data-orderable="false">Country</th>
                          <th data-orderable="false">Sales Floor Number</th>
                          <th data-orderable="false">Date Created</th>
                        </tr>
                      </thead>
                      <tbody>
                      @forelse ($unexpleads as $unexpleadData)
                        <tr class="text-center">
                          <td>{{ $unexpleadData->first_name }} {{ $unexpleadData->last_name }}</td>
                          <td>{{ $unexpleadData->email }}</td>
                          <td>{{ $unexpleadData->phone }}</td>
                          <td>{{ $unexpleadData->zip }}</td>
                          <td>{{ $unexpleadData->country }}</td>
                          <td><label class="badge badge-success"><i class="ti-check"></i></label> <br/>{{ $unexpleadData->sales_number }}</td>
                          <td>{{ $unexpleadData->created_at }}</td>
                        </tr>
                        @empty
                        <tr>
                          <th>No Records Found!</th>
                        </tr>
                        @endforelse
			                </tbody>
                    </table>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
@include('layouts.footer')

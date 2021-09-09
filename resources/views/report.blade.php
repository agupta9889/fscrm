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
                      <h4 class="card-title">{{ $getsalenumber }} Leads</h4>
                    </div>
                    <div class="col-md-6">
                      <div class="justify-content-end d-flex">
                        <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                          <div id="reportrange" class="btn btn-sm btn-light bg-white dropdown-toggle">
                            <i class="mdi mdi-calendar"></i>
                            <span class='filter-data'></span>
                            <input type="hidden" name="start_date" id="id_start_date">
                            <input type="hidden" name="end_date" id="id_end_date">
                            <input type="hidden" name="phoneID" value="{{ request()->route('id') }}" id="phone_id">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <br/>
                  <div class="reportListByFilter">
                    <div class="row">
                      <div class="col-md-3 mb-4 stretch-card transparent">
                        <div class="card card-tale">
                          <div class="card-body">
                            <p class="mb-4">Total</p>
                            <p class="fs-30 mb-2 totalReportLeads">{{ $totalCount }}</p>
                            <!-- <p class="fs-30 mb-2">{{ $totalCount }}</p> -->
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3 mb-4 stretch-card transparent">
                        <div class="card card-dark-blue">
                          <div class="card-body">
                            <p class="mb-4">Accepted</p>
                            <p class="fs-30 mb-2 totalReportLeads">{{ $totalCount }}</p>
                            <!-- <p class="fs-30 mb-2">{{$totalCount}}</p> -->
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3 mb-4 stretch-card transparent">
                        <div class="card card-light-blue">
                          <div class="card-body">
                            <p class="mb-4">Rejected</p>
                            <p class="fs-30 mb-2">0</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3 mb-4 stretch-card transparent">
                        <div class="card card-light-danger">
                          <div class="card-body">
                            <p class="mb-4">Exports</p>
                            <p class="fs-30 mb-2">{{ $exportcount->export_count }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-hover" id="exampleReport">
                        <thead>
                          <tr class="text-center">
                            <th class="nosort" data-orderable="false">Name</th>
                            <th data-orderable="false">Email </th>
                            <th data-orderable="false">Phone</th>
                            <th data-orderable="false">Sales Floor Number</th>
                            <th data-orderable="false">Date Created</th>
                          </tr>
                        </thead>
                        <tbody>
                        @forelse($reportleads as $reportData)
                          <tr class="text-center">
                            <td>{{ $reportData->first_name }} {{ $reportData->last_name }}</td>
                            <td>{{ $reportData->email }}</td>
                            <td>{{ $reportData->phone }}</td>
                            <td><label class="badge badge-success"><i class="ti-check"></i></label> <br/>{{ $reportData->sales_number }}</td>
                            <td>{{ $reportData->created_at }}</td>
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
    
<script>
</script>
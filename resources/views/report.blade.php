@include('layouts.header')
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">9876543210 Leads</h4>
                  <div class="row">
                    <div class="col-md-3 mb-4 stretch-card transparent">
                      <div class="card card-tale">
                        <div class="card-body">
                          <p class="mb-4">Accepted</p>
                          <p class="fs-30 mb-2">4006</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-4 stretch-card transparent">
                      <div class="card card-dark-blue">
                        <div class="card-body">
                          <p class="mb-4">Rejected</p>
                          <p class="fs-30 mb-2">61344</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-4 stretch-card transparent">
                      <div class="card card-light-blue">
                        <div class="card-body">
                          <p class="mb-4">Active Numbers</p>
                          <p class="fs-30 mb-2">34040</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-4 stretch-card transparent">
                      <div class="card card-light-danger">
                        <div class="card-body">
                          <p class="mb-4">Inactive Numbers</p>
                          <p class="fs-30 mb-2">47033</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr class="text-center">
                          <th>Name</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Accepted</th>
                          <th>Date Created</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($reportleads as $reportData)
                        <tr class="text-center">
                          <td>{{ $reportData->first_name }} {{ $reportData->first_name }}</td>
                          <td>{{ $reportData->email }}</td>
                          <td>{{ $reportData->phone }}</td>
                          <td><label class="badge badge-success"><i class="ti-check"></i></label> <br/>{{ $reportData->sales_number }}</td>
                          <td>{{ $reportData->created_at }}</td>
                        </tr>
                        @endforeach
			                </tbody>
                    </table>
                    {{-- Pagination --}}
                    <div class="template-demo">
                      <div class="btn-group d-flex ">
                          {{ $reportleads->links() }}
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
    

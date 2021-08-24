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
                  <h4 class="card-title">Unexported Leads <a href="{{ URL::to('exportlead')}}/{{ $unexpID->phone_setting_id}}">(12 Exports)</a></h4>
                  </div>
                  <div class="col-md-6">
                  <button type="button" class="btn btn-outline-primary btn-icon-text" style="float:right;">
                    <i class="ti-download"></i>
                      Export
                  </button>
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
                      @forelse ($unexpleads as $unexpleadData)
                        <tr class="text-center">
                          <td>{{ $unexpleadData->first_name }} {{ $unexpleadData->last_name }}</td>
                          <td>{{ $unexpleadData->email }}</td>
                          <td>{{ $unexpleadData->phone }}</td>
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
                    {{-- Pagination --}}
                    <div class="template-demo">
                      <div class="btn-group d-flex ">
                          {{ $unexpleads->links() }}
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

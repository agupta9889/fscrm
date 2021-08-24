@include('layouts.header')
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Exports</h4>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>Date Created</th>
                          <th>Phone</th>
                          <th>Leads Num</th>
                          <th>Download</th>
                        </tr>
                      </thead>
                      <tbody>
                      @forelse($expleads as $expdata)
                        <tr>
                          <td>{{ $expdata->created_at }}</td>
                          <td>{{ $expdata->sales_number }}</td>
                          <td>{{ $expleadscount }}</td>
                          <td><i class="ti-download"></i> Download</td>
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
                          {{ $expleads->links() }}
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

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
                          <td>{{ $expdata->sale_number }}</td>
                          <td>{{ $expdata->leads_count }}</td>
                          <td><a href="{{ URL::to('csvexport') }}/{{ $expdata->id }}"><i class="ti-download"></i> Download</a></td>
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

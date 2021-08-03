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
                  <h4 class="card-title">Unexported Leads <a href="{{ URL::to('exportlead')}}">(12 Exports)</a></h4>
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
                        <tr>
                          <th>Sr. No.</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Accepted</th>
                          <th>Date Created</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                        <td>1</td>
                          <td>My Commission Bootcamp</td>
                          <td>arun@gmail.com</td>
                          <td>9889899898</td>
                          <td>Active</td>
                          <td>07/30/2021 02:19</td>
                        </tr>
			                </tbody>
                    </table>
                    <div class="template-demo">
                      <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-primary">Previous</button>
                        <button type="button" class="btn btn-primary">1</button>
                        <button type="button" class="btn btn-primary">2</button>
                        <button type="button" class="btn btn-primary">3</button>
                        <button type="button" class="btn btn-primary">Next</button>
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

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
                          <th>Sr. No.</th>
                          <th>Date Created</th>
                          <th>Phone</th>
                          <th>Leads Num</th>
                          <th>Download</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                        <td>1</td>
                        <td>07/30/2021 02:19</td>
                          <td>9889899898</td>
                          <td>5</td>
                          <td><i class="ti-download"></i> Download</td>
                          
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

    <div class="row">
                    <div class="col-md-3 mb-4 stretch-card transparent">
                      <div class="card card-tale">
                        <div class="card-body">
                          <p class="mb-4">Total</p>
                          <p class="fs-30 mb-2">{{ $totalReportActCount }}</p>
                          
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-4 stretch-card transparent">
                      <div class="card card-dark-blue">
                        <div class="card-body">
                          <p class="mb-4">Accepted</p>
                          <p class="fs-30 mb-2">{{ $totalReportActCount }}</p>
                          
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
                          <p class="fs-30 mb-2">{{ $export_count }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-hover" id="exampleReport2">
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
                      @forelse($totalReportAct as $reportData)
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

<!--Data Table-->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
<style>
  #exampleReport2_paginate { display: none; }
</style>
<script>
  // Data Table Report Filter 
$(document).ready(function() {
    $('#exampleReport2').DataTable({
        dom: 'Bfrtip',
        "order": [[ 4, "asc" ]], 
        buttons: {
          buttons: [
              { extend: 'excel', text: 'Export', className: 'getExportCount' }
          ]
     }
    });
});
</script>
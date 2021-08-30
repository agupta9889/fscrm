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
                      <button type="button"  class="btn btn-outline-primary btn-icon-text" style="float:right;">
                        <i class="ti-download"></i>
                          Export
                      </button>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <input type="hidden" id="export_count" value="{{ request()->id }}">
                    <table class="table table-hover display" id="example">
                      <thead>
                        <tr class="text-center">
                          <th class="nosort" data-orderable="false">Name</th>
                          <th data-orderable="false">Email</th>
                          <th data-orderable="false">Phone</th>
                          <th data-orderable="false">Accepted</th>
                          <th data-orderable="false">Date Created</th>
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
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css"/> -->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>

<script>
  $(document).ready(function() {
    $('#example').DataTable({
        dom: 'Bfrtip',
        buttons: {
          buttons: [
              { extend: 'excel', className: 'getExportCount' }
          ]
     }
    });
   
   $(".getExportCount").click(function(){
      var exportID = $("#export_count").val();
      //alert(exportID);
      //$.post('updateExportCount', {exportID:exportID});
      // $.post('updateExportCount', {exportID:exportID}, function(response){ 
      //         alert("success");
      //         //$("#mypar").html(response.amount);
      //   });

        $.ajax({
            type:'POST',
            url:'/updateExportCount',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(data){}
        });
   })

} );


</script>
<style>
  .dataTables_filter, .dataTables_info { display: none; }
  #example_paginate { display: none; }

</style>
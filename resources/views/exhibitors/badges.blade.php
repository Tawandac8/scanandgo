@extends('layouts.app')

@section('title')
All Exhibitors
@endsection

@section('content')
<div class="row my-4">
        <div class="col-lg-8 col-md-12 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>Business Visitors</h6>
                </div>
                @hasanyrole('admin|super-admin')
                <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    <span onclick="" style="cursor: pointer" class="badge badge-sm bg-gradient-dark">Add Badge</span>
                    <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fa fa-ellipsis-v text-dark"></i>
                    </a>
                    <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Printed Badges</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Synch Badges</a></li>
                    </ul>
                  </div>
                </div>
                @endhasanyrole
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Printed</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Printed Copies</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(isset($badges))
                    @foreach($badges as $badge)
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $badge->name }}</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        @if($badge->is_printed)
                        <span class="badge badge-sm bg-gradient-success">Yes</span>
                        @else
                        <span class="badge badge-sm bg-gradient-danger">No</span>
                        @endif
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold"> {{ $badge->printed_copies }} </span>
                      </td>
                      <td>
                        <span onclick="viewBadge({{ $badge->id }})" style="cursor: pointer" class="badge badge-sm bg-gradient-info">Go <i class="fa-solid fa-arrow-right"></i></span>
                      </td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer">
              <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    {{ $badges->links('components.pagination') }}
                  </div>
                </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-12 mb-md-0 mb-4">
            <div id="badge-wrapper" class="card text-center">
            <div id="badge" class="card-body pb-0">
              <h4 class="badge-name">John Doe</h4>
              <p class="text-sm text-dark">
                Programmer <br> ZITF Company
              </p>
              <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate('Badge sample')) !!} ">
            </div>
            <div class="card-footer">
              <span onclick="" class="btn bg-gradient-primary">Print</span>
            </div>
          </div>
        </div>
</div>
@endsection

@section('scripts')
<script>
    function printBadge(){
            var contents = $("#badge").html();
            var frame1 = $('<iframe />');
            frame1[0].name = "frame1";
            frame1.css({ "position": "absolute", "top": "-1000000px" });
            $("body").append(frame1);
            var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
            frameDoc.document.open();
            //Create a new HTML document.
            frameDoc.document.write('<html><head><title>DIV Contents</title>');
            //Append the external CSS file.
            frameDoc.document.write('<link href="{{ asset('css/print.css') }}" rel="stylesheet" type="text/css">');
            //Append the DIV contents.
            frameDoc.document.write(contents);
            frameDoc.document.write('</body></html>');
            frameDoc.document.close();
            setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                frame1.remove();
            }, 500);
        }

        function viewBadge(id){
            $.ajax({
                url: '/exhibitor/badge/'+id,
                type: 'GET',
                success: function(response) {
                    $('#badge-wrapper').html(response);
                    
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function startPrint(id){
            printBadge()
            changePrintStatus(id)
            
        }

        function changePrintStatus(id){
            $.ajax({
                url: '/exhibitor/badge/print/'+id,
                type: 'GET',
                success: function(response) {
                },
                error: function(xhr) {
                }
            });
        }
</script>
@endsection
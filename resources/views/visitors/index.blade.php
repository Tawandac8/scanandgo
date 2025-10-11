@extends('layouts.app')

@section('title')
{{ $event->name.' '.$event->year }} Visitors
@endsection

@section('content')
    <div class="row my-4">
        <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
          <div class="card mb-3">
            <div class="card-body">
              <div class="row">
                <div class="col-md-8">
                  <input onchange="searchVisitor(this.value)" class="form-control border-info search-by-qr" type="text" name="q" placeholder="Search/Scan Visitor" autofocus>
                  <input  class="form-control border-info search-by-name" type="text" name="name" placeholder="Enter Visitor Name">
                </div>
                <div class="col-md-4">
                  <select onchange="searchBy(this.value)" class="form-select" name="search_by" id="">
                    <option value="qr" selected>Scan Code/Search Code</option>
                    <option value="name">Search By Name</option>
                  </select>
                </div>
              </div>
              
            </div>
          </div>
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>Business Visitors</h6>
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    <span onclick="addNewVisitor()" style="cursor: pointer" class="badge badge-sm bg-gradient-dark">Add Visitor</span>
                    <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fa fa-ellipsis-v text-dark"></i>
                    </a>
                    <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Printed Badges</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Synch Badges</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Full Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Institution</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Position/Level</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Printed</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody class="visitors-table">
                    @if(isset($visitors))
                    @foreach($visitors as $visitor)
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $visitor->title.' '.$visitor->first_name.' '.$visitor->last_name }}</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold"> {{ $visitor->company_name }} </span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold"> {{ $visitor->position }} </span>
                      </td>
                      <td class="text-center">
                        @if(!$visitor->is_printed)
                        <span class="text-xs font-weight-bold text-danger"> <i class="fa-solid fa-xmark"></i> </span>
                        @else
                        <span class="text-xs font-weight-bold text-success"> <i class="fa-solid fa-check"></i> </span>
                        @endif
                      </td>
                      <td>
                        <span style="cursor: pointer" onclick="viewVisitor({{ $visitor->id }})" class="badge badge-sm bg-gradient-info">Go <i class="fa-solid fa-arrow-right"></i></span>
                      </td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer">
              <div class="col-lg-12 col-12 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    {{ $visitors->links('components.pagination') }}
                  </div>
                </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
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
          <div id="add-visitor-wrapper" class="card">
            <div class="card-body pb-0">
              <h4 class="badge-name">Add Visitor</h4>
              <label class="required" for="">Receipt Number</label>
              <div class="mb-3">
                <input name="receipt" type="text" class="form-control">
              </div>
              <label for="">Title</label>
              <div class="mb-3">
                <select name="title" class="form-select" id="">
                  <option value="" selected disabled>Select Title</option>
                  <option value="Amb">Ambassador</option>
                  <option value="Capt">Captain</option>
                  <option value="Col">Col</option>
                  <option value="Dr">Doctor</option>
                  <option value="Eng">Engineer</option>
                  <option value="Gen">General</option>
                  <option value="Hon">Honourable</option>
                  <option value="H.E">His Excellency</option>
                  <option value="Lt">Lt</option>
                  <option value="Major">Major</option>
                  <option value="Mr">Mr</option>
                  <option value="Mrs">Mrs</option>
                  <option value="Miss">Miss</option>
                  <option value="Ms">Ms</option>
                  <option value="Pres">Pres</option>
                  <option value="Prof">Professor</option>
                  <option value="Rev">Rev</option>
                  <option value="">None of the above</option>
                </select>
              </div>
              <label class="required" for="">First Name</label>
              <div class="mb-3">
                <input name="first_name" type="text" class="form-control" required>
              </div>
              <label class="required" for="">Last Name</label>
              <div class="mb-3">
                <input name="last_name" type="text" class="form-control" required>
              </div>
              <label for="" class="required">Position/Level</label>
              <div class="mb-3">
                <select name="position" id="" class="form-select" required>
                  <option value="" selected disabled>Select Position</option>
                  <option value="CEO">CEO</option>
                  <option value="CFO">CFO</option>
                  <option value="CMO">CMO</option>
                  <option value="COO">COO</option>
                  <option value="CTO">CTO</option>
                  <option value="ICT">ICT</option>
                  <option value="Marketing">Marketing</option>
                  <option value="Procurement">Procurement</option>
                  <option value="Project Manager">Project Manager</option>
                  <option value="Finance">Finance</option>
                  <option value="Sales">Sales</option>
                  <option value="Security">Security</option>
                  <option value="Self">Self Employed</option>
                  <option value="Self">Student</option>
                  <option value="">None of the above</option>
                </select>
              </div>
              <label for="">Institution <span class="text-muted">(Company/School)</span></label>
              <div class="mb-3">
                <input name="company" type="text" class="form-control">
              </div>
              <label for="" class="required">Email</label>
              <div class="mb-3">
                <input name="email" type="email" class="form-control" required>
              </div>
              <label for="" class="required">Phone</label>
              <div class="mb-3">
                <input name="phone" type="text" class="form-control" required>
              </div>
              <label for="" class="required">Country</label>
              <div class="mb-3">
                <select name="country" id="" class="form-select" required>
                  @foreach ($countries as $country)
                  <option value="{{ $country->id }}">{{ $country->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="card-footer">
              <span onclick="addVisitor()" class="btn bg-gradient-dark w-100">Add Visitor</span>
            </div>
          </div>
          <div id="edit-visitor-wrapper" class="card">
            <form action="" method="post">
              @csrf
              <div class="card-body pb-0">
              <h4 class="badge-name">Edit Visitor</h4>
              <label class="required" for="">Receipt Number</label>
              <div class="mb-3">
                <input name="receipt" type="text" class="form-control">
              </div>
              <label for="">Title</label>
              <div class="mb-3">
                <select name="title" class="form-select" id="">
                  <option value="" selected disabled>Select Title</option>
                  <option value="Amb">Ambassador</option>
                  <option value="Capt">Captain</option>
                  <option value="Col">Col</option>
                  <option value="Dr">Doctor</option>
                  <option value="Eng">Engineer</option>
                  <option value="Gen">General</option>
                  <option value="Hon">Honourable</option>
                  <option value="H.E">His Excellency</option>
                  <option value="Lt">Lt</option>
                  <option value="Major">Major</option>
                  <option value="Mr">Mr</option>
                  <option value="Mrs">Mrs</option>
                  <option value="Miss">Miss</option>
                  <option value="Ms">Ms</option>
                  <option value="Pres">Pres</option>
                  <option value="Prof">Professor</option>
                  <option value="Rev">Rev</option>
                  <option value="">None of the above</option>
                </select>
              </div>
              <label class="required" for="">First Name</label>
              <div class="mb-3">
                <input name="first_name" type="text" class="form-control" required>
              </div>
              <label class="required" for="">Last Name</label>
              <div class="mb-3">
                <input name="last_name" type="text" class="form-control" required>
              </div>
              <label for="" class="required">Position/Level</label>
              <div class="mb-3">
                <select name="position" id="" class="form-select" required>
                  <option value="" selected disabled>Select Position</option>
                  <option value="CEO">CEO</option>
                  <option value="CFO">CFO</option>
                  <option value="CMO">CMO</option>
                  <option value="COO">COO</option>
                  <option value="CTO">CTO</option>
                  <option value="ICT">ICT</option>
                  <option value="Marketing">Marketing</option>
                  <option value="Procurement">Procurement</option>
                  <option value="Project Manager">Project Manager</option>
                  <option value="Finance">Finance</option>
                  <option value="Sales">Sales</option>
                  <option value="Security">Security</option>
                  <option value="Self">Self Employed</option>
                  <option value="Self">Student</option>
                  <option value="">None of the above</option>
                </select>
              </div>
              <label for="">Institution <span class="text-muted">(Company/School)</span></label>
              <div class="mb-3">
                <input name="company" type="text" class="form-control">
              </div>
              <label for="" class="required">Email</label>
              <div class="mb-3">
                <input name="email" type="email" class="form-control" required>
              </div>
              <label for="" class="required">Phone</label>
              <div class="mb-3">
                <input name="phone" type="text" class="form-control" required>
              </div>
              <label for="" class="required">Country</label>
              <div class="mb-3">
                <select name="country" id="" class="form-select" required>
                  @foreach ($countries as $country)
                  <option value="{{ $country->name }}">{{ $country->name }}</option>
                  @endforeach
                </select>
              </div>
                            <label for="exampleSelectBorder">To which industry does your company belong?</label>
                            <div class="mb-3">
                            <select name="industry" class="form-select" id="exampleSelectBorder">
                                <option value="Agriculture">Agriculture</option>
                                <option value="Automotive,Transport & Logistics">Automotive,Transport & Logistics</option>
                                <option value="Banking, Insurance & Financial Services">Banking, Insurance & Financial Services</option>
                                <option value="Building & Construction">Building & Construction</option>
                                <option value="Chemicals & Pharmaceuticals">Chemicals & Pharmaceuticals</option>
                                <option value="Clothing & Textiles">Clothing & Textiles</option>
                                <option value="Education & Training">Education & Training</option>
                                <option value="FMCG">FMCG</option>
                                <option value="Food & Baverage Processing">Food & Baverage Processing</option>
                                <option value="Furniture">Furniture</option>
                                <option value="Health Services">Health Services</option>
                                <option value="ICT & Telecommunications">ICT & Telecommunications</option>
                                <option value="Manufacturing">Manufacturing</option>
                                <option value="Mining & Engineering">Mining & Engineering</option>
                                <option value="Printing &packaging">Printing &packaging</option>
                                <option value="Social Services">Social Services</option>
                                <option value="Tourism & Leisure">Tourism & Leisure</option>
                                <option value="Non Governmental Organisations">Non Governmental Organisations</option>
                                <option value="Research & Training">Research & Training</option>
                                <option value="Local Authority">Local Authority</option>
                                <option value="Other">Other</option>
                            </select>
                            </div>
                            
                            <label for="" class="form-label form-label-required">What is the purpose of your visit?</label>
                            <div class="mb-3">
                            <select id="purpose" name="purpose" class="form-select">
                                <option value="Check out competition">Check out competition</option>
                                <option value="Contact existing suppliers and buyers">Contact existing suppliers and buyers</option>
                                <option value="Gather general market/Product information">Gather general market/Product information</option>
                                <option value="Look for new ideas">Look for new ideas</option>
                                <option value="Identify new distributors/Agents/Partners">Identify new distributors/Agents/Partners</option>
                                <option value="Place purchasing order">Place purchasing order</option>
                                <option value="Place purchasing order">Seek solutions for special requirements</option>
                                <option value="Place purchasing order">Identify Investment Opportunities</option>
                                <option value="Place purchasing order">Seek research fields & partners</option>
                                <option value="Place purchasing order">Attend conference</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label form-label-required">How did you learn about this expo?
                            </label>
                            <select name="info_source" class="form-control">
                                <option value="Direct Mail/Invitation from an exhibitor">Direct Mail/Invitation from an exhibitor</option>
                                <option value="Direct Mail/Invitation from organizers">Direct Mail/Invitation from organizers</option>
                                <option value="Google Ads">Google Ads</option>
                                <option value="Youtube">Youtube</option>
                                <option value="Twitter">Twitter</option>
                                <option value="Facebook">Facebook</option>
                                <option value="Linkedin">Linkedin</option>
                                <option value="Social Media">Social Media</option>
                                <option value="Print Advertisement">Print Advertisement</option>
                                <option value="Radio Advert">Radio Advert</option>
                                <option value="Television Advert">Television Advert</option>
                                <option value="Trade / Business associatio">Trade / Business association</option>
                                <option value="Word of mouth">Word of mouth</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                            <label for="" class="form-label form-label-required">Which product group(s) are you interested in?
                            </label>
                            <div class="mb-3">
                            <select name="product_group" class="form-control">
                                <option value="Agriculture">Agriculture</option>
                                <option value="Automotive/Transport">Automotive/Transport</option>
                                <option value="Building & Construction">Building & Construction</option>
                                <option value="Chemicals/Pharmaceuticals">Chemicals/Pharmaceuticals</option>
                                <option value="Clothing & Textiles">Clothing & Textiles</option>
                                <option value="Consumer Goods">Consumer Goods</option>

                                <option value="Education & Training">Education & Training</option>
                                <option value="Food & Beverage Processing">Food & Beverage Processing</option>
                                <option value="Furniture">Furniture</option>
                                <option value="ICT & Telecommunications">ICT & Telecommunications</option>
                                <option value="Light & Heavy">Light & Heavy</option>
                                <option value="Industrial Goods">Industrial Goods</option>
                                <option value="Mining & Engineering">Mining & Engineering</option>
                                <option value="Printing & Packaging">Printing & Packaging</option>
                                <option value="Service Organizations">Service Organizations</option>
                                <option value="Socials Services">Socials Services</option>
                                <option value="Toursm & Leisure">Toursm & Leisure</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
              
            </div>
            <div class="card-footer">
              <button type="submit" class="btn bg-gradient-dark w-100">Update Visitor</button>
            </div>
            </form>
            
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

        function printerDetails(visitorId){
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "post",
                url: "/visitor/print/"+visitorId,
                data: {'_token':_token},
                success: function (response) {
                    

                }
            });
        }

        function startPrint(visitorId){
            printBadge()
            printerDetails(visitorId)
            
        }

        //add visitor
        function addVisitor(){
            var _token = $('meta[name="csrf-token"]').attr('content');
            var receipt = $('input[name="receipt"]').val();
            var title = $('select[name="title"]').val();
            var first_name = $('input[name="first_name"]').val();
            var last_name = $('input[name="last_name"]').val();
            var position = $('select[name="position"]').val();
            var company = $('input[name="company"]').val();
            var email = $('input[name="email"]').val();
            var phone = $('input[name="phone"]').val();
            var country = $('select[name="country"]').val();
            $.ajax({
                type: "post",
                url: "/visitor/add",
                data: {'_token':_token,'title':title,
                'first_name':first_name,'last_name':last_name,
                'position':position,'company':company,
                'email':email,'phone':phone,'country':country,'receipt':receipt},
                success: function (response) {
                    $('#badge-wrapper').html(response);
                    $('#add-visitor-wrapper').slideUp('fast','swing',function(){
                        $('#badge-wrapper').slideDown('fast','swing');
                    })
                }
            });
        }

        function viewVisitor(visitorId){
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "post",
                url: "/visitor/view/"+visitorId,
                data: {'_token':_token},
                success: function (response) {
                    $('#badge-wrapper').html(response);
                    $('#add-visitor-wrapper').slideUp('fast','swing',function(){
                        $('#badge-wrapper').slideDown('fast','swing');
                    })
                }
            });
        }

        function addNewVisitor(){
          $('#badge-wrapper').slideUp('fast','swing',function(){
            $('#add-visitor-wrapper').slideDown('fast','swing');
          });  
        }

        function searchBy(val){
            if(val == 'name'){
                $('.search-by-name').show();
                $('.search-by-qr').hide();
            }else{
                $('.search-by-name').hide();
                $('.search-by-qr').show();
            }
        }

        function searchVisitor(q){
            var _token = $('meta[name="csrf-token"]').attr('content');
            $('input[name="q"]').val('');
            $.ajax({
                type: "post",
                url: "/visitor/search",
                data: {'_token':_token,'q':q},
                success: function (response) {
                    if(response == 'false'){
                      alert('Visitor badge not found');
                      $('#badge-wrapper').slideUp('fast','swing',function(){
                        $('#add-visitor-wrapper').slideDown('fast','swing');
                    })
                    }else{
                      $('#badge-wrapper').html(response);
                    $('#add-visitor-wrapper').slideUp('fast','swing',function(){
                        $('#badge-wrapper').slideDown('fast','swing');
                    })
                    }
                    
                }
            });
        }

        $('input[name="name"]').on('keyup',function(){
            var name = $(this).val();
            
            $.ajax({
                type: "get",
                url: "/visitor/name/search/",
                data: {'name':name},
                success: function (response) {
                    $('.visitors-table').html(response);
                }
            });
        })

        function editVisitor(id){
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "post",
                url: "/visitor/edit/"+id,
                data: {'_token':_token},
                success: function (response) {
                  console.log(response);
                  $('#edit-visitor-wrapper form').attr('action','/visitor/update/'+id);
                  $('#edit-visitor-wrapper input[name="receipt"]').val(response['receipt_number']);
                  $('#edit-visitor-wrapper select[name="title"]').val(response['title']);
                  $('#edit-visitor-wrapper input[name="first_name"]').val(response['first_name']);
                  $('#edit-visitor-wrapper input[name="last_name"]').val(response['last_name']);
                  $('#edit-visitor-wrapper select[name="position"]').val(response['position']);
                  $('#edit-visitor-wrapper input[name="company"]').val(response['company_name']);
                  $('#edit-visitor-wrapper input[name="email"]').val(response['email']);
                  $('#edit-visitor-wrapper input[name="phone"]').val(response['phone']);
                  $('#edit-visitor-wrapper select[name="country"]').val(response['country_id']);
                  
                    $('#edit-visitor-wrapper').show();
                }
            });
        }
</script>
@endsection

@section('styles')
<style>
  #badge-wrapper{
    display: none;
  }

  .search-by-name{
    display: none;
  }

  #add-visitor-wrapper{
    display: none;
  }
</style>
@endsection
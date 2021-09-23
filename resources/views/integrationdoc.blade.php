@include('layouts.header')
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-1 grid-margin stretch-card"></div>
            <div class="col-md-10 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Integrations</h4>
                  <div class="table-responsive">
                    <table class="table table-hover ">
                      <tbody>
                      @if(Session::has('message'))
                      <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                      @endif
                      @foreach($integrationUser as $user)
                        <tr>
                          <td data-toggle="collapse" data-target="#table-{{$user->id}}" class="accordion-toggle">
                            <span class="badge badge-info">
                              <i class="ti-eye"></i>
                            </span></td>
                          <td>{{$user->name}}</td>
                          <td>{{$user->created_at}}</td>
                        </tr>
                        <tr>
                          <td colspan="12" class="hiddenRow">
                            <div class="accordian-body collapse" id="table-{{$user->id}}">
                              <table class="table table-striped" id="phoneTable">
                                <tbody>
                                  <tr>
                                    <div class="row col-sm-12">
                                      <form class="forms-sample" method="post" action="{{ URL::to('editintegrationdoc') }}/{{$user->id}}">
                                            @csrf()
                                        <div class="col-sm-6">
                                        <br/>
                                        <h4 class="card-title">Settings</h4>
                                          <div class="form-group">
                                            <label for="exampleInputUsername1">Name</label>
                                            <input type="text" name="name" value="{{$user->name}}" class="form-control" placeholder="Full Name"  required>
                                            <input type="hidden" name="updatedID" value="{{$user->id}}">
                                          </div>
                                          <div class="form-group">
                                            <label for="exampleInputEmail1">Username/Email ID</label>
                                            <input type="email" name="email" value="{{$user->email}}" class="form-control" placeholder="Email" required>
                                          </div>
                                          <div class="form-group">
                                            <label for="exampleInputUsername1">API Key</label>
                                            <input type="text" name="api_key" value="{{$user->api_key}}" class="form-control" placeholder="API Key" required>
                                          </div>
                                          <div class="form-group">
                                            <label for="exampleInputPassword1">Offer/Rotator Id</label>
                                            <input type="text" name="rotator_id" value="{{$user->rotator_id}}" class="form-control" placeholder="Offer/Rotator Id" required>
                                          </div>
                                          <a href="deleteintegration/{{$user->id}}" type="submit" class="form-group btn btn-danger" onclick="return confirm('Are you sure?')">Delete
                                          </a>
                                        </div>
                                        <div class="col-sm-6">
                                          <br/>
                                          <h4 class="card-title">Managers</h4>
                                            <div class="form-group">
                                              <label for="exampleInputUsername1">Users with role "account manager"</label>
                                              <br>

                                              @foreach($coachingmanager as $row)
                                              <?php $array= explode(',',$user->user_assign_id);
                                              //print_r($user->user_assign_id);
                                              ?>
                                              <label>
                                                <input class="form-group" name="user_assign_id[]" type="checkbox" value="{{ $row->id }}" <?php if (in_array($row->id, $array)){ echo "checked"; }?>> {{$row->fname}} {{$row->lname}} ({{$row->email}})
                                              </label><br>
                                              @endforeach
                                            </div>
                                            <div class="form-group">
                                              <input type="submit" class="form-group btn btn-primary ml-3" value="Update">
                                            </div>
                                        </div>
                                      </form>
                                    </div>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <br/>
                  <h3 class="card-title">Using Floor Solution outside IMA?</h3>
                  <p>Show this to your developer. He'll know what to do. Once you get a response back, please save the sales_number and show it to your lead on your members area.</p>
                  <blockquote>
                    <pre>
                      <code>
                      <pre style="color:#000000;background:#ffffff;"><span style="color:#5f5035; background:#ffffe8; ">&lt;?php</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#696969; background:#ffffe8; ">/*</span>
<span style="color:#696969; background:#ffffe8; ">Required Fields:</span>
<span style="color:#696969; background:#ffffe8; ">api_key</span>
<span style="color:#696969; background:#ffffe8; ">rotator_id</span>
<span style="color:#696969; background:#ffffe8; ">email</span>
<span style="color:#696969; background:#ffffe8; ">phone</span>
<span style="color:#696969; background:#ffffe8; ">&nbsp;*/</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#797997; background:#ffffe8; ">$email</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#0000e6; background:#ffffe8; ">"test@email.com"</span><span style="color:#800080; background:#ffffe8; ">;</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#797997; background:#ffffe8; ">$phone</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$first_name</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$last_name</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$state</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$address</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$city</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$zip</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$country</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#0000e6; background:#ffffe8; ">""</span><span style="color:#800080; background:#ffffe8; ">;</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#797997; background:#ffffe8; ">$fields_string</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#0000e6; background:#ffffe8; ">""</span><span style="color:#800080; background:#ffffe8; ">;</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#797997; background:#ffffe8; ">$api_key</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#0000e6; background:#ffffe8; ">"fsc188fsc734fsc106fsc"</span><span style="color:#800080; background:#ffffe8; ">;
    </span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#797997; background:#ffffe8; ">$rotator_id</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#0000e6; background:#ffffe8; ">"45"</span><span style="color:#800080;
background:#ffffe8; ">;
    </span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#797997; background:#ffffe8; ">$fields</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">[</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#000000; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color:#0000e6; background:#ffffe8; ">"api_key"</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#808030; background:#ffffe8; ">&gt;</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$api_key</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#000000; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color:#0000e6; background:#ffffe8; ">"rotator_id"</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#808030; background:#ffffe8; ">&gt;</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$rotator_id</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#000000; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color:#0000e6; background:#ffffe8; ">"email"</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#808030; background:#ffffe8; ">&gt;</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$email</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#000000; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color:#0000e6; background:#ffffe8; ">"phone"</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#808030; background:#ffffe8; ">&gt;</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$phone</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#000000; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color:#0000e6; background:#ffffe8; ">"first_name"</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#808030; background:#ffffe8; ">&gt;</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$first_name</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#000000; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color:#0000e6; background:#ffffe8; ">"last_name"</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#808030; background:#ffffe8; ">&gt;</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$last_name</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#000000; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color:#0000e6; background:#ffffe8; ">"state"</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#808030; background:#ffffe8; ">&gt;</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$state</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; ">    </span>
<span style="color:#000000; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color:#0000e6; background:#ffffe8; ">"address"</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#808030; background:#ffffe8; ">&gt;</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$address</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#000000; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color:#0000e6; background:#ffffe8; ">"city"</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#808030; background:#ffffe8; ">&gt;</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$city</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#000000; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color:#0000e6; background:#ffffe8; ">"zip"</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#808030; background:#ffffe8; ">&gt;</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$zip</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#000000; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color:#0000e6; background:#ffffe8; ">"country"</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#808030; background:#ffffe8; ">&gt;</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$country</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#808030; background:#ffffe8; ">]</span><span style="color:#800080; background:#ffffe8; ">;</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#800000; background:#ffffe8; font-weight:bold; ">foreach</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">(</span><span style="color:#797997; background:#ffffe8; ">$fields</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#800000; background:#ffffe8; font-weight:bold; ">as</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$key</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#808030; background:#ffffe8; ">&gt;</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$value</span><span style="color:#808030; background:#ffffe8; ">)</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#800080; background:#ffffe8; ">{</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#000000; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color:#797997; background:#ffffe8; ">$fields_string</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">.</span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$key</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">.</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#0000e6; background:#ffffe8; ">'='</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">.</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$value</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">.</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#0000e6; background:#ffffe8; ">'&amp;'</span><span style="color:#800080; background:#ffffe8; ">;</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#800080; background:#ffffe8; ">}</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#696969; background:#ffffe8; ">// echo $fields_string;</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#400000; background:#ffffe8; ">rtrim</span><span style="color:#808030; background:#ffffe8; ">(</span><span style="color:#797997; background:#ffffe8; ">$fields_string</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#0000e6; background:#ffffe8; ">'&amp;'</span><span style="color:#808030; background:#ffffe8; ">)</span><span style="color:#800080; background:#ffffe8; ">;</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#797997; background:#ffffe8; ">$url</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#0000e6; background:#ffffe8; ">'https://floorsolutioncrm.com/api/sales_phones'</span><span style="color:#800080; background:#ffffe8; ">;</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#696969; background:#ffffe8; ">//open connection</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#797997; background:#ffffe8; ">$ch</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#400000; background:#ffffe8; ">curl_init</span><span style="color:#808030; background:#ffffe8; ">(</span><span style="color:#808030; background:#ffffe8; ">)</span><span style="color:#800080; background:#ffffe8; ">;</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#696969; background:#ffffe8; ">//set the url, number of POST vars, POST data</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#400000; background:#ffffe8; ">curl_setopt</span><span style="color:#808030; background:#ffffe8; ">(</span><span style="color:#797997; background:#ffffe8; ">$ch</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#7d0045; background:#ffffe8; ">CURLOPT_URL</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$url</span><span style="color:#808030; background:#ffffe8; ">)</span><span style="color:#800080; background:#ffffe8; ">;</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#400000; background:#ffffe8; ">curl_setopt</span><span style="color:#808030; background:#ffffe8; ">(</span><span style="color:#797997; background:#ffffe8; ">$ch</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#7d0045; background:#ffffe8; ">CURLOPT_POST</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#008c00; background:#ffffe8; ">1</span><span style="color:#808030; background:#ffffe8; ">)</span><span style="color:#800080; background:#ffffe8; ">;</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#400000; background:#ffffe8; ">curl_setopt</span><span style="color:#808030; background:#ffffe8; ">(</span><span style="color:#797997; background:#ffffe8; ">$ch</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#7d0045; background:#ffffe8; ">CURLOPT_POSTFIELDS</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#797997; background:#ffffe8; ">$fields_string</span><span style="color:#808030; background:#ffffe8; ">)</span><span style="color:#800080; background:#ffffe8; ">;</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#400000; background:#ffffe8; ">curl_setopt</span><span style="color:#808030; background:#ffffe8; ">(</span><span style="color:#797997; background:#ffffe8; ">$ch</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#7d0045; background:#ffffe8; ">CURLOPT_RETURNTRANSFER</span><span style="color:#808030; background:#ffffe8; ">,</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#0f4d75; background:#ffffe8; ">true</span><span style="color:#808030; background:#ffffe8; ">)</span><span style="color:#800080; background:#ffffe8; ">;</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#696969; background:#ffffe8; ">//execute post</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#797997; background:#ffffe8; ">$result</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#808030; background:#ffffe8; ">=</span><span style="color:#000000; background:#ffffe8; "> </span><span style="color:#400000; background:#ffffe8; ">json_decode</span><span style="color:#808030; background:#ffffe8; ">(</span><span style="color:#400000; background:#ffffe8; ">curl_exec</span><span style="color:#808030; background:#ffffe8; ">(</span><span style="color:#797997; background:#ffffe8; ">$ch</span><span style="color:#808030; background:#ffffe8; ">)</span><span style="color:#808030; background:#ffffe8; ">)</span><span style="color:#800080; background:#ffffe8; ">;</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#696969; background:#ffffe8; ">//close connection</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#400000; background:#ffffe8; ">curl_close</span><span style="color:#808030; background:#ffffe8; ">(</span><span style="color:#797997; background:#ffffe8; ">$ch</span><span style="color:#808030; background:#ffffe8; ">)</span><span style="color:#800080; background:#ffffe8; ">;</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#400000; background:#ffffe8; ">var_dump</span><span style="color:#808030; background:#ffffe8; ">(</span><span style="color:#797997; background:#ffffe8; ">$result</span><span style="color:#808030; background:#ffffe8; ">)</span><span style="color:#800080; background:#ffffe8; ">;</span><span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#000000; background:#ffffe8; "></span>
<span style="color:#696969; background:#ffffe8; ">/*</span>
<span style="color:#696969; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;Sample Response:</span>
<span style="color:#696969; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;[response_code] =&gt; 200</span>
<span style="color:#696969; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;[response_message] =&gt; Lead successfully added</span>
<span style="color:#696969; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;[lead_id] =&gt; 2463338303</span>
<span style="color:#696969; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;[sales_number] =&gt; 8778220617</span>
<span style="color:#696969; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;[accepted] =&gt; true</span>
<span style="color:#696969; background:#ffffe8; "></span>
<span style="color:#696969; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;Possible Response Codes:</span>
<span style="color:#696969; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;401 = Invalid Credentials</span>
<span style="color:#696969; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;400 = Email Is Required</span>
<span style="color:#696969; background:#ffffe8; ">&nbsp;&nbsp;&nbsp;&nbsp;200 = Success</span>
<span style="color:#696969; background:#ffffe8; ">&nbsp;*/</span><span style="color:#000000; background:#ffffe8; "></span>
</pre>
                      </code>
                    </pre>
                  </blockquote>
                </div>
              </div>
            </div>
            <div class="col-md-1 grid-margin stretch-card"></div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
@include('layouts.footer')

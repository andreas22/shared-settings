<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title bariol-thin"><i class="fa fa-users"></i> API Users List</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-10 col-md-9 col-sm-9">

            </div>
            <div class="col-lg-2 col-md-3 col-sm-3">
                <a href="{{ route('apiuser.new') }}" class="btn btn-info"><i class="fa fa-plus"></i> Add New</a>
                <br /><br />
            </div>
        </div>
          <div class="row">
              <div class="col-md-12">

                  <?php $message = Session::get('message'); ?>
                  @if( isset($message) )
                  <div class="alert alert-success">{{$message}}</div>
                  @endif

                  @if(! $apiuser->isEmpty() )
                  <table class="table table-hover">
                          <thead>
                              <tr>
                                  <th>Username</th>
                                  <th>Description</th>
                                  <th>Created</th>
                                  <th>Modified</th>
                                  <th>Operations</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach($apiuser as $k => $d)
                              <tr>
                                  <td>{{$d->username}}</td>
                                  <td>{{$d->description}}</td>
                                  <td>
                                      {{$d->createdBy->email}}
                                      <h6>{{$d->created_at}}</h6>
                                  </td>
                                  <td>
                                      {{$d->modifiedBy->email}}
                                      <h6>{{$d->updated_at}}</h6>
                                  </td>
                                  <td>
                                      <a href="{{ URL::route('apiuser.view', array('id' => $d->id)) }}"><i class="fa fa-bars fa-2x"></i></a>
                                      <a href="{{ URL::route('apiuser.edit', array('id' => $d->id)) }}" style="margin: 2px 5px"><i class="fa fa-pencil-square-o fa-2x"></i></a>
                                      <a href="{{ URL::route('apiuser.delete', array('id' => $d->id)) }}"class="delete"><i class="fa fa-trash-o fa-2x"></i></a>
                                  </td>
                              </tr>
                          </tbody>
                          @endforeach
                  </table>
                  <div class="paginator">
                      {{$apiuser->links()}}
                  </div>
                  @else
                      <span class="text-warning"><h5>No results found.</h5></span>
                  @endif
              </div>
          </div>
    </div>
</div>

@section('footer_scripts')
<script>
    $(".delete").click(function(){
        return confirm("Are you sure to delete this item?");
    });
</script>
@stop

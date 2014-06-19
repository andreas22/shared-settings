<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title bariol-thin"><i class="fa fa-user"></i> Data List</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-10 col-md-9 col-sm-9">

            </div>
            <div class="col-lg-2 col-md-3 col-sm-3">
                <a href="{{URL::action('AdminDataController@edit')}}" class="btn btn-info"><i class="fa fa-plus"></i> Add New</a>
                <br /><br />
            </div>
        </div>
          <div class="row">
              <div class="col-md-12">

                  <?php $message = Session::get('message'); ?>
                  @if( isset($message) )
                  <div class="alert alert-success">{{$message}}</div>
                  @endif



                  @if(! $data->isEmpty() )
                  <table class="table table-hover">
                          <thead>
                              <tr>
                                  <th>Code</th>
                                  <th>Title</th>
                                  <th>Created</th>
                                  <th>Modified</th>
                                  <th>Operations</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach($data as $k => $d)
                              <tr>
                                  <td>{{$d->code}}</td>
                                  <td>{{$d->title}}</td>
                                  <td>
                                      {{$d->createdBy->email}}
                                      <h6>{{$d->created_at}}</h6>
                                  </td>
                                  <td>
                                      {{$d->modifiedBy->email}}
                                      <h6>{{$d->updated_at}}</h6>
                                  </td>
                                  <td>
                                      <a href="{{ route('sharedsettings.data.view', array('id' => $d->id)) }}"><i class="fa fa-bars fa-2x"></i></a>
                                      <a href="{{ route('sharedsettings.data.edit', array('id' => $d->id)) }}" style="margin: 2px 5px"><i class="fa fa-pencil-square-o fa-2x"></i></a>
                                      <a href="{{ route('sharedsettings.data.delete', array('id' => $d->id)) }}" class="delete"><i class="fa fa-trash-o fa-2x"></i></a>
                                  </td>
                              </tr>
                          </tbody>
                          @endforeach
                  </table>
                  <div class="paginator">
                      {{$data->links()}}
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

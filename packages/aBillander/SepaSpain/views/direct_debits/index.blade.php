@extends('layouts.master')

@section('title') {{ l('SEPA Direct Debits') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('sepasp/directdebits/create') }}" class="btn btn-sm btn-success" 
                title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('SEPA Direct Debits') }}
    </h2>        
</div>


<div id="div_sdds">
   <div class="table-responsive">

@if ($sdds->count())
<table id="sdds" class="table table-hover">
    <thead>
        <tr>
			<th>{{l('ID', [], 'layouts')}}</th>
			<th>{{ l('Due Date') }}</th>
			<th>{{ l('Name') }}</th>
      <!-- th class="text-center">{{l('Status', [], 'layouts')}}</th -->
      <th>{{ l('Customer Orders') }}</th>
      <th>{{ l('Production Orders') }}</th>
      <th class="text-center">{{l('Notes', [], 'layouts')}}</th>
			<th class="text-right"> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($sdds as $sdd)
		<tr>
			<td>{{ $sdd->id }}</td>
      <td>{{ abi_date_form_short($sdd->due_date) }}</td>
      <td>{{ $sdd->name }}</td>
      <!-- td class="text-center">
          @if ($sdd->is_dirty)
              <button type="button" class="btn btn-xs btn-danger" title="{{l('Need Update')}}">
                  <i class="fa fa-hand-stop-o"></i>
              </button>
          @else
              <button type="button" class="btn btn-xs btn-success" title="{{l('OK')}}">
                  <i class="fa fa-thumbs-o-up"></i>
              </button>
          @endif</td -->
      <td>{{ $sdd->nbr_customerorders() }}</td>
      <td>{{ $sdd->nbr_productionorders() }}</td>
      <td class="text-center">
          @if ($sdd->notes)
           <a href="javascript:void(0);">
              <button type="button" xclass="btn btn-xs btn-success" data-toggle="popover" data-placement="top" 
                      data-content="{{ $sdd->notes }}">
                  <i class="fa fa-paperclip"></i> {{l('View', [], 'layouts')}}
              </button>
           </a>
          @endif</td>

           <td class="text-right">

                <a class="btn btn-sm btn-blue" href="{{ URL::to('sepasp/directdebits/' . $sdd->id) }}" title="{{l('Show', [], 'layouts')}}"><i class="fa fa-folder-open-o"></i></a>

                <a class="btn btn-sm btn-warning" href="{{ URL::to('sepasp/directdebits/' . $sdd->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>

                <!-- a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                    href="{{ URL::to('sdds/' . $sdd->id ) }}" 
                    data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                    data-title="{{ l('Production Sheets') }} :: ({{$sdd->id}}) {{{ $sdd->name }}}" 
                    onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a -->

            </td>
		</tr>
	@endforeach
    </tbody>
</table>
{!! $sdds->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $sdds->total() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@endsection

@include('layouts/modal_delete')

@section('scripts') @parent 

<!-- script type="text/javascript">

$(document).ready(function() {
   $("#b_search_filter").click(function() {
      $('#search_status').val(1);
      $('#search_filter').show();
   });
});

</script -->

@endsection

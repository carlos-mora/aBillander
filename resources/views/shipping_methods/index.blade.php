@extends('layouts.master')

@section('title') {{ l('Shipping Methods') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        <a href="{{ URL::to('shippingmethods/create') }}" class="btn btn-sm btn-success" 
        		title="{{l('Add New Item', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Add New', [], 'layouts')}}</a>
    </div>
    <h2>
        {{ l('Shipping Methods') }}
    </h2>        
</div>

<div id="div_shippingmethods">
   <div class="table-responsive">

@if ($shippingmethods->count())
<table id="shippingmethods" class="table table-hover">
	<thead>
		<tr>
			<th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th>{{l('Shipping Method name')}}</th>
            <th>{{l('Carrier')}}</th>
            <th class="text-center">{{l('Active', [], 'layouts')}}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	@foreach ($shippingmethods as $shippingmethod)
		<tr>
			<td>{{ $shippingmethod->id }}</td>
            <td>{{ $shippingmethod->name }}</td>
            <td>{{ $shippingmethod->carrier ? $shippingmethod->carrier->name : '-' }}</td>

            <td class="text-center">@if ($shippingmethod->active) <i class="fa fa-check-square" style="color: #38b44a;"></i> @else <i class="fa fa-square-o" style="color: #df382c;"></i> @endif</td>

			<td class="text-right">
                @if (  is_null($shippingmethod->deleted_at))
                <a class="btn btn-sm btn-warning" href="{{ URL::to('shippingmethods/' . $shippingmethod->id . '/edit') }}" title="{{l('Edit', [], 'layouts')}}"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-sm btn-danger delete-item" data-html="false" data-toggle="modal" 
                		href="{{ URL::to('shippingmethods/' . $shippingmethod->id ) }}" 
                		data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
                		data-title="{{ l('Shipping Methods') }} :: ({{$shippingmethod->id}}) {{ $shippingmethod->name }} " 
                		onClick="return false;" title="{{l('Delete', [], 'layouts')}}"><i class="fa fa-trash-o"></i></a>
                @else
                <a class="btn btn-warning" href="{{ URL::to('shippingmethods/' . $shippingmethod->id. '/restore' ) }}"><i class="fa fa-reply"></i></a>
                <a class="btn btn-danger" href="{{ URL::to('shippingmethods/' . $shippingmethod->id. '/delete' ) }}"><i class="fa fa-trash-o"></i></a>
                @endif
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>

@stop

@include('layouts/modal_delete')

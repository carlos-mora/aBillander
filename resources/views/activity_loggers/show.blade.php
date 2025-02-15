@extends('layouts.master')

@section('title')  {{ l('aBillander LOG') }} @parent @stop


@section('content')

<div class="page-header">
    <div class="pull-right" style="padding-top: 4px;">
        @if( $logger_errors )
        <a href="javascript:void(0);" class="btn btn-xs btn-danger" onclick="return false;" 
                title="{{l('ERRORS')}}"><span class="badge">{{ $logger_errors }}</span> {{l('ERROR(S)')}}</a>
        @endif
        @if( $logger_warnings )
        <a href="javascript:void(0);" class="btn btn-xs btn-warning" onclick="return false;" 
                title="{{l('WARNINGS')}}"><span class="badge">{{ $logger_warnings }}</span> {{l('WARNING(S)')}}</a>
        @endif

        <a class="btn btn-danger delete-item" data-html="false" data-toggle="modal" 
            href="{{ URL::to('activityloggers/' . $activitylogger->id ) }}" 
            data-content="{{l('You are going to delete a record. Are you sure?', [], 'layouts')}}" 
            data-title="{{ l('aBillander LOG') }} :: ({{$activitylogger->id}}) {{ $activitylogger->name }}" 
            onClick="return false;" title="{{l('Delete LOG')}}"><i class="fa fa-trash-o"></i> {{l('Delete LOG')}}</a>

@if ($activitylogger->back_to)
        <a href="{{ $activitylogger->back_to }}" class="btn btn-lightblue"><i class="fa fa-mail-reply"></i> {{l('Back', 'layouts')}}</a>
@endif

        <a href="{{ URL::to('activityloggers') }}" class="btn btn-default"><i class="fa fa-mail-reply"></i> {{l('Back to aBillander LOG')}}</a>
    </div>
    <h2>
        <a href="{{ URL::to('activityloggers') }}">{{ l('aBillander LOG') }}</a> <span style="color: #cccccc;">/</span> {{$activitylogger->signature}}
    </h2>        
</div>




<div class="container-fluid">
   <div class="row">

      <div class="col-lg-2 col-md-2 col-sm-1">
         <!-- div class="list-group">
            <a id="b_main_data" href="#" class="list-group-item active">
               <i class="fa fa-asterisk"></i>
               &nbsp; {{ l('Updates') }}
            </a>
         </div -->
      </div>

      
      <div class="col-lg-8 col-md-8 col-sm-8">


<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">{{ $activitylogger->name }} - [{{ $activitylogger->user->getFullName() }}]</h3>
  </div>
  <div class="panel-body">



<div id="div_loggers">
   <div class="table-responsive">

@if ($loggers->count())
<table id="loggers" class="table table-hover">
    <thead>
        <tr>
            <th class="text-left">{{l('ID', [], 'layouts')}}</th>
            <th class="text-left">{{l('Date/Time')}}</th>
            <th class="text-left">{{l('Type')}}</th>
            <th class="text-left">{{l('Message')}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($loggers as $logger)
        <tr>
            <td class="auto-width">{{ $logger->id }}</td>
            <td class="auto-width">{{ $logger->date_added }} &nbsp; {{ sprintf( "(.%04s)",   intval(intval($logger->secs_added)/100.0) ) }}</td>
            <td class="auto-width"><span class="log-{{ $logger->level_name }}-format">{{ $logger->level_name }}</span></td>
            <td>{!! $logger->message !!}</td>
        </tr>
        @endforeach
    </tbody>
</table>
{!! $loggers->appends( Request::all() )->render() !!} 
<ul class="pagination"><li class="active"><span style="color:#333333;">{{l('Found :nbr record(s)', [ 'nbr' => $loggers->total() ], 'layouts')}} </span></li></ul>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{l('No records found', [], 'layouts')}}
</div>
@endif

   </div>
</div>



  </div>
</div>



      </div>

   </div>
</div>

@endsection


@include('layouts/modal_delete')


@section('styles')    @parent

<style>
  .log-showoff-format {
      color: #3a87ad;;
      font-weight: bold;
  }

  .log-INFO-format {
      color: black;
      font-weight: bold;
  }
  .log-WARNING-format {
      color: #e7a413;
      font-weight: bold;
  }
  .log-ERROR-format {
      color: red;
      font-weight: bold;
  }
  .log-TIMER-format {
      color: blue;
      font-weight: bold;
  }

  .log-PENDING-format {
      color: blue;
      font-weight: bold;
  }
  .log-SUCCESS-format {
      color: #38b44a;
      font-weight: bold;
  }
  .log-ATTENTION-format {
      color: red;
      font-weight: bold;
  }
  .auto-width {
        width: 1px; 
        white-space: nowrap;
  }
</style>

@endsection



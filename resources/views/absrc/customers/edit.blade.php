@extends('absrc.layouts.master')

@section('title') {{ l('Customers - Edit') }} @parent @stop


@section('content') 
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <!-- Button trigger modal -->
                <!-- button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal_new_address" title=" Nueva Dirección Postal ">
                  <i class="fa fa-plus"></i> Dirección
                </button -->

                <div class="btn-group">
                    <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown" title="{{l('Add Document', [], 'layouts')}}"><i class="fa fa-plus"></i> {{l('Document', [], 'layouts')}} &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="{{ route('absrc.orders.create.withcustomer', $customer->id) }}">{{l('Order', [], 'layouts')}}</a></li>
{{--
                      <li class="divider"></li>
                      <li><a href="{{ route('absrc.shippingslips.create.withcustomer', $customer->id) }}">{{l('Shipping Slip', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <li><a href="{{ route('absrc.invoices.create.withcustomer', $customer->id) }}">{{l('Invoice', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <!-- li><a href="#">Separated link</a></li -->
--}}
                    </ul>
                </div>

                <div class="btn-group" style="margin-right: 36px;">
                    <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="{{l('Go to', [], 'layouts')}}" style="background-color: #31b0d5;
border-color: #269abc;"><i class="fa fa-mail-forward"></i> &nbsp;{{l('Go to', [], 'layouts')}} &nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu pull-right">
                      <li><a href="{{ route('absrc.customer.orders', $customer->id) }}"><i class="fa fa-user-circle"></i> {{l('Orders', [], 'layouts')}}</a></li>
{{--
                      <li class="divider"></li>
                      <li><a href="{{ route('customer.shippingslips', $customer->id) }}"><i class="fa fa-user-circle"></i> {{l('Shipping Slips', [], 'layouts')}}</a></li>
                      <li class="divider"></li>
                      <li><a href="{{ route('customer.invoices', $customer->id) }}"><i class="fa fa-user-circle"></i> {{l('Invoices', [], 'layouts')}}</a></li>
--}}
                      <li class="divider"></li>
                      <li><a href="{{ URL::to('absrc/customers') }}">{{ l('Back to Customers') }}</a></li>
                    </ul>
                </div>

            </div>
            <h2><a href="{{ URL::to('absrc/customers') }}">{{ l('Customers') }}</a> <span style="color: #cccccc;">/</span> {{ $customer->name_regular }}</h2>
        </div>
    </div>
</div>

<div class="container-fluid">
   <div class="row">

      <div class="col-lg-2 col-md-2 col-sm-3">
         <div class="list-group">
            <a id="b_main" href="#" class="list-group-item active">
               <i class="fa fa-user"></i>
               &nbsp; {{ l('Main Data') }}
            </a>
            <a id="b_commercial" href="#commercial" class="list-group-item">
               <i class="fa fa-dashboard"></i>
               &nbsp; {{ l('Commercial') }}
            </a>
            <!-- a id="b_bankaccounts" href="#bankaccounts" class="list-group-item">
               <i class="fa fa-briefcase"></i>
               &nbsp; Bancos
            </a -->
            <a id="b_addressbook" href="#addressbook" class="list-group-item">
               <i class="fa fa-address-book"></i>
               &nbsp; {{ l('Address Book') }}
            </a>
{{--
            <!-- a id="b_specialprices" href="#specialprices" class="list-group-item">
               <i class="fa fa-list-alt"></i>
               &nbsp; Precios Especiales
            </a -->
            <!-- a id="b_accounting" href="#accounting" class="list-group-item">
               <i class="fa fa-book"></i></span>
               &nbsp; Contabilidad
            </a -->
            <a id="b_orders" href="#orders" class="list-group-item">
               <i class="fa fa-file-text-o"></i>
               &nbsp; {{ l('Orders') }}
            </a>
            <a id="b_products" href="#products" class="list-group-item">
               <i class="fa fa-th"></i>
               &nbsp; {{ l('Products') }}
            </a>
--}}
            <a id="b_pricerules" href="#pricerules" class="list-group-item">
               <i class="fa fa-gavel"></i>
               &nbsp; {{ l('Price Rules') }}
            </a>
{{--
            <!-- a id="b_statistics" href="#statistics" class="list-group-item">
               <i class="fa fa-bar-chart"></i>
               &nbsp; {{ l('Statistics') }}
            </a -->
--}}
            <a id="b_customeruser" href="#customeruser" class="list-group-item">
               <i class="fa fa-bolt"></i>
               &nbsp; {{ l('ABCC Access') }}
            </a>
         </div>
      </div>
      
      <div class="col-lg-10 col-md-10 col-sm-9">

         {!! Form::model($customer, array('route' => array('absrc.customers.update', $customer->id), 'method' => 'PUT', 'class' => 'form')) !!}
            
          @include('absrc.customers._panel_main_data')

          @include('absrc.customers._panel_commercial')

         {!! Form::close() !!}

          @include('absrc.customers._panel_addressbook')

{{--

          @include('customers._panel_orders')

          @include('customers._panel_products')
--}}

          @include('absrc.customers._panel_pricerules')

{{--
          @include('customers._panel_statistics')

--}}

@if (\App\Configuration::isTrue('ENABLE_CUSTOMER_CENTER') )

          @include('absrc.customers._panel_customeruser')

@endif

      </div><!-- div class="col-lg-10 col-md-10 col-sm-9" -->

   </div>
</div>
@endsection

@section('scripts')     @parent
<script type="text/javascript">
   function route_url()
   {
      $("#panel_main").hide();
      $("#panel_commercial").hide();
 //     $("#panel_bankaccounts").hide();
      $("#panel_addressbook").hide();
 //     $("#panel_specialprices").hide();
 //     $("#panel_accounting").hide();
      $("#panel_orders").hide();
      $("#panel_products").hide();
      $("#panel_pricerules").hide();
 //     $("#panel_statistics").hide();
      $("#panel_customeruser").hide();

      $("#b_main").removeClass('active');
      $("#b_commercial").removeClass('active');
 //     $("#b_bankaccounts").removeClass('active');
      $("#b_addressbook").removeClass('active');
 //     $("#b_specialprices").removeClass('active');
 //     $("#b_accounting").removeClass('active');
      $("#b_orders").removeClass('active');
      $("#b_products").removeClass('active');
      $("#b_pricerules").removeClass('active');
//      $("#b_statistics").removeClass('active');
      $("#b_customeruser").removeClass('active');
      
      if(window.location.hash.substring(1) == 'commercial')
      {
         $("#panel_commercial").show();
         $("#b_commercial").addClass('active');
         // document.f_cliente.codgrupo.focus();
      }
      else if(window.location.hash.substring(1) == 'addressbook')
      {
         $("#panel_addressbook").show();
         $("#b_addressbook").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'orders')
      {
         $("#panel_orders").show();
         $("#b_orders").addClass('active');
         getCustomerOrders();
      }
      else if(window.location.hash.substring(1) == 'products')
      {
         $("#panel_products").show();
         $("#b_products").addClass('active');
         getCustomerProducts();
      }
      else if(window.location.hash.substring(1) == 'pricerules')
      {
         $("#panel_pricerules").show();
         $("#b_pricerules").addClass('active');
         getCustomerPriceRules();
      }
      else if(window.location.hash.substring(1) == 'statistics')
      {
         $("#panel_statistics").show();
         $("#b_statistics").addClass('active');
      }
      else if(window.location.hash.substring(1) == 'customeruser')
      {
         $("#panel_customeruser").show();
         $("#b_customeruser").addClass('active');
      }
      else  
      {
         $("#panel_main").show();
         $("#b_main").addClass('active');
         // document.f_cliente.nombre.focus();
      }

      // Gracefully scrolls to the top of the page
      $("html, body").animate({ scrollTop: 0 }, "slow");
   }
   
   $(document).ready(function() {
      route_url();
      window.onpopstate = function(){
         route_url();
      }
   });

</script>
@endsection
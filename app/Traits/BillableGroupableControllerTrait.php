<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Customer;
use App\CustomerOrder as Document;
use App\CustomerOrderLine as DocumentLine;
use App\CustomerOrderLineTax as DocumentLineTax;

use App\CustomerInvoice;
use App\CustomerInvoiceLine;
use App\CustomerInvoiceLineTax;

use App\CustomerShippingSlip;
use App\CustomerShippingSlipLine;
use App\CustomerShippingSlipLineTax;
use App\DocumentAscription;

use App\Configuration;
use App\Sequence;
use App\Template;

trait BillableGroupableControllerTrait
{
    /**
    * Temporary stuff. Waiting for final location
    */
    public function aggregateDocumentList( $list, $params )
    {

//        1.- Recuperar los documntos
//        2.- Comprobar que están todos los de la lista ( comparando count() )

        try {

            $customer = $this->customer
                                ->with('currency')
                                ->findOrFail($params['customer_id']);

            $documents = $this->document
                                ->where('customer_id', $params['customer_id'])
                                ->where('status', '<>', 'closed')
                                ->with('lines')
                                ->with('lines.linetaxes')
    //                            ->with('customer')
    //                            ->with('currency')
    //                            ->with('paymentmethod')
                                ->orderBy('document_date', 'asc')
                                ->orderBy('id', 'asc')
                                ->findOrFail( $list );
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return redirect()->back()
                    ->with('error', l('Some records in the list [ :id ] do not exist', ['id' => implode(', ', $list)], 'layouts'));
            
        }


//        abi_r($list);die();

//        4.- Cear cabecera

        // Header
        $shipping_method_id = $customer->getShippingMethodId();

        $shipping_method = \App\ShippingMethod::find($shipping_method_id);
        $carrier_id = $shipping_method ? $shipping_method->carrier_id : null;
        // Common data
        $data = [
//            'company_id' => $this->company_id,
            'customer_id' => $customer->id,
//            'user_id' => $this->,

            'sequence_id' => $params['sequence_id'],

            'created_via' => 'aggregate_orders',

            'document_date' => $params['document_date'],

            'currency_conversion_rate' => $customer->currency->conversion_rate,
//            'down_payment' => $this->down_payment,

            'total_currency_tax_incl' => $documents->sum('total_currency_tax_incl'),
            'total_currency_tax_excl' => $documents->sum('total_currency_tax_excl'),
//            'total_currency_paid' => $this->total_currency_paid,

            'total_tax_incl' => $documents->sum('total_tax_incl'),
            'total_tax_excl' => $documents->sum('total_tax_excl'),

//            'commission_amount' => $this->commission_amount,

            // Skip notes

            'status' => 'draft',
            'onhold' => 0,
            'locked' => 0,

            'invoicing_address_id' => $customer->invoicing_address_id,
            'shipping_address_id' => $customer->shipping_address_id,
            'warehouse_id' => Configuration::getInt('DEF_WAREHOUSE'),
            'shipping_method_id' => $shipping_method_id,
            'carrier_id' => $carrier_id,
            'sales_rep_id' => $customer->sales_rep_id,
            'currency_id' => $customer->currency->id,
            'payment_method_id' => $customer->getPaymentMethodId(),
            'template_id' => $params['template_id'],
        ];

        // Model specific data
        $extradata = [
//            'payment_status' => 'pending',
//            'stock_status' => 'completed',
        ];


        // Let's get dirty
//        CustomerInvoice::unguard();
        $order = Document::create( $data + $extradata );
//        CustomerInvoice::reguard();


//        5a.- Añadir Albarán
//        5b.- Crear enlaces para trazabilidad de documentos
        // Initialize grouped lines collection
        // $grouped_lines = DocumentLine::whereIn($this->getParentModelSnakeCase().'_id', $list)->get();

        // Initialize totals
        $total_currency_tax_incl = 0;
        $total_currency_tax_excl = 0;
        $total_tax_incl = 0;
        $total_tax_excl = 0;

        // Initialize line sort order
        $i = 0;

        foreach ($documents as $document) {

            // Confirm Order
            $document->confirm();       // if needed
            
            # code...
            $i++;

            // Text line announces Shipping Slip
            $line_data = [
                'line_sort_order' => $i*10, 
                'line_type' => 'comment', 
                'name' => l('Order: :id [:date]', ['id' => $document->document_reference, 'date' => abi_date_short($document->document_date)]),
//                'product_id' => , 
//                'combination_id' => , 
                'reference' => $document->document_reference, 
//                'name', 
                'quantity' => 1, 
                'measure_unit_id' => Configuration::getInt('DEF_MEASURE_UNIT_FOR_PRODUCTS'),
//                    'cost_price', 'unit_price', 'unit_customer_price', 
//                    'prices_entered_with_tax',
//                    'unit_customer_final_price', 'unit_customer_final_price_tax_inc', 
//                    'unit_final_price', 'unit_final_price_tax_inc', 
//                    'sales_equalization', 'discount_percent', 'discount_amount_tax_incl', 'discount_amount_tax_excl', 
//                'total_tax_incl' => $document->total_currency_tax_incl, 
//                'total_tax_excl' => $document->total_currency_tax_excl, 
//                    'tax_percent', 'commission_percent', 
                'notes' => '', 
                'locked' => 0,
 //                 'customer_shipping_slip_id',
                'tax_id' => Configuration::get('DEF_TAX'),  // Just convenient
 //               'sales_rep_id'
            ];

            $order_line = DocumentLine::create( $line_data );

            $order->lines()->save($order_line);

            // Add current Shipping Slip lines to Invoice
            foreach ($document->lines as $line) {
                # code...
                $i++;

                // $invoice_line = $line->toInvoiceLine();

                // Common data
                $data = [
                ];

                $data = $line->toArray();
                // id
                unset( $data['id'] );
                // Parent document
                unset( $data[$this->getParentModelSnakeCase().'_id'] );
                // Dates
                unset( $data['created_at'] );
                unset( $data['deleted_at'] );
                // linetaxes
                unset( $data['linetaxes'] );
                // Sort order
                $data['line_sort_order'] = $i*10; 
                // Locked 
                $data['locked'] = 1; 

                // Model specific data
                $extradata = [
                ];

                // abi_r($this->getParentModelSnakeCase().'_id');
                // abi_r($data, true);


                // Let's get dirty
                DocumentLine::unguard();
                $order_line = DocumentLine::create( $data + $extradata );
                DocumentLine::reguard();

                $order->lines()->save($order_line);

                foreach ($line->taxes as $linetax) {

                    // $invoice_line_tax = $this->lineTaxToInvoiceLineTax( $linetax );
                    // Common data
                    $data = [
                    ];

                    $data = $linetax->toArray();
                    // id
                    unset( $data['id'] );
                    // Parent document
                    unset( $data[$this->getParentModelSnakeCase().'_line_id'] );
                    // Dates
                    unset( $data['created_at'] );
                    unset( $data['deleted_at'] );

                    // Model specific data
                    $extradata = [
                    ];


                    // Let's get dirty
                    DocumentLineTax::unguard();
                    $order_line_tax = DocumentLineTax::create( $data + $extradata );
                    DocumentLineTax::reguard();

                    $order_line->taxes()->save($order_line_tax);

                }
            }

            // Not so fast, Sony Boy

            // Close Order
            $document->close();


            // Document traceability
            //     leftable  is this document
            //     rightable is Customer Invoice Document
            $link_data = [
                'leftable_id'    => $document->id,
                'leftable_type'  => $document->getClassName(),

                'rightable_id'   => $order->id,
                'rightable_type' => Document::class,

                'type' => 'aggregate',
                ];

            $link = DocumentAscription::create( $link_data );
        }

        // Good boy, so far

        if ( $params['status'] == 'confirmed' )
            $order->confirm();




        // abi_r($grouped_lines, true);



        return redirect('customerorders/'.$order->id.'/edit')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $order->id], 'layouts'));
    }

}
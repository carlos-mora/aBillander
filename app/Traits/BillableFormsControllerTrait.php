<?php 

namespace App\Traits;

use Illuminate\Http\Request;

trait BillableFormsControllerTrait
{

    public function FormForProduct( $action )
    {

        switch ( $action ) {
            case 'edit':
                # code...
                return view($this->view_path.'._form_for_product_edit');
                break;
            
            case 'create':
                # code...
                return view($this->view_path.'._form_for_product_create');
                break;
            
            default:
                # code...
                // Form for action not supported
                return response()->json( [
                            'msg' => 'ERROR',
                            'data' => $action
                    ] );
                break;
        }
        
    }

    public function storeDocumentLine(Request $request, $document_id)
    {
        $line_type = $request->input('line_type', '');

        switch ( $line_type ) {
            case 'product':
                # code...
                return $this->storeDocumentLineProduct($request, $document_id);
                break;
            
            case 'service':
                # code...
                return $this->storeDocumentLineService($request, $document_id);
                break;
            
            default:
                # code...
                // Document Line Type not supported
                return response()->json( [
                            'msg' => 'ERROR',
                            'data' => $request->all()
                    ] );
                break;
        }
    }

    public function storeDocumentLineProduct(Request $request, $document_id)
    {
        // return response()->json(['order_id' => $order_id] + $request->all());

        $document = $this->document
                        ->with('customer')
                        ->with('taxingaddress')
                        ->with('salesrep')
                        ->with('currency')
                        ->find($document_id);

        if ( !$document )
            return response()->json( [
                    'msg' => 'ERROR',
                    'data' => $document_id,
            ] );


        $product_id     = $request->input('product_id');
        $combination_id = $request->input('combination_id', null);
        $quantity       = $request->input('quantity', 1.0);

        $pricetaxPolicy = intval( $request->input('prices_entered_with_tax', $document->customer->currentPricesEnteredWithTax( $document->document_currency )) );

        $params = [
            'prices_entered_with_tax' => $pricetaxPolicy,
            'discount_percent' => $request->input('discount_percent', 0.0),
            'unit_customer_final_price' => $request->input('unit_customer_final_price'),

            'line_sort_order' => $request->input('line_sort_order'),
            'notes' => $request->input('notes', ''),
        ];

        // More stuff
        if ($request->has('name')) 
            $params['name'] = $request->input('name');

        if ($request->has('sales_equalization')) 
            $params['sales_equalization'] = $request->input('sales_equalization');

        if ($request->has('measure_unit_id')) 
            $params['measure_unit_id'] = $request->input('measure_unit_id');

        if ($request->has('sales_rep_id')) 
            $params['sales_rep_id'] = $request->input('sales_rep_id');

        if ($request->has('commission_percent')) 
            $params['commission_percent'] = $request->input('commission_percent');


        // Let's Rock!

        $document_line = $document->addProductLine( $product_id, $combination_id, $quantity, $params );


        return response()->json( [
                'msg' => 'OK',
                'data' => $document_line->toArray()
        ] );
    }


    public function updateDocumentLine(Request $request, $line_id)
    {
        $line_type = $request->input('line_type', '');

        switch ( $line_type ) {
            case 'product':
                # code...
                return $this->updateDocumentLineProduct($request, $line_id);
                break;
            
            case 'service':
            case 'shipping':
                # code...
                return $this->updateDocumentLineService($request, $line_id);
                break;
            
            default:
                # code...
                // Document Line Type not supported
                return response()->json( [
                            'msg' => 'ERROR',
                            'data' => $request->all()
                    ] );
                break;
        }
    }

    public function updateDocumentLineProduct(Request $request, $line_id)
    {

        $params = [
//            'prices_entered_with_tax' => $pricetaxPolicy,
//            'discount_percent' => $request->input('discount_percent', 0.0),
//            'unit_customer_final_price' => $request->input('unit_customer_final_price'),

//            'line_sort_order' => $request->input('line_sort_order'),
//            'notes' => $request->input('notes'),
        ];

        // More stuff
        if ($request->has('quantity')) 
            $params['quantity'] = $request->input('quantity');

        if ($request->has('prices_entered_with_tax')) 
            $params['prices_entered_with_tax'] = $request->input('prices_entered_with_tax');

        if ($request->has('discount_percent')) 
            $params['discount_percent'] = $request->input('discount_percent');

        if ($request->has('unit_customer_final_price')) 
            $params['unit_customer_final_price'] = $request->input('unit_customer_final_price');

        if ($request->has('line_sort_order')) 
            $params['line_sort_order'] = $request->input('line_sort_order');

        if ($request->has('notes')) 
            $params['notes'] = $request->input('notes');


        if ($request->has('name')) 
            $params['name'] = $request->input('name');

        if ($request->has('sales_equalization')) 
            $params['sales_equalization'] = $request->input('sales_equalization');

        if ($request->has('measure_unit_id')) 
            $params['measure_unit_id'] = $request->input('measure_unit_id');

        if ($request->has('sales_rep_id')) 
            $params['sales_rep_id'] = $request->input('sales_rep_id');

        if ($request->has('commission_percent')) 
            $params['commission_percent'] = $request->input('commission_percent');


        // Let's Rock!
        $parent = strtolower( $this->model );
        $document_line = $this->document_line
                        ->with( $parent )
                        ->find($line_id);

        if ( !$document_line )
            return response()->json( [
                    'msg' => 'ERROR',
                    'data' => $line_id,
            ] );

        
        $document = $document_line->{$parent};
//        $document = $this->document->where('id', $this->model_snake_case.'_id')->first();

        $document_line = $document->updateProductLine( $line_id, $params );


        return response()->json( [
                'msg' => 'OK',
                'data' => $document_line->toArray()
        ] );
    }

}
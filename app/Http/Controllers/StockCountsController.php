<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\StockCount as StockCount;

class StockCountsController extends Controller
{


   protected $stockcount;

   public function __construct(StockCount $stockcount)
   {
        $this->stockcount = $stockcount;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stockcounts = $this->stockcount->with('warehouse')->orderBy('document_date', 'desc')->get();

        return view('stock_counts.index', compact('stockcounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $date = abi_date_short( \Carbon\Carbon::now() );
/*
        $sequenceList = \App\Sequence::listFor( StockCount::class );

        if ( !$sequenceList )
            return redirect('stockcounts')
                ->with('error', l('There is not any Sequence for this type of Document &#58&#58 You must create one first', [], 'layouts'));
*/
        return view('stock_counts.create', compact('date'));       // , 'sequenceList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $date_raw = $request->input('document_date');
        $date = \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $date_raw )->toDateString();

/*
        $seq = \App\Sequence::findOrFail( $request->input('sequence_id') );
        $doc_id = $seq->getNextDocumentId();
        $extradata = [  'document_prefix'      => $seq->prefix,
                        'document_id'          => $doc_id,
                        'document_reference'   => $seq->getDocumentReference($doc_id),
                        'document_date' => $date,
                     ];
*/                     
        $request->merge( ['document_date' => $date] );

        // abi_r($request->all());die();

        $this->validate($request, StockCount::$rules);

        $stockcount = $this->stockcount->create($request->all());

        return redirect('stockcounts')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $stockcount->id], 'layouts') . $stockcount->name);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function show(StockCount $stockcount)
    {
        return $this->edit($stockcount);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function edit(StockCount $stockcount)
    {
        $date = abi_date_short( $stockcount->document_date );

        return view('stock_counts.edit', compact('stockcount', 'date'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockCount $stockcount)
    {
        $date_raw = $request->input('document_date');
        $date = \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $date_raw )->toDateString();

        $request->merge( ['document_date' => $date] );

        // abi_r($request->all());die();

        $this->validate($request, StockCount::$rules);

        $stockcount->update($request->all());

        return redirect('stockcounts')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $stockcount->id], 'layouts') . $stockcount->name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockCount $stockcount)
    {
        $id = $stockcount->id;

        $stockcount->stockcountlines()->each(function($line) {
                    $line->delete();
                });

        $stockcount->delete();

        return redirect('stockcounts')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }
}

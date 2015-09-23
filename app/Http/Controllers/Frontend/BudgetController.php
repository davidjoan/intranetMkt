<?php namespace IntranetMkt\Http\Controllers\Frontend;

use IntranetMkt\Http\Requests;
use IntranetMkt\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Routing\ResponseFactory;
use IntranetMkt\Models\Budget;

class BudgetController extends Controller {

	protected $budget;
    private $responseFactory;

    public function __construct(Budget $budget, ResponseFactory $responseFactory)
    {
        $this->budget = $budget;
        $this->responseFactory = $responseFactory;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
	    $division_id   = $request->get('division_id',null,true);
        $cycle_id     = $request->get('cycle_id',null,true);

        $budgets =  $this->budget->newQuery()->with('cycle','division','user','cost_center','book_account');

        if(!(is_null($division_id) || $division_id == '')){
            $budgets->where('division_id','=', $division_id);
        }

        if(!(is_null($cycle_id) || $cycle_id == '')){
            $budgets->where('cycle_id','=', $cycle_id);
        }

         $budgets->whereHas('book_account', function ($q){
                $q->where('active', '=', '1');
            });

        return $budgets->get()->toJson();


	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}

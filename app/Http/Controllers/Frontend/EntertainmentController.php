<?php namespace IntranetMkt\Http\Controllers\Frontend;

use Illuminate\Contracts\Routing\ResponseFactory;
use IntranetMkt\Models\Entertainment;
use IntranetMkt\Http\Requests;
use IntranetMkt\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Carbon;
use DB;
use IntranetMkt\Models\Expense;

class EntertainmentController extends Controller {

    protected $entertainment;
    private $responseFactory;

    public function __construct(Entertainment $entertainment, ResponseFactory $responseFactory)
    {
        $this->entertainment = $entertainment;
        $this->responseFactory = $responseFactory;
    }

	/**
	 * Display a listing of the resource.
     * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        $expense_id = $request->get('expense_id',null,true);

        $page = 10;
        $data =  Entertainment::with('expense','expense.user');

        if(!(is_null($expense_id) || $expense_id == '')){
            $data->where('expense_id','=', $expense_id);
        }

        $data->orderBy('delivery_date','desc');
        return $this->responseFactory->json($data->paginate($page));
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
     * @param Request $request
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        /*
         * 'file_format_id', 'expense_id', 'consultor','type_entertainment', 'delivery_date',
        'place', 'qty_doctors', 'estimated_value', 'description', 'status'
         * */

        $file_format_id     = $request->get('file_format_id',null,true);
        $expense_id         = $request->get('expense_id',null,true);
        $consultor          = $request->get('consultor',null,true);
        $entertainment_type = $request->get('entertainment_type',null,true);
        $delivery_date      = $request->get('delivery_date',null,true);
        $place              = $request->get('place',null,true);
        $qty_doctors        = $request->get('qty_doctors',0,true);
        $estimated_value    = $request->get('estimated_value',null,true);
        $description        = $request->get('description',null,true);
        $status             = $request->get('status',null,true);

        $entertainment = new Entertainment();

        $entertainment->file_format_id     = $file_format_id;
        $entertainment->expense_id         = $expense_id;
        $entertainment->consultor          = $consultor;
        $entertainment->entertainment_type = $entertainment_type;
        $entertainment->delivery_date      = Carbon::createFromFormat('d/m/Y',$delivery_date);
        $entertainment->place              = $place;
        $entertainment->qty_doctors        = $qty_doctors;
        $entertainment->estimated_value    = $estimated_value;
        $entertainment->description        = $description;
        $entertainment->status             = $status;

        $entertainment->save();

        $entertainments = DB::table('entertainments')->where('expense_id','=',$expense_id)->get();

        $total = 0;
        foreach($entertainments as $data)
        {

            $total += $data->estimated_value;
        }

        $expense = Expense::find($expense_id);

        $expense->total_amount = $total;
        $expense->save();



        return $this->responseFactory->json($entertainment);
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
        $entertainment = Entertainment::find($id);
        $expense_id = $entertainment->expense_id;

        $this->entertainment->findOrFail($id)->delete();

        $entertainments = DB::table('entertainments')->where('expense_id','=',$expense_id)->get();

        $total = 0;
        foreach($entertainments as $data)
        {

            $total += $data->estimated_value;
        }

        $expense = Expense::find($expense_id);

        $expense->total_amount = $total;
        $expense->save();



        return $this->responseFactory->json($id." fue eliminado correctamente!");
	}

}

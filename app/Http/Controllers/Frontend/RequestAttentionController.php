<?php namespace IntranetMkt\Http\Controllers\Frontend;

use Illuminate\Routing\ResponseFactory;
use IntranetMkt\Http\Requests;
use IntranetMkt\Http\Controllers\Controller;

use Illuminate\Http\Request;
use IntranetMkt\Models\RequestAttention;
use Carbon;
use DB;
use IntranetMkt\Models\Expense;

class RequestAttentionController extends Controller {

    protected $request_attention;
    private $responseFactory;

    public function __construct(RequestAttention $request_attention, ResponseFactory $responseFactory)
    {
        $this->request_attention = $request_attention;
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
        $data =  RequestAttention::with('expense');

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
     *
     * @param Request $request
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        /*
 * 'file_format_id',
        'expense_id',
        'promotora', 'description', 'delivery_date', 'client_code', 'client',
        'price_unit', 'quantity', 'estimated_value', 'reason', 'status'
 * */

        $file_format_id     = $request->get('file_format_id',null,true);
        $expense_id         = $request->get('expense_id',null,true);
        $promotora          = $request->get('promotora',null,true);
        $description        = $request->get('description',null,true);
        //$delivery_date      = $request->get('delivery_date',null,true);
        $client_code        = $request->get('client_code',null,true);
        $client             = $request->get('client',null,true);
        $price_unit         = $request->get('price_unit',null,true);
        $quantity           = $request->get('quantity',0,true);
        $estimated_value    = $request->get('estimated_value',null,true);
        $reason             = $request->get('reason',null,true);
        $status             = $request->get('status',null,true);

        $request_attention = new RequestAttention();

        $request_attention->file_format_id     = $file_format_id;
        $request_attention->expense_id         = $expense_id;
        $request_attention->promotora          = $promotora;
        $request_attention->description        = $description;
        //$request_attention->delivery_date      = Carbon::createFromFormat('d/m/Y',$delivery_date);
        $request_attention->client_code        = $client_code;
        $request_attention->client             = $client;
        $request_attention->price_unit         = $price_unit;
        $request_attention->quantity           = $quantity;
        $request_attention->estimated_value    = $estimated_value;
        $request_attention->reason             = $reason;
        $request_attention->status             = $status;

        $request_attention->save();

        $request_attentions = DB::table('request_attentions')->where('expense_id','=',$expense_id)->get();

        $total = 0;
        foreach($request_attentions as $data)
        {

            $total += $data->estimated_value;
        }

        $expense = Expense::find($expense_id);

        $expense->total_amount = $total;
        $expense->save();

        return $this->responseFactory->json($request_attention);
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
        $request_attention = RequestAttention::find($id);
        $expense_id = $request_attention->expense_id;

        $this->request_attention->findOrFail($id)->delete();

        $request_attentions = DB::table('request_attentions')->where('expense_id','=',$expense_id)->get();

        $total = 0;
        foreach($request_attentions as $data)
        {

            $total += $data->estimated_value;
        }

        $expense = Expense::find($expense_id);

        $expense->total_amount = $total;
        $expense->save();



        return $this->responseFactory->json($id." fue eliminado correctamente!");
	}

}

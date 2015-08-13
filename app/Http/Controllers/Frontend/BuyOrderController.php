<?php namespace IntranetMkt\Http\Controllers\Frontend;

use Illuminate\Routing\ResponseFactory;
use IntranetMkt\Http\Requests;
use IntranetMkt\Http\Controllers\Controller;

use Illuminate\Http\Request;
use IntranetMkt\Models\BuyOrder;
use Carbon;
use IntranetMkt\Models\Expense;
use DB;
use IntranetMkt\Models\ExpenseType;

class BuyOrderController extends Controller {


    protected $buyOrder;
    private $responseFactory;

    public function __construct(BuyOrder $buyOrder, ResponseFactory $responseFactory)
    {
        $this->buyOrder = $buyOrder;
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
        $data =  BuyOrder::with('expense');

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

        $file_format_id    = $request->get('file_format_id',null,true);
        $expense_id        = $request->get('expense_id',null,true);
        $cost_center       = $request->get('cost_center',null,true);
        //$book_account      = $request->get('book_account',null,true);
        $code              = $request->get('code',null,true);
        $delivery_date     = $request->get('delivery_date',null,true);
        $inventory         = $request->get('inventory',0,true);
        $active            = $request->get('active',0,true);
        $expenditure       = $request->get('expenditure',0,true);
        $quantity          = $request->get('quantity',null,true);
        $price_unit        = $request->get('price_unit',null,true);
        $estimated_value   = $request->get('estimated_value',null,true);
        $description       = $request->get('description',null,true);
        $destination       = $request->get('destination',null,true);

        $buyOrder = new BuyOrder();
        $expense = Expense::find($expense_id);
        $expenseType = ExpenseType::find($expense->expense_type_id);
        $buyOrder->file_format_id  = $file_format_id;
        $buyOrder->expense_id      = $expense_id;
        $buyOrder->cost_center     = $cost_center;
        $buyOrder->book_account    = $expenseType->book_account->code;
        $buyOrder->code            = $code;
        $buyOrder->delivery_date   = Carbon::createFromFormat('d/m/Y',$delivery_date);
        $buyOrder->inventory       = ($inventory == 'on')?1:0;
        $buyOrder->active          = ($active == 'on')?1:0;
        $buyOrder->expenditure     = ($expenditure == 'on')?1:0;
        $buyOrder->quantity        = $quantity;
        $buyOrder->price_unit      = $price_unit;
        $buyOrder->estimated_value = $estimated_value;
        $buyOrder->description     = $description;
        $buyOrder->destination     = $destination;


        $buyOrder->save();

        $buyOrders    = DB::table('buy_orders')->where('expense_id','=',$expense_id)->get();

        $total = 0;
        foreach($buyOrders as $data)
        {

            $total += $data->estimated_value;
        }



        $expense->total_amount = $total;
        $expense->save();



        return $this->responseFactory->json($buyOrder);
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
        $buyOrder = BuyOrder::find($id);
        $expense_id = $buyOrder->expense_id;

        $this->buyOrder->findOrFail($id)->delete();

        $buyOrders = DB::table('buy_orders')->where('expense_id','=',$expense_id)->get();

        $total = 0;
        foreach($buyOrders as $data)
        {

            $total += $data->estimated_value;
        }

        $expense = Expense::find($expense_id);

        $expense->total_amount = $total;
        $expense->save();



        return $this->responseFactory->json($id." fue eliminado correctamente!");
	}

}

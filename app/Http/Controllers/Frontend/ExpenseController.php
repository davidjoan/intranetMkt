<?php namespace IntranetMkt\Http\Controllers\Frontend;

use Illuminate\Routing\ResponseFactory;
use IntranetMkt\Http\Requests;
use IntranetMkt\Http\Controllers\Controller;

use Illuminate\Http\Request;
use IntranetMkt\Models\Division;
use IntranetMkt\Models\Expense;
use Carbon;
use DB;
use IntranetMkt\User;

class ExpenseController extends Controller {


    protected $expense;
    private $responseFactory;

    public function __construct(Expense $expense, ResponseFactory $responseFactory)
    {
        $this->expense = $expense;
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

        $expense_type_id = $request->get('expense_type_id',null,true);
        $division_id   = $request->get('division_id',null,true);
        $user_id     = $request->get('user_id',null,true);
        $cycle_id     = $request->get('cycle_id',null,true);

        $query_in = $request->get('query',null,true);

        $expenses =  $this->expense->newQuery()->with('cycle','division','user','expense_type');

        $user = User::find($user_id);

        if(in_array($user->role_id, array(1,2,3))){
                $expenses->where('user_id','=', $user_id);
            $expenses->where('approval_4','=', '0');

        }elseif(in_array($user->role_id, array(4))){ //GD
            $expenses->where('approval_1','=', '1');

            foreach($user->divisions() as $division){
                $expenses->where('division_id','=', $division->id);
            }
        }elseif(in_array($user->role_id, array(5))){ //CG

            $expenses->where('approval_2','=', '1');
        }elseif(in_array($user->role_id, array(6))){ //CG
            $expenses->where('approval_3','=', '1');
        }

        if(!(is_null($division_id) || $division_id == '')){
            $expenses->where('division_id','=', $division_id);
        }

        if(!(is_null($expense_type_id) || $expense_type_id == '')){
            $expenses->where('expense_type_id','=', $expense_type_id);
        }

        if(!(is_null($cycle_id) || $cycle_id == '')){
            $expenses->where('cycle_id','=', $cycle_id);
        }

        if(!(is_null($query_in) || $query_in == '')){
             $expenses->where('name','LIKE','%'.strtoupper($query_in).'%');
        }

        return $expenses->get()->toJson();

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

        $expense_type_id   = $request->get('expense_type_id',null,true);
        $cycle_id          = $request->get('cycle_id',null,true);
        $user_id           = $request->get('user_id',null,true);
        $division_id       = $request->get('division_id',null,true);
        $application_date  = $request->get('application_date',null,true);
        $code              = $request->get('code',null,true);
        $name              = $request->get('name',null,true);
        $description       = $request->get('description',null,true);
        $approval_1        = $request->get('approval_1',0,true);
        $approval_2        = $request->get('approval_2',0,true);
        $approval_3        = $request->get('approval_3',0,true);
        $approval_4        = $request->get('approval_4',0,true);
        $approval_5        = $request->get('approval_5',0,true);
        $total_amount      = $request->get('total_amount',0.0,true);
        $active            = $request->get('active',1,true);
        $estimated_amount  = $request->get('estimated_amount',null,true);

        $last_insert_id = DB::getPdo()->lastInsertId();


        $expense = new Expense();

        $expense->expense_type_id  = $expense_type_id;
        $expense->user_id          = $user_id;
        $expense->cycle_id         = $cycle_id;
        $expense->division_id      = $division_id;
        $expense->application_date = Carbon::createFromFormat('d/m/Y',$application_date);
        $expense->code             = $last_insert_id+1;
        $expense->name             = $name;
        $expense->description      = $description;
        $expense->approval_1       = $approval_1;
        $expense->approval_2       = $approval_2;
        $expense->approval_3       = $approval_3;
        $expense->approval_4       = $approval_4;
        $expense->approval_5       = $approval_5;
        $expense->total_amount     = $total_amount;
        $expense->estimated_amount = $estimated_amount;
        $expense->active           = $active;

        $expense->save();
        $division = Division::find($division_id);
        $code = date("Y").str_pad($expense->id, 6, "0", STR_PAD_LEFT);
        $expense->code = $code;
        $expense->save();




        return $this->responseFactory->json($expense);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $expense = $this->expense->findOrFail($id);
        return $this->responseFactory->json($expense);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

     	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
     * @param Request $request
     *
	 * @return Response
	 */
	public function update($id, Request $request)
	{
        $expense_type_id   = $request->get('expense_type_id',null,true);
        $user_id           = $request->get('user_id',null,true);
        $cycle_id          = $request->get('cycle_id',null,true);
        $division_id       = $request->get('division_id',null,true);
        $application_date  = $request->get('application_date',null,true);
        $code              = $request->get('code',null,true);
        $name              = $request->get('name',null,true);
        $description       = $request->get('description',null,true);
        $approval_1        = $request->get('approval_1',null,true);
        $approval_2        = $request->get('approval_2',null,true);
        $approval_3        = $request->get('approval_3',null,true);
        $approval_4        = $request->get('approval_4',null,true);
        $approval_5        = $request->get('approval_5',null,true);
        $total_amount      = $request->get('total_amount',null,true);
        $estimated_amount  = $request->get('estimated_amount',null,true);
        $active            = $request->get('active',null,true);


        $expense = Expense::find($id);

        if(!is_null($expense_type_id)){
            $expense->expense_type_id = $expense_type_id;
        }

        if(!is_null($user_id)){
            $expense->user_id = $user_id;
        }

        if(!is_null($cycle_id)){
            $expense->cycle_id = $cycle_id;
        }

        if(!is_null($division_id)){
            $expense->division_id = $division_id;
        }

        if(!is_null($application_date)){
            $expense->application_date = Carbon::createFromFormat('d/m/Y',$application_date);;
        }

        if(!is_null($code)){
            $expense->code = $code;
        }

        if(!is_null($name)) {
            $expense->name = $name;
        }

        if(!is_null($description)) {
            $expense->description = $description;
        }

        if(!is_null($approval_1)) {
            $expense->approval_1 = $approval_1;
        }

        if(!is_null($approval_2)) {
            $expense->approval_2 = $approval_2;
        }

        if(!is_null($approval_3)) {
            $expense->approval_3 = $approval_3;
        }
        if(!is_null($approval_4)) {
            $expense->approval_4 = $approval_4;
        }
        if(!is_null($approval_5)) {
            $expense->approval_5 = $approval_5;
        }

        if(!is_null($total_amount)) {
            $expense->total_amount = $total_amount;
        }

        if(!is_null($estimated_amount)) {
            $expense->estimated_amount = $estimated_amount;
        }

        if(!is_null($active)) {
            $expense->active = $active;
        }

        $expense->save();

        return $this->responseFactory->json($expense);

    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        DB::table('buy_orders')->where('expense_id', '=', $id)->delete();
        DB::table('entertainments')->where('expense_id', '=', $id)->delete();
        DB::table('medical_campaigns')->where('expense_id', '=', $id)->delete();
        DB::table('request_attentions')->where('expense_id', '=', $id)->delete();
        DB::table('expense_details')->where('expense_id', '=', $id)->delete();
        DB::table('expense_amounts')->where('expense_id', '=', $id)->delete();
        $this->expense->findOrFail($id)->delete();
	}

}

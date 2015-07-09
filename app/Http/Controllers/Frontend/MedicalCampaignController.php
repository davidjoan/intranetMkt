<?php namespace IntranetMkt\Http\Controllers\Frontend;

use Illuminate\Routing\ResponseFactory;
use IntranetMkt\Http\Requests;
use IntranetMkt\Http\Controllers\Controller;

use Illuminate\Http\Request;
use IntranetMkt\Models\MedicalCampaign;
use Carbon;
use DB;
use IntranetMkt\Models\Expense;


class MedicalCampaignController extends Controller {

    protected $medical_campaign;
    private $responseFactory;

    public function __construct(MedicalCampaign $medical_campaign, ResponseFactory $responseFactory)
    {
        $this->medical_campaign = $medical_campaign;
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
        $data =  MedicalCampaign::with('expense');

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
 * 'file_format_id', 'expense_id', 'consultor','medical_campaign_type', 'delivery_date', 'place', 'cmp',
        'doctor', 'estimated_value', 'description', 'status'
 * */

        $file_format_id     = $request->get('file_format_id',null,true);
        $expense_id         = $request->get('expense_id',null,true);
        $consultor          = $request->get('consultor',null,true);
       // $medical_campaign_type = $request->get('medical_campaign_type',null,true);
        $delivery_date      = $request->get('delivery_date',null,true);
        $place              = $request->get('place',null,true);
        $cmp                = $request->get('cmp',null,true);
        $doctor             = $request->get('doctor',null,true);
        $estimated_value    = $request->get('estimated_value',null,true);
        $description        = $request->get('description',null,true);
        $status             = $request->get('status',null,true);

        $medical_campaign = new MedicalCampaign();

        $medical_campaign->file_format_id     = $file_format_id;
        $medical_campaign->expense_id         = $expense_id;
        $medical_campaign->consultor          = $consultor;
       // $medical_campaign->medical_campaign_type = $medical_campaign_type;
        $medical_campaign->delivery_date      = Carbon::createFromFormat('d/m/Y',$delivery_date);
        $medical_campaign->place              = $place;
        $medical_campaign->cmp                = $cmp;
        $medical_campaign->doctor             = $doctor;
        $medical_campaign->estimated_value    = $estimated_value;
        $medical_campaign->description        = $description;
        $medical_campaign->status             = $status;

        $medical_campaign->save();

        $medical_campaigns = DB::table('medical_campaigns')->where('expense_id','=',$expense_id)->get();

        $total = 0;
        foreach($medical_campaigns as $data)
        {

            $total += $data->estimated_value;
        }

        $expense = Expense::find($expense_id);

        $expense->total_amount = $total;
        $expense->save();



        return $this->responseFactory->json($medical_campaign);
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
        $medical_campaign = MedicalCampaign::find($id);
        $expense_id = $medical_campaign->expense_id;

        $this->medical_campaign->findOrFail($id)->delete();

        $medical_campaigns = DB::table('medical_campaigns')->where('expense_id','=',$expense_id)->get();

        $total = 0;
        foreach($medical_campaigns as $data)
        {

            $total += $data->estimated_value;
        }

        $expense = Expense::find($expense_id);

        $expense->total_amount = $total;
        $expense->save();



        return $this->responseFactory->json($id." fue eliminado correctamente!");
	}

}
